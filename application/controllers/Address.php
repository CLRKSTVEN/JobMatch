<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Address extends CI_Controller
{
    private const LIMIT_PROVINCE = 'Davao Oriental';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Address_model');
        $this->output->set_content_type('application/json; charset=utf-8');
        if (method_exists($this->output, 'enable_profiler')) {
            $this->output->enable_profiler(false);
        }
        if (ob_get_level() > 0) { @ob_end_clean(); }
    }

    private function is_worker(): bool
    {
        return strtolower((string) $this->session->userdata('role')) === 'worker';
    }

    public function api()
    {
        try {
            $scope    = strtolower(trim((string)$this->input->get('scope', true)));
            $province = trim((string)$this->input->get('province', true));
            $city     = trim((string)$this->input->get('city', true));

            switch ($scope) {
                case 'province':
                    if ($this->is_worker()) {
                        return $this->_out(true, 'ok', [self::LIMIT_PROVINCE]);
                    }
                    $items = $this->Address_model->get_provinces();
                    return $this->_out(true, 'ok', $items);

                case 'city':
                    if ($this->is_worker()) {
                        // Return only the DB's Mati variant(s) from Davao Oriental
                        $allCities = $this->Address_model->get_cities(self::LIMIT_PROVINCE);
                        $matiOnly  = array_values(array_filter($allCities, static function($c){
                            return stripos($c, 'mati') !== false; // matches "City of Mati", "Mati City", "Mati"
                        }));
                        return $this->_out(true, 'ok', $matiOnly ?: []);
                    }
                    if ($province === '') return $this->_out(false, 'Missing province', [], 400);
                    $items = $this->Address_model->get_cities($province);
                    return $this->_out(true, 'ok', $items);

                case 'brgy':
                    if ($this->is_worker()) {
                        $province = self::LIMIT_PROVINCE;
                        if ($city === '') {
                            return $this->_out(false, 'Missing city', [], 400);
                        }

                        $allCities = $this->Address_model->get_cities($province);
                        $exactCity = null;
                        foreach ($allCities as $c) {
                            if (strcasecmp($c, $city) === 0) { $exactCity = $c; break; }
                        }
                        if ($exactCity === null) {
                            foreach ($allCities as $c) {
                                if (stripos($c, 'mati') !== false) { $exactCity = $c; break; }
                            }
                        }

                        if ($exactCity === null) {
                            return $this->_out(true, 'ok', []);
                        }

                        $city = $exactCity; 
                    } else {
                        if ($province === '' || $city === '') {
                            return $this->_out(false, 'Missing province/city', [], 400);
                        }
                    }

                    $items = $this->Address_model->get_barangays($province, $city);
                    return $this->_out(true, 'ok', $items);

                default:
                    return $this->_out(false, 'Invalid scope', [], 400);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Address API error: '.$e->getMessage());
            return $this->_out(false, 'Server error', [], 500);
        }
    }

    private function _out($ok, $msg, $items = [], $status = 200)
    {
        $payload = [
            'ok'    => (bool)$ok,
            'msg'   => (string)$msg,
            'items' => $items,
        ];
        if ($this->config->item('csrf_protection')) {
            $payload['csrf_name'] = $this->security->get_csrf_token_name();
            $payload['csrf_hash'] = $this->security->get_csrf_hash();
        }

        $this->output->set_status_header($status);
        $this->output->set_output(json_encode($payload, JSON_UNESCAPED_UNICODE));
        return;
    }
}

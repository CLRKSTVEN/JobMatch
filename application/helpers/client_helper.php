<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('client_extract_org_fields')) {
    /**
     * Normalize organization-related fields from a client profile.
     *
     * @param mixed $profile Object/array containing client data.
     * @return array{company:string,employer:string,business:string}
     */
    function client_extract_org_fields($profile): array
    {
        $company  = '';
        $employer = '';
        $business = '';

        if (is_object($profile)) {
            $company  = $profile->companyName    ?? ($profile->company_name    ?? '');
            $employer = $profile->employer       ?? '';
            $business = $profile->business_name  ?? ($profile->businessName    ?? '');
        } elseif (is_array($profile)) {
            $company  = $profile['companyName']  ?? ($profile['company_name']  ?? '');
            $employer = $profile['employer']     ?? '';
            $business = $profile['business_name']?? ($profile['businessName']  ?? '');
        }

        return [
            'company'  => trim((string)$company),
            'employer' => trim((string)$employer),
            'business' => trim((string)$business),
        ];
    }
}

if (!function_exists('client_is_individual_employer')) {
    /**
     * Determine whether a client should be considered an individual employer.
     *
     * @param mixed $profile
     */
    function client_is_individual_employer($profile): bool
    {
        $fields = client_extract_org_fields($profile);
        return $fields['company'] === '' && $fields['employer'] === '';
    }
}

if (!function_exists('client_org_label')) {
    /**
     * Compute the organization label to display for a client.
     *
     * @param mixed  $profile
     * @param string $fallback Label when no employer/company is provided.
     */
    function client_org_label($profile, string $fallback = 'Individual Employer'): string
    {
        $fields   = client_extract_org_fields($profile);
        $fallback = trim($fallback);

        if ($fields['company'] !== '') {
            return $fields['company'];
        }
        if ($fields['employer'] !== '') {
            return $fields['employer'];
        }
        $isIndividual = ($fields['company'] === '' && $fields['employer'] === '');
        if ($fields['business'] !== '' && !$isIndividual) {
            return $fields['business'];
        }

        return $fallback !== '' ? $fallback : 'Individual Employer';
    }
}

if (!function_exists('client_has_company_position_field')) {
    /**
     * Detect if the client_profile table has the company_position column.
     */
    function client_has_company_position_field(): bool
    {
        static $hasField;
        if ($hasField !== null) {
            return $hasField;
        }

        $CI = get_instance();
        if (!isset($CI->db)) {
            $CI->load->database();
        }
        $hasField = $CI->db->field_exists('company_position', 'client_profile');
        return $hasField;
    }
}

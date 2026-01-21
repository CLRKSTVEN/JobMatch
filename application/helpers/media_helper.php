<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('media_rel_from_abs')) {
   
    function media_rel_from_abs(string $absUrl): ?string {
        $absUrl = trim($absUrl);
        if ($absUrl === '') return null;
        $path = parse_url($absUrl, PHP_URL_PATH);
        if (!$path) return null;

        $CI = &get_instance();
        $basePath = trim(parse_url(base_url(), PHP_URL_PATH), '/');
        $rel = ltrim($path, '/');
        if ($basePath !== '' && strpos($rel, $basePath . '/') === 0) {
            $rel = substr($rel, strlen($basePath) + 1);
        }
        return $rel;
    }
}

if (!function_exists('media_viewer_url')) {
    function media_viewer_url(string $rel): string {
        return site_url('media/preview?f=' . rawurlencode($rel));
    }
}

if (!function_exists('media_wm_image_url')) {
    function media_wm_image_url(string $rel, bool $download = false): string {
        $url = site_url('media/wm_image?f=' . rawurlencode($rel));
        return $download ? $url . '&dl=1' : $url;
    }
}

if (!function_exists('media_wm_pdf_url')) {
    function media_wm_pdf_url(string $rel, bool $download = false): string {
        $url = site_url('media/wm_pdf?f=' . rawurlencode($rel));
        return $download ? $url . '&dl=1' : $url;
    }
}

<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('avatar_url')) {
  function avatar_url($candidatePathOrUrl) {
    $defaultRel = 'uploads/avatars/avatar.png';

    $p = trim((string)$candidatePathOrUrl);
    if ($p !== '' && preg_match('#^https?://#i', $p)) return $p;

    $p = str_replace('\\', '/', $p);

    $abs = FCPATH . ltrim($p, '/');
    if ($p !== '' && is_file($abs)) return base_url($p);

    $defAbs = FCPATH . $defaultRel;
    return base_url(is_file($defAbs) ? $defaultRel : $p);
  }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('validate_uploaded_file_signature')) {
  function validate_uploaded_file_signature(string $fullPath, string $ext): bool {
    $ext = strtolower(ltrim($ext, '.'));
    if ($ext === 'pdf') { $h = @fopen($fullPath, 'rb'); if (!$h) return false; $sig = @fread($h, 5); @fclose($h); return $sig === '%PDF-'; }
    if (in_array($ext, ['jpg','jpeg','png','webp','gif'], true)) {
      if (function_exists('exif_imagetype')) return @exif_imagetype($fullPath) !== false;
      return @getimagesize($fullPath) !== false;
    }
    return false;
  }
}

if (!function_exists('safe_image_reencode')) {
  function safe_image_reencode(string $fullPath, string $ext): bool {
    $ext = strtolower(ltrim($ext, '.'));
    switch ($ext) {
      case 'jpg': case 'jpeg':
        if (!function_exists('imagecreatefromjpeg')) return true;
        $src = @imagecreatefromjpeg($fullPath); if (!$src) return false;
        $ok = @imagejpeg($src, $fullPath, 92); imagedestroy($src); return (bool)$ok;
      case 'png':
        if (!function_exists('imagecreatefrompng')) return true;
        $src = @imagecreatefrompng($fullPath); if (!$src) return false;
        imagealphablending($src, true); imagesavealpha($src, true);
        $ok = @imagepng($src, $fullPath, 6); imagedestroy($src); return (bool)$ok;
      case 'webp':
        if (function_exists('imagecreatefromwebp') && function_exists('imagewebp')) {
          $src = @imagecreatefromwebp($fullPath); if (!$src) return false;
          $ok = @imagewebp($src, $fullPath, 80); imagedestroy($src); return (bool)$ok;
        }
        return true;
      case 'gif':
        if (!function_exists('imagecreatefromgif')) return true;
        $src = @imagecreatefromgif($fullPath); if (!$src) return false;
        $ok = @imagegif($src, $fullPath); imagedestroy($src); return (bool)$ok;
      default: return true;
    }
  }
}

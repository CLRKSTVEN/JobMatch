<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('normalize_image_orientation_gd')) {
  function normalize_image_orientation_gd(string $path): void {
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg','jpeg'], true)) return;
    if (!function_exists('exif_read_data')) return;

    $exif = @exif_read_data($path);
    if (!$exif || empty($exif['Orientation'])) return;

    $orientation = (int)$exif['Orientation'];
    if ($orientation === 1) return;

    $img = @imagecreatefromjpeg($path);
    if (!$img) return;

    $flip = function ($im, int $mode) {
      if (function_exists('imageflip')) { imageflip($im, $mode); return $im; }
      $w = imagesx($im); $h = imagesy($im);
      $dst = imagecreatetruecolor($w, $h);
      if ($mode === IMG_FLIP_HORIZONTAL) {
        for ($x=0; $x<$w; $x++) imagecopy($dst, $im, $w-$x-1, 0, $x, 0, 1, $h);
      } elseif ($mode === IMG_FLIP_VERTICAL) {
        for ($y=0; $y<$h; $y++) imagecopy($dst, $im, 0, $h-$y-1, 0, $y, $w, 1);
      } else { imagedestroy($dst); return $im; }
      imagedestroy($im); return $dst;
    };

    switch ($orientation) {
      case 2: $img = $flip($img, IMG_FLIP_HORIZONTAL); break;
      case 3: $img = imagerotate($img, 180, 0); break;
      case 4: $img = $flip($img, IMG_FLIP_VERTICAL); break;
      case 5: $img = $flip($img, IMG_FLIP_HORIZONTAL); $img = imagerotate($img, 270, 0); break;
      case 6: $img = imagerotate($img, -90, 0); break;
      case 7: $img = $flip($img, IMG_FLIP_HORIZONTAL); $img = imagerotate($img, -90, 0); break;
      case 8: $img = imagerotate($img, 90, 0); break;
    }

    imagejpeg($img, $path, 85);
    imagedestroy($img);
  }
}

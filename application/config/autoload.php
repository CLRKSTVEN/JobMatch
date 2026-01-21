<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Auto-load Packages
|--------------------------------------------------------------------------
*/
$autoload['packages'] = array();

/*
|--------------------------------------------------------------------------
| Auto-load Libraries
|--------------------------------------------------------------------------
| These are the classes located in system/libraries/ or your
| application/libraries/ directory. Keep this single, unified list.
*/
$autoload['libraries'] = array('database', 'session', 'form_validation', 'email');

/*
|--------------------------------------------------------------------------
| Auto-load Drivers
|--------------------------------------------------------------------------
*/
$autoload['drivers'] = array();

/*
|--------------------------------------------------------------------------
| Auto-load Helper Files
|--------------------------------------------------------------------------
*/
$autoload['helper'] = array('url','form','upload_safety','avatar','media','image_orientation','client');

/*
|--------------------------------------------------------------------------
| Auto-load Config files
|--------------------------------------------------------------------------
| Only list custom config files here (without the _config.php suffix).
*/
$autoload['config'] = array('recaptcha');

/*
|--------------------------------------------------------------------------
| Auto-load Language files
|--------------------------------------------------------------------------
| List language files here (without the _lang.php suffix).
*/
$autoload['language'] = array();

/*
|--------------------------------------------------------------------------
| Auto-load Models
|--------------------------------------------------------------------------
| You can map model aliases like: 'Some_model' => 'some'
*/
$autoload['model'] = array();

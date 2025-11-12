<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => $_ENV['DB_HOST'],
        'username' => $_ENV['DB_USERNAME'],
        'password' => $_ENV['DB_PASSWORD'],
        'db' => $_ENV['DB_DATABASE']
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800 // time in seconds
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )
);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Cookie;
use App\Config;
use App\DB;
use App\Session;
use App\User;

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
    // user asked to be remembered 
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('user_session', array('hash', '=', $hash));

    if($hashCheck->count()) {
        // hash matches log user in
        // make sure the db field is large enough for hash - 64 charecters //
        $user = new User($hashCheck->first()->user_id);
        $user->login();
    }
}

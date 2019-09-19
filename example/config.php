<?php

require_once __DIR__ . '/../vendor/autoload.php';

@session_start();

$static = new \Apple\Apple();
$static::$privateKey = '';
$static::$key_id = '';
$static::$team_id = '';
$static::$client_id = '';
$static::$redirect_uri = 'https://xxx/callback.php';
$static::$original_uri = 'https://xxx/index.php';


<?php

require_once __DIR__ . '/config.php';

use Apple\Apple;

try {
    if (!isset($_POST['code'])) die('Authorization server returned an invalid code parameter');
    if (!isset($_POST['state']) || !isset($_SESSION['state']) || $_SESSION['state'] != $_POST['state']) die('Authorization server returned an invalid state parameter');
    if (isset($_REQUEST['error'])) die('Authorization server returned an error: ' . htmlspecialchars($_REQUEST['error']));

    $response = Apple::get_web_sign_in_callback($_POST['code']);

} catch (\Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: ' . Apple::$original_uri);
}

if ($response) {
    echo '<h3>Access Token Response</h3>';
    echo '<pre>';
    print_r($response);
    echo '</pre>';

    $claims = explode('.', $response['id_token'])[1];
    $claims = json_decode(base64_decode($claims), true);

    echo '<pre>';
    print_r($claims);
    echo '</pre>';
} else {
    echo '<h3>Invalid callback data</h3>';
}


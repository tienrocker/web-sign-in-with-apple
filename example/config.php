<?php

require_once __DIR__ . '/../vendor/autoload.php';

use \Apple\Apple;

@session_start();

Apple::setup('', '', '', '', 'https://xxx/callback.php', 'https://xxx/index.php');

<?php
use Ads\Application;
require_once dirname(__DIR__) . '/vendor/autoload.php';

define('ROOT', dirname(__DIR__));
const CONFIG = ROOT . '/config';

return Application::i();
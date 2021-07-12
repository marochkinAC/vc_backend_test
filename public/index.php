<?php
use Symfony\Component\HttpFoundation\Request;

/**
 * @var \Ads\Application $application
 */
$application = require '../config/bootstrap.php';
$request = Request::createFromGlobals();
$response = $application->run($request);
$response->send();

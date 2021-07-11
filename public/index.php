<?php
/***
 * обработка ошибки когда не задан метод контроллера
 * обработка ошибки когда контроллер вернул не Response
 *
 */

use Symfony\Component\HttpFoundation\Request;

/**
 * @var \Ads\Application $application
 */
$application = require '../config/bootstrap.php';
$request = Request::createFromGlobals();
$response = $application->run($request);
$response->send();

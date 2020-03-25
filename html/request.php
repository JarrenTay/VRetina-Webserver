<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require 'models/requestModel.php';
    require 'controllers/requestController.php';
    require 'views/requestView.php';

    $model = new RequestModel();
    $controller = new RequestController($model);
    $view = new RequestView($controller, $model);

    $controller->processRequest();
    echo $view->output();

?>

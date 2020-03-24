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
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        if (isset($_POST['clientType']) && !empty($_POST['clientType'])) {
            $controller->processRequest($_POST['id'], $_POST['clientType']);
        }
    }

    echo $view->output();
?>

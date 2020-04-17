<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require 'models/uploadModel.php';
    require 'controllers/uploadController.php';
    require 'views/uploadView.php';

    $model = new UploadModel();
    $controller = new UploadController($model);
    $view = new UploadView($controller, $model);

    $controller->processUpload();
    echo $view->output();

?>

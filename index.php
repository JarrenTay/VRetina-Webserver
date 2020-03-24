<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require 'models/indexModel.php';
    require 'controllers/indexController.php';
    require 'views/indexView.php';

    $model = new IndexModel();
    $controller = new IndexController($model);
    $view = new IndexView($controller, $model);
    
    echo $view->output();
?>

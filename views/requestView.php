<?php
    class IndexView
    {
        private $model;
        private $controller;
    
        public function __construct($controller,$model) {
            $this->controller = $controller;
            $this->model = $model;
        }
    
        public function output() {
            return '<p><a href="mvc.php?action=clicked">' . $this->model->string . '</a></p>';
        }
    }
?>

<?php
    class IndexController
    {
        private $model;
    
        public function __construct($model){
            $this->model = $model;
        }
    
        public function clicked() {
            $this->model->string = 'This text changed!';
        }
    }
?>

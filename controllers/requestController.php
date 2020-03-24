<?php
    class IndexController
    {
        private $model;
    
        public function __construct($model){
            $this->model = $model;
        }
    
        public function clicked() {
            $this->model->string = 'Updated Data, thanks to MVC and PHP!';
        }
    }
?>

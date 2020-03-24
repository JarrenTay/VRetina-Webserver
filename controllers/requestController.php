<?php
    class RequestController
    {
        private $model;
    
        public function __construct($model){
            $this->model = $model;
        }
    
        public function processRequest() {
            $this->model->string = 'Updated Data, thanks to MVC and PHP!';
        }
    }
?>

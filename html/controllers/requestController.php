<?php
    class RequestController
    {
        private $model;
    
        public function __construct($model){
            $this->model = $model;
        }
    
        public function processRequest($id, $clientType) {
            if ($clientType == 'browser') {

            } else if ($clientType == 'app') {

            }
        }
    }
?>

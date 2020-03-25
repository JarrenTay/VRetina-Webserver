<?php
    class RequestController
    {
        private $model;
    
        public function __construct($model){
            $this->model = $model;
        }
    
        public function processRequest($id, $clientType) {
            $this->model->queryDatabase($id);
            $this->model->clientType = $clientType;
        }
    }
?>

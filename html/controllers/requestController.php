<?php
    class RequestController
    {
        private $model;
    
        public function __construct($model){
            $this->model = $model;
        }
    
        public function processRequest() {
            if (isset($_POST['id'])) {
                $this->model->queryDatabase($_POST['id']);
            }

            if (isset($_POST['clientType']) && !empty($_POST['clientType'])) {
                $this->model->clientType = $_POST['clientType'];
            } else {
                $this->model->clientType = NULL;
            }
        }
    }
?>

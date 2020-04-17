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
                $this->model->requestType = 'lookup';
            } else if (isset($_POST['requestType'])) {
                $this->model->idList = $this->model->getCount();
                $this->model->requestType = 'numImages';
            } else {
                $this->model->requestType = 'bad';
            }

            if (isset($_POST['clientType']) && !empty($_POST['clientType'])) {
                $this->model->clientType = $_POST['clientType'];
            } else {
                $this->model->clientType = NULL;
            }
        }
    }
?>

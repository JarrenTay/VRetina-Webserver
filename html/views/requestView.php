<?php
    class RequestView
    {
        private $model;
        private $controller;
    
        public function __construct($controller,$model) {
            $this->controller = $controller;
            $this->model = $model;
        }
    
        public function output() {
            if ($this->model->id == -1) {
                return 'Bad Request (id not specified)';
            }

            if ($this->model->clientType == 'browser') {
                $requestTemplateName = '/var/www/templates/requestTemplate.html';
                $requestTemplateStream = fopen($requestTemplateName, 'r') or die('Unable to open request template');
                $requestTemplate = fread($requestTemplateStream, filesize($requestTemplateName));
                
                $output = str_replace('{{title}}', 'VRetina - ' . $this->model->name, $requestTemplate);
                $output = str_replace('{{head}}', '', $output);
    
                $output = str_replace('{{id}}', $this->model->id, $output);
                $output = str_replace('{{name}}', $this->model->name, $output);
                $output = str_replace('{{xSize}}', $this->model->xSize, $output);
                $output = str_replace('{{ySize}}', $this->model->ySize, $output);
                $output = str_replace('{{filetype}}', $this->model->filetype, $output);
                $output = str_replace('{{official}}', $this->model->official, $output);
                $output = str_replace('{{uploaded}}', $this->model->uploaded, $output);
    
                $output = str_replace('{{imageData}}', $this->model->image, $output);
                $output = str_replace('{{altText}}', $this->model->name, $output);
                return $output;
            } else if ($this->model->clientType == 'app') {
                //return json_encode($this->model);
                $requestTemplateName = '/var/www/templates/requestJsonTemplate.json';
                $requestTemplateStream = fopen($requestTemplateName, 'r') or die('Unable to open request template');
                $requestTemplate = fread($requestTemplateStream, filesize($requestTemplateName));

                $output = str_replace('{{id}}', $this->model->id, $requestTemplate);
                $output = str_replace('{{name}}', $this->model->name, $output);
                $output = str_replace('{{xSize}}', $this->model->xSize, $output);
                $output = str_replace('{{ySize}}', $this->model->ySize, $output);
                $output = str_replace('{{filetype}}', $this->model->filetype, $output);
                $output = str_replace('{{official}}', $this->model->official, $output);
                $output = str_replace('{{uploaded}}', $this->model->uploaded, $output);    
                $output = str_replace('{{imageData}}', $this->model->image, $output);
                return $output;
            } else {
                return 'Bad Request (clientType not specified)';
            }
        }
    }
?>

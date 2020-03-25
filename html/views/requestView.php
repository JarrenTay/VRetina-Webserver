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
            #header('Content-type: image/' . $this->model->filetype);
            #return $this->model->imageLocation;
            $requestTemplateName = '/var/www/html/views/requestTemplate.html';
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
        }
    }
?>

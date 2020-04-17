<?php

    include_once '/var/www/html/classes/labelClasses.php';

    class RequestView
    {
        private $model;
        private $controller;
    
        public function __construct($controller,$model) {
            $this->controller = $controller;
            $this->model = $model;
        }

        public function labelToJson($labels) {
            $labelTemplateName = '/var/www/templates/labelTemplate.json';
            $labelTemplateStream = fopen($labelTemplateName, 'r') or die('Unable to open label template');
            $labelTemplate = fread($labelTemplateStream, filesize($labelTemplateName));

            $meshTemplateName = '/var/www/templates/meshTemplate.json';
            $meshTemplateStream = fopen($meshTemplateName, 'r') or die('Unable to open mesh template');
            $meshTemplate = fread($meshTemplateStream, filesize($meshTemplateName));

            $vertexTemplateName = '/var/www/templates/vertexTemplate.json';
            $vertexTemplateStream = fopen($vertexTemplateName, 'r') or die('Unable to open vertex template');
            $vertexTemplate = fread($vertexTemplateStream, filesize($vertexTemplateName));

            $triangleTemplateName = '/var/www/templates/triangleTemplate.json';
            $triangleTemplateStream = fopen($triangleTemplateName, 'r') or die('Unable to open triangle template');
            $triangleTemplate = fread($triangleTemplateStream, filesize($triangleTemplateName));

            $labelOut = "";
            foreach ($labels as $label) {
                $labelTemplateCopy = $labelTemplate;
                $labelTemplateCopy = str_replace('{{labelId}}', $label->labelId, $labelTemplateCopy);
                $labelTemplateCopy = str_replace('{{labelName}}', $label->labelName, $labelTemplateCopy);
                $meshOut = "";
                foreach ($label->meshes as $mesh) {
                    $meshTemplateCopy = $meshTemplate;
                    $meshTemplateCopy = str_replace('{{meshId}}', $mesh->meshId, $meshTemplateCopy);
                    $vertexOut = "";
                    foreach ($mesh->vertices as $vertex) {
                        $vertexTemplateCopy = $vertexTemplate;
                        $vertexTemplateCopy = str_replace('{{vertexNum}}', $vertex->vertexNum, $vertexTemplateCopy);
                        $vertexTemplateCopy = str_replace('{{x}}', $vertex->x, $vertexTemplateCopy);
                        $vertexTemplateCopy = str_replace('{{y}}', $vertex->y, $vertexTemplateCopy);
                        $vertexTemplateCopy = str_replace('{{z}}', $vertex->z, $vertexTemplateCopy);
                        $vertexOut = $vertexOut . $vertexTemplateCopy . ",";
                    }
                    $vertexOut = substr($vertexOut, 0, -1);
                    $meshTemplateCopy = str_replace('{{vertices}}', $vertexOut, $meshTemplateCopy);

                    $triangleOut = "";
                    foreach ($mesh->triangles as $triangle) {
                        $triangleTemplateCopy = $triangleTemplate;
                        $triangleTemplateCopy = str_replace('{{triangleNum}}', $triangle->triangleNum, $triangleTemplateCopy);
                        $triangleTemplateCopy = str_replace('{{a}}', $triangle->a, $triangleTemplateCopy);
                        $triangleTemplateCopy = str_replace('{{b}}', $triangle->b, $triangleTemplateCopy);
                        $triangleTemplateCopy = str_replace('{{c}}', $triangle->c, $triangleTemplateCopy);
                        $triangleOut = $triangleOut . $triangleTemplateCopy . ",";
                    }
                    $triangleOut = substr($triangleOut, 0, -1);
                    $meshTemplateCopy = str_replace('{{triangles}}', $triangleOut, $meshTemplateCopy);
                    $meshOut = $meshOut . $meshTemplateCopy . ",";
                }
                $meshOut = substr($meshOut, 0, -1);
                $labelTemplateCopy = str_replace('{{meshes}}', $meshOut, $labelTemplateCopy);
                $labelOut = $labelOut . $labelTemplateCopy . ",";
            }
            $labelOut = substr($labelOut, 0, -1);
            return $labelOut;
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
                $output = str_replace('{{labels}}', $this->labelToJson($this->model->labels), $output);
                return $output;
            } else {
                return 'Bad Request (clientType not specified)';
            }
        }
    }
?>

<?php

    class IndexView
    {
        private $model;
        private $controller;
    
        public function __construct($controller,$model) {
            $this->controller = $controller;
            $this->model = $model;
        }
    
        public function output() {
            $indexTemplateName = '/var/www/html/views/indexTemplate.html';
            $indexTemplateStream = fopen($indexTemplateName, 'r') or die('Unable to open index template');
            $indexTemplate = fread($indexTemplateStream, filesize($indexTemplateName));

            $indexRowTemplateName = '/var/www/html/views/indexRowTemplate.html';
            $indexRowTemplateStream = fopen($indexRowTemplateName, 'r') or die('Unable to open index row template');
            $indexRowTemplate = fread($indexRowTemplateStream, filesize($indexRowTemplateName));

            $allRows = '';
            foreach ($this->model->retina_models as $retina_model) {
                $rowCopy = $indexRowTemplate;
                $rowCopy = str_replace('{{name}}', $retina_model['name'], $rowCopy);
                $rowCopy = str_replace('{{id}}', $retina_model['id'], $rowCopy);
                $rowCopy = str_replace('{{timestamp}}', $retina_model['uploaded'], $rowCopy);
                $allRows = $allRows . $rowCopy;
            }
            $output = str_replace('{{title}}', 'VRetina', $indexTemplate);
            $output = str_replace('{{head}}', '', $output);
            $output = str_replace('{{retina_model_rows}}', $allRows, $output);

            return $output;
        }
    }
?>

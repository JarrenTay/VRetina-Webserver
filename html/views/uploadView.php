<?php
    class UploadView
    {
        private $model;
        private $controller;
    
        public function __construct($controller,$model) {
            $this->controller = $controller;
            $this->model = $model;
        }
    
        public function output() {
            $uploadTemplateName = '/var/www/templates/uploadTemplate.html';
            $uploadTemplateStream = fopen($uploadTemplateName, 'r') or die('Unable to open upload template');
            $uploadTemplate = fread($uploadTemplateStream, filesize($uploadTemplateName));

            $uploadMessageTemplateName = '/var/www/templates/uploadMessageTemplate.html';
            $uploadMessageTemplateStream = fopen($uploadMessageTemplateName, 'r') or die('Unable to open upload message template');
            $uploadMessageTemplate = fread($uploadMessageTemplateStream, filesize($uploadMessageTemplateName));
                
            $output = str_replace('{{title}}', 'Upload Retina Models', $uploadTemplate);

            if ($this->model->submission === TRUE) {
                $messageOutput = "";
                foreach($this->model->errorMessageList as $message) {
                    $messageTemplateCopy = $uploadMessageTemplate;
                    $messageOutput = $messageOutput . str_replace('{{message}}', $message, $messageTemplateCopy);
                }
                $output = str_replace('{{messages}}', $messageOutput, $output);
            } else {
                $output = str_replace('{{messages}}', "", $output);
            }

            return $output;
        }
    }
?>

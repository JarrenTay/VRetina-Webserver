<?php

    include_once '/var/www/html/classes/imageBundle.php';

    class UploadController
    {
        private $model;
    
        public function __construct($model){
            $this->model = $model;
        }

        # if having upload problems, check file size. and php.ini upload_max_filesize and post_max_size.    
        public function processUpload() {
            if (isset($_POST['submit'])) {
                $this->model->submission = TRUE;
                if (isset($_FILES['retinaImage']["tmp_name"]) && isset($_POST['retinaImageName'])) {
                    $this->model->retinaImageBundle = new ImageBundle($_FILES['retinaImage'], $_POST['retinaImageName'], FALSE, -1);

                    if (isset($_FILES['labelImage']['tmp_name']) && $_POST['labelImageName']) {
                        $numLabelImages = count($_FILES['labelImage']['tmp_name']);
                        $numLabelNames = count($_POST['labelImageName']);
                        if ($numLabelImages != $numLabelNames) {
                            die("num label images and num label image names not equal" . strval($numLabelImages) . " " . strval($numLabelNames));
                        }
                    } else {
                        $numLabelImages = 0;
                        $numLabelNames = 0;
                    }

                    for ($i = 0; $i < $numLabelImages; $i++) {
                        array_push($this->model->labelBundles, new ImageBundle($_FILES['labelImage'], $_POST['labelImageName'], TRUE, $i));
                    }

                    $allOk = TRUE;
                    if (!$this->model->retinaImageBundle->isOk) {
                        $allOk = FALSE;
                        array_push($this->model->errorMessageList, $this->model->retinaImageBundle->errorMessage);
                        #echo $this->model->retinaImageBundle->errorMessage;
                    } else {
                        #echo gettype($this->model->retinaImageBundle->isOk);
                    }

                    foreach ($this->model->labelBundles as $labelBundle) {
                        if (!$labelBundle->isOk) {
                            $allOk = FALSE;
                            array_push($this->model->errorMessageList, $labelBundle->errorMessage);
                        }
                    }

                    if ($allOk) {
                        $this->model->id = $this->model->getNewModelId();
                        $filePath = '/var/www/images/' . strval($this->model->id) . '.' . $this->model->getFileType($this->model->retinaImageBundle->imageType);
                        if (move_uploaded_file($this->model->retinaImageBundle->imageData, $filePath)) {
                            # File saved successfully.
                            $this->model->updateRetinaModelEntry($this->model->id, $this->model->retinaImageBundle);
                            # Sent to database.
                            $labelTempFilePath = '/var/www/tmp/';
                            foreach ($this->model->labelBundles as $labelBundle) {
                                $labelId = $this->model->getNewLabelId();
                                $this->model->labelToDatabase($this->model->id, $labelId, $labelBundle->userImageName);
                                $currLabelPath = $labelTempFilePath . $labelId . '.' . $this->model->getFileType($labelBundle->imageType);
                                if (move_uploaded_file($labelBundle->imageData, $currLabelPath)) {
                                    $contourCommand = "/var/www/getContours/GetContours $currLabelPath $labelId $labelTempFilePath";
                                    $shellOutput = shell_exec($contourCommand);
                                    if (strpos($shellOutput, "ERROR") === FALSE) {
                                        for ($meshNum = 0; $meshNum < strval(trim($shellOutput)); $meshNum++) {
                                            $nodesFilePath = $labelTempFilePath . $labelId . "_" . $meshNum . ".node";
                                            $triangleCommand = "/var/www/triangle/triangle -q0N " . $nodesFilePath;
                                            shell_exec($triangleCommand);
                                            $verticesFilePath =  $labelTempFilePath . $labelId . "_" . $meshNum . ".transformed";
                                            $trianglesFilePath = $labelTempFilePath . $labelId . "_" . $meshNum . ".1.ele";
                                            $this->model->meshToDatabase($labelId, $verticesFilePath, $trianglesFilePath);
                                            unlink($nodesFilePath);
                                            unlink($verticesFilePath);
                                            unlink($trianglesFilePath);
                                        }
                                        unlink($currLabelPath);
                                    }
                                } else {
                                    die('Could not move temp file');
                                }
                            }
                            $this->model->success = TRUE;
                            array_push($this->model->errorMessageList, "Files uploaded succesfully!");
                        } else {
                            die("Could not save file");
                        }
                        # Save image to file system
                        # Add image entry into database
                        # Use getContours to create contours.
                        # Use output of getContours on triangle.
                        # Insert nodes and triangles into database.
                        # Clean up artifacts
                    } else {
                        array_push($this->model->errorMessageList, "Files not uploaded. Fix the error messages to upload files.");
                    }
                }
            }

            if (isset($_POST['clientType']) && !empty($_POST['clientType'])) {
                $this->model->clientType = $_POST['clientType'];
            } else {
                $this->model->clientType = NULL;
            }
        }
    }
?>

<?php

    const MAX_FILE_SIZE = 15000000;

    class ImageBundle {

        public $UNITY_PHP_SUPPORTED_IMAGE_TYPES = [1, 2, 3, 5, 6, 7, 8, 14];
        public $OPENCV_PHP_SUPPORTED_IMAGE_TYPES = [2, 3, 6, 7, 8, 10, 18];

        public $imageData;
        public $userImageName;
        public $imageType;
        public $isOk;
        public $errorMessage;
        public $height;
        public $width;

        public function __construct($image, $imageName, $isLabel, $labelNum) {
            if ($isLabel) {
                $fileSize = $image["size"][$labelNum];
            } else {
                $fileSize = $image["size"];
            }
            if ($fileSize < MAX_FILE_SIZE) {
                if ($isLabel) {
                    $this->imageData = $image["tmp_name"][$labelNum];
                    $this->userImageName = $imageName[$labelNum];
                } else {
                    $this->imageData = $image["tmp_name"];
                    $this->userImageName = $imageName;
                }
                $detectedType = exif_imagetype($this->imageData);
                if ($detectedType !== FALSE) {
                    if ((in_array($detectedType, $this->UNITY_PHP_SUPPORTED_IMAGE_TYPES) && !$isLabel) || (in_array($detectedType, $this->OPENCV_PHP_SUPPORTED_IMAGE_TYPES) && $isLabel)) {
                        $this->isOk = TRUE;

                        $this->imageType = $detectedType;
                        $size = getimagesize($this->imageData);
                        $this->width = $size[0];
                        $this->height = $size[1];
                    } else {
                        $this->isOk = FALSE;
                        $this->errorMessage = "Image file type of $imageName is not supported.";
                        if ($isLabel) {
                            $this->errorMessage = $this->errorMessage . " Verify that your image type is supported by OpenCV and PHP.";
                        } else {
                            $this->errorMessage = $this->errorMessage . " Verify that your image type is supported by Unity and PHP.";
                        }
                    }
                } else {
                    $this->isOk = FALSE;
                    $this->errorMessage = "Please verify that the image of $imageName is not corrupted.";
                }
            } else {
                $this->isOk = FALSE;
                $this->errorMessage = "The filesize of $imageName is too large. The maximum file size is " . strval(MAX_FILE_SIZE) . " bytes. Detected" . strval($fileSize) . ".";
            }
        }
    }
?>


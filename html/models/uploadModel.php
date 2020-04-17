<?php

    include_once '/var/www/html/classes/imageBundle.php';

    class UploadModel
    {
        public function __construct() {
            $this->retinaImageBundle = NULL;
            $this->labelBundles = array();
            $this->errorMessageList = array();
            $this->id = NULL;
            $this->submission = FALSE;
            $this->success = FALSE;
        }

        public function getNewModelId() {
            $servername = 'localhost';
            $username = 'ec2-user';
            $password = 'ukyCS499!';
            $dbname = 'vretina';

            $conn = mysqli_connect($servername, $username, $password, $dbname);
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = 'SELECT MAX(id) AS MaxId FROM retina_models;';
            $result = $conn->query($sql);

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                if ($row['MaxId'] !== NULL) {
                    $id = $row['MaxId'] + 1;
                } else {
                    $id = 0;
                }
            }

            # Create placeholder for id, so that another getNewId doesnt try to use it.
            $sql = 'INSERT INTO retina_models (id) VALUES (' . $id . ');';
            $result = $conn->query($sql);

            $conn->close();
            return $id;
        }

        public function getNewLabelId() {
            $servername = 'localhost';
            $username = 'ec2-user';
            $password = 'ukyCS499!';
            $dbname = 'vretina';

            $conn = mysqli_connect($servername, $username, $password, $dbname);
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = 'SELECT MAX(label_id) AS MaxId FROM labels;';
            $result = $conn->query($sql);

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                if ($row['MaxId'] !== NULL) {
                    $id = $row['MaxId'] + 1;
                } else {
                    $id = 0;
                }
            }

            # Create placeholder for id, so that another getNewLabelId doesnt try to use it.
            $sql = 'INSERT INTO labels (label_id) VALUES (' . $id . ');';
            $result = $conn->query($sql);

            $conn->close();
            return $id;
        }


        public function getFileType($fileEnum) {
            $servername = 'localhost';
            $username = 'ec2-user';
            $password = 'ukyCS499!';
            $dbname = 'vretina';

            $conn = mysqli_connect($servername, $username, $password, $dbname);
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = 'SELECT filetype FROM filetypes WHERE id = ' . $fileEnum . ';';
            $result = $conn->query($sql);

            $row = $result->fetch_assoc();
            $filetype = $row['filetype'];

            $conn->close();
            return $filetype;
        }

        public function updateRetinaModelEntry($id, $imageBundle) {
            $servername = 'localhost';
            $username = 'ec2-user';
            $password = 'ukyCS499!';
            $dbname = 'vretina';

            $conn = mysqli_connect($servername, $username, $password, $dbname);
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = $conn->prepare('UPDATE retina_models SET name = ?, x_size = ?, y_size = ?, filetype = ?, official = ?, uploaded = NOW() WHERE id = ' . $id . ';');
            $sql->bind_param("siiii", $modelName, $width, $height, $filetype, $official);
            $modelName = $imageBundle->userImageName;
            $width = $imageBundle->width;
            $height = $imageBundle->height;
            $filetype = $imageBundle->imageType;
            $official = 1;
            $result = $sql->execute();

            $sql->close();
            $conn->close();
        }

        public function labelToDatabase($retinaModelId, $labelId, $labelName) {
            $servername = 'localhost';
            $username = 'ec2-user';
            $password = 'ukyCS499!';
            $dbname = 'vretina';

            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = $conn->prepare('UPDATE labels SET retina_image_id = ?, label_name = ? WHERE label_id = ' . $labelId . ';');
            $sql->bind_param("is", $retina_image_id, $label_name);
            $retina_image_id = $retinaModelId;
            $label_name = $labelName;
            $result = $sql->execute();

            $sql->close();
            $conn->close();
        }

        public function meshToDatabase($labelId, $verticesFilePath, $trianglesFilePath) {
            $servername = 'localhost';
            $username = 'ec2-user';
            $password = 'ukyCS499!';
            $dbname = 'vretina';

            $conn = mysqli_connect($servername, $username, $password, $dbname);
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = 'SELECT MAX(mesh_id) AS MaxId FROM meshes;';
            $result = $conn->query($sql);

            $mesh_id = -1;

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                if ($row['MaxId'] !== NULL) {
                    $mesh_id = $row['MaxId'] + 1;
                } else {
                    $mesh_id = 0;
                }
            }

            $sql = 'INSERT INTO meshes VALUES (' . strval($labelId) . ', ' . strval($mesh_id) . ');';
            $result = $conn->query($sql);

            $sql = '';
            $verticesFileStream = fopen($verticesFilePath, 'r');
            $isFirstLine = TRUE;
            $mesh_id_string = strval($mesh_id);
            while (!feof($verticesFileStream)) {
                $line = fgets($verticesFileStream);
                if ($isFirstLine) {
                    $isFirstLine = FALSE;
                } else {
                    $verticesArray = preg_split('/\s+/', $line);
                    if (count($verticesArray) >= 4) {
                        $sql = $sql . 'INSERT INTO vertices VALUES (' . $mesh_id_string . ', ' . $verticesArray[0] . ', ' . $verticesArray[1] . ', ' . $verticesArray[2] . ', ' . $verticesArray[3] . ');';
                    }
                }
            }
            fclose($verticesFileStream);


            $trianglesFileStream = fopen($trianglesFilePath, 'r');
            $isFirstLine = TRUE;
            while (!feof($trianglesFileStream)) {
                $line = fgets($trianglesFileStream);
                $line = trim($line);
                if ($isFirstLine) {
                    $isFirstLine = FALSE;
                } else {
                    if (strlen($line) > 0) {
                        if ($line[0] != '#') {
                            $trianglesArray = preg_split('/\s+/', $line);
                            if (count($trianglesArray) == 4) {
                                $sql = $sql . 'INSERT INTO triangles VALUES (' . $mesh_id_string . ', ' . $trianglesArray[0] . ', ' . $trianglesArray[1] . ', ' . $trianglesArray[2] . ', ' . $trianglesArray[3] . ');';
                            }
                        }
                    }
                }
            }
            fclose($trianglesFileStream);
            $result = $conn->multi_query($sql);
            $conn->close();
        }

        public function queryDatabase($id) {
            $servername = 'localhost';
            $username = 'ec2-user';
            $password = 'ukyCS499!';
            $dbname = 'vretina';
            $this->imageLocation = '3';
            $conn = mysqli_connect($servername, $username, $password, $dbname);
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = 'SELECT * FROM retina_models where id = ' . strval($id) . ';';
            $result = $conn->query($sql);

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $this->id = $id;
                $this->name = $row['name'];
                $this->xSize = $row['x_size'];
                $this->ySize = $row['y_size'];
                $filetypeEnum = $row['filetype'];
                $this->official = $row['official'];
                $this->uploaded = $row['uploaded'];
            }

            $sql = 'SELECT * FROM filetypes where id = ' . strval($filetypeEnum) . ';';
            $result = $conn->query($sql);

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $this->filetype = $row['filetype'];
            }
            $this->imageLocation = '/var/www/images/' . strval($id) . '.' . $this->filetype;

            $this->image = base64_encode(file_get_contents('/var/www/images/' . strval($id) . '.' . $this->filetype));

            $conn->close();
        }

    }
?>

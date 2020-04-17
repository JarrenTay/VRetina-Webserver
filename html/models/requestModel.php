<?php

    include_once '/var/www/html/classes/labelClasses.php';

    class RequestModel
    {
        public function __construct(){
            $this->id = -1;
            $this->name = '';
            $this->xSize = -1;
            $this->ySize = -1;
            $this->filetype = '';
            $this->official = false;
            $this->uploaded = NULL;#date("Y-m-d H:i:s");
            $this->image = NULL;
            $this->imageLocation = '2';
            $this->labels = array();
        }

        public function getCount() {
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

            $sql = 'SELECT id FROM retina_models;';
            $result = $conn->query($sql);

            $idsString = "";

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $idsString = $idsString . $row['id'] . ',';
                }
                $idsString = substr($idsString, 0, -1);
            }               
            $conn->close();
            return $idsString;
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


            $sql = 'SELECT * FROM labels WHERE retina_image_id = ' . strval($id) . ';';
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $newLabel = new Label($row['label_id'], $row['label_name']);
                    array_push($this->labels, $newLabel);
                }
            }

            foreach ($this->labels as $label) {
                $sql = 'SELECT * FROM meshes WHERE label_id = ' . strval($label->labelId) . ';';
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $newMesh = new Mesh($row['mesh_id']);
                        array_push($label->meshes, $newMesh);
                    }
                }

                foreach ($label->meshes as $mesh) {
                    $sql = 'SELECT * FROM vertices WHERE mesh_id = ' . strval($mesh->meshId) . ';';
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $newVertex = new Vertex($row['vertex_num'], $row['x'], $row['y'], $row['z']);
                            array_push($mesh->vertices, $newVertex);
                        }
                    }

                    $sql = 'SELECT * FROM triangles WHERE mesh_id = ' . strval($mesh->meshId) . ';';
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $newTriangle = new Triangle($row['triangle_num'], $row['a'], $row['b'], $row['c']);
                            array_push($mesh->triangles, $newTriangle);
                        }
                    }
                }
            }

            $conn->close();
        }

    }
?>

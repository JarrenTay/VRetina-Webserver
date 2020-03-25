<?php

    class RequestModel
    {
        public $string;

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

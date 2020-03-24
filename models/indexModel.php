<?php

    class IndexModel
    {
        public $string;

        public function __construct() {
            $servername = 'localhost';
            $username = 'ec2-user';
            $password = 'ukyCS499!';
            $dbname = 'vretina';

            $conn = mysqli_connect($servername, $username, $password, $dbname);
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $sql = 'SELECT * FROM retina_models;';
            $result = $conn->query($sql);
            $this->retina_models = array();
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $this->retina_models[] = $row;
                }
            }

            $conn->close();
        }

    }
?>

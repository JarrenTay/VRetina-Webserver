<?php
    class Vertex {
        public $vertexNum = -1;
        public $x = 0.0;
        public $y = 0.0;
        public $z = 0.0;

        public function __construct($num, $x, $y, $z) {
            $this->vertexNum = $num;
            $this->x = $x;
            $this->y = $y;
            $this->z = $z;
        }
    }

    class Triangle {
        public $triangleNum = -1;
        public $a = -1;
        public $b = -1;
        public $c = -1;

        public function __construct($num, $a, $b, $c) {
            $this->triangleNum = $num;
            $this->a = $a;
            $this->b = $b;
            $this->c = $c;
        }
    }

    class Mesh {
        public $meshId = -1;
        public $vertices = array();
        public $triangles = array();

        public function __construct($id) {
            $this->meshId = $id;
        }
    }

    class Label {
        public $labelId = -1;
        public $labelName = "";
        public $meshes = array();

        public function __construct($id, $name) {
            $this->labelId = $id;
            $this->labelName = $name;
        }
    }
?>

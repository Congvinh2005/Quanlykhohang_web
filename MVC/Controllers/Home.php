<?php
    class Home{
        public function Get_data(){
            $data = ['page' => 'home'];
            include_once __DIR__.'/../Views/Master.php';
        }
    }
?>
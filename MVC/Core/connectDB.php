<?php
    include_once __DIR__.'/../../Public/Classes/TimezoneHelper.php';

    class connectDB{
        public $con;
            function __construct()
            {
                $this->con=mysqli_connect('localhost','root','','Web');
                mysqli_query($this->con,"SET NAMES 'utf8'");

                // Set timezone for the database connection
                mysqli_query($this->con, "SET time_zone = '+07:00'"); // Vietnam timezone (UTC+7)
            }
    }
?>
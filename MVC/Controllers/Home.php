<?php
    class Home extends controller{
        private $home;

        function __construct()
        {
            $this->home = $this->model("Home_m");
        }

        public function Get_data(){
            $recent_activities = $this->home->getRecentActivities(8);
            $data = [
                'page' => 'home',
                'recent_activities' => $recent_activities
            ];
            include_once __DIR__.'/../Views/Master.php';
        }
    }
?>
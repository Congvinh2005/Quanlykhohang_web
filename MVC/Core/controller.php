<?php
    class controller{
        public function model($model){
        include_once __DIR__.'/../Models/'.$model.'.php';
        return new $model;
        }
        public function view($view,$data=[]){
            include_once __DIR__.'/../Views/'.$view.'.php';
        }

        /**
         * Format datetime for display in the correct timezone
         * @param string $datetime The datetime string from database
         * @param string $format The format to display (optional)
         * @return string Formatted datetime
         */
        public function formatDate($datetime, $format = 'd/m/Y H:i:s') {
            if (empty($datetime)) {
                return $datetime;
            }

            // Use the timezone helper to format the date
            return TimezoneHelper::formatForDisplay($datetime, $format);
        }

        /**
         * Generate a URL relative to the application base
         * @param string $path The path to append to the base URL
         * @return string The complete URL
         */
        public function url($path = '') {
            return UrlHelper::url($path);
        }
    }
?>
<?php
    class Thongke extends controller{
        private $dh;
        private $ctdh;

        function __construct()
        {
            $this->dh = $this->model("Donhang_m");
            $this->ctdh = $this->model("Chitietdonhang_m");
        }

        function Get_data(){
            // Hàm mặc định - hiển thị thống kê
            $this->thongke();
        }

        function index(){
            $this->thongke();
        }

        function thongke(){
            // Default to current month if no dates provided
            $tu_ngay = isset($_GET['tu_ngay']) ? $_GET['tu_ngay'] : date('Y-m-01');
            $den_ngay = isset($_GET['den_ngay']) ? $_GET['den_ngay'] : date('Y-m-d');

            // Get statistics data using the model's method
            $stats = $this->dh->getStatisticsByDate($tu_ngay, $den_ngay);

            $this->view('Master', [
                'page' => 'Thongke_v',
                'stats' => $stats,
                'tu_ngay' => $tu_ngay,
                'den_ngay' => $den_ngay
            ]);
        }

        // Method to get chart data for AJAX requests
        function getChartData(){
            $tu_ngay = isset($_GET['tu_ngay']) ? $_GET['tu_ngay'] : date('Y-m-01');
            $den_ngay = isset($_GET['den_ngay']) ? $_GET['den_ngay'] : date('Y-m-d');

            // Get daily revenue data for line chart
            $daily_revenue = $this->dh->getDailyRevenue($tu_ngay, $den_ngay);

            // Get revenue by category for pie chart
            $revenue_by_category = $this->dh->getRevenueByCategory($tu_ngay, $den_ngay);

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'daily_revenue' => $daily_revenue,
                'revenue_by_category' => $revenue_by_category
            ]);
            exit;
        }
    }
?>
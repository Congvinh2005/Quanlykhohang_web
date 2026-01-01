<?php
require_once __DIR__ . '/../tcpdf/tcpdf.php';

class InvoicePDF extends TCPDF
{
    private $order_data;
    private $order_details;
    private $order_total;

    public function __construct($order_data, $order_details, $order_total)
    {
        parent::__construct('P', 'mm', 'A4', true, 'UTF-8', false);
        $this->order_data = $order_data;
        $this->order_details = $order_details;
        $this->order_total = $order_total;
        
        // Set document information
        $this->SetCreator(PDF_CREATOR);
        $this->SetAuthor('QLSP System');
        $this->SetTitle('Hóa đơn #' . $order_data['ma_don_hang']);
        $this->SetSubject('Hóa đơn bán hàng');
        
        // Remove default header/footer
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);
        
        // Set margins
        $this->SetMargins(15, 15, 15);
        $this->SetAutoPageBreak(TRUE, 15);
        
        // Set font - using default TCPDF font that supports UTF-8
        $this->SetFont('dejavusans', '', 12);
    }

    // Page header
    public function Header()
    {
        // Logo
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        if (file_exists($image_file)) {
            $this->Image($image_file, 15, 10, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        }
        
        // Title
        $this->SetFont('dejavusans', 'B', 18);
        $this->Cell(0, 10, 'HÓA ĐƠN BÁN HÀNG', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(10);
        
        // Add line separator
        $this->Line(15, $this->GetY(), 195, $this->GetY());
        $this->Ln(5);
    }

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        $this->SetFont('dejavusans', '', 8);
        $this->Cell(0, 10, 'Trang '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    public function generateInvoice()
    {
        $this->AddPage();
        
        // Header
        $this->Header();
        
        // Order information
        $this->SetFont('dejavusans', '', 12);
        $this->Cell(0, 6, 'Mã đơn hàng: ' . ($this->order_data['ma_don_hang'] ?? 'N/A'), 0, 1);
        $this->Cell(0, 6, 'Ngày đặt: ' . (isset($this->order_data['ngay_tao']) ? date('d/m/Y H:i:s', strtotime($this->order_data['ngay_tao'])) : 'N/A'), 0, 1);
        // $this->Cell(0, 6, 'Người tạo: ' . ($this->order_data['ten_user'] ?? 'N/A'), 0, 1);
        // $this->Cell(0, 6, 'Khách hàng: ' . ($this->order_data['ten_khach_hang'] ?? 'N/A'), 0, 1);
        // $this->Cell(0, 6, 'Điện thoại: ' . ($this->order_data['sdt_khach_hang'] ?? 'N/A'), 0, 1);
        // $this->Cell(0, 6, 'Địa chỉ: ' . ($this->order_data['dia_chi_khach_hang'] ?? 'N/A'), 0, 1);
        $this->Ln(8);

        // Table header
        $this->SetFont('dejavusans', 'B', 12);
        $header = array('Món ăn', 'Số lượng', 'Đơn giá', 'Thành tiền');
        $w = array(90, 20, 30, 30);

        for($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 0);
        $this->Ln();

        // Data
        $this->SetFont('dejavusans', '', 12);
        foreach($this->order_details as $detail) {
            $this->Cell($w[0], 6, $detail['ten_mon'], 'LRB', 0, 'L');
            $this->Cell($w[1], 6, $detail['so_luong'], 'RB', 0, 'C');
            $this->Cell($w[2], 6, number_format($detail['gia_tai_thoi_diem_dat'], 0, '.', '') . 'đ', 'RB', 0, 'R');
            $this->Cell($w[3], 6, number_format($detail['gia_tai_thoi_diem_dat'] * $detail['so_luong'], 0, '.', '') . 'đ', 'RB', 0, 'R');
            $this->Ln();
        }

        // Total
        $this->SetFont('dejavusans', 'B', 12);
        $this->Cell(array_sum($w) - 30, 6, 'Tổng cộng:', 'T', 0, 'R');
        $this->Cell(30, 6, number_format($this->order_total, 0, '.', '') . 'đ', 'TB', 0, 'R');
        $this->Ln(12);

        // Footer text
        $this->SetFont('dejavusans', '', 12);
        $this->Cell(0, 6, 'Cảm ơn quý khách đã sử dụng dịch vụ!', 0, 1, 'C');
        $this->Ln(5);

        // Signature
        $this->SetFont('dejavusans', '', 12);
        $this->Cell(90, 10, 'Khách hàng', 0, 0, 'C');
        $this->Cell(90, 10, 'Nhân viên bán hàng', 0, 1, 'C');
        $this->Ln(15);
        $this->Cell(90, 10, '(Ký và ghi rõ họ tên)', 0, 0, 'C');
        
        $text = "(Ký và ghi rõ họ tên)\nNgười tạo: " . ($this->order_data['ten_user'] ?? 'N/A');
        $this->MultiCell(90, 6, $text, 0, 'C');

    }
}
?>
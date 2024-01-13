<?php
require_once('tcpdf/tcpdf.php');

function generatePurchaseOrderPDF($conn, $sup_id)
{
    // Fetch approved requisitions with the same sup_id
    $sql = "SELECT * FROM requisition WHERE req_status = 'APPROVED' AND sup_id = '$sup_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $pdf = new MYPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();

        // Add a title
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Requisition Form', 0, 1, 'C');
        $pdf->Ln();

        // Set column headers for Purchase Order
        $headers = array('Product Name', 'Product Description', 'Brand Name', 'Quantity');
        $pdf->SetFillColor(200, 220, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('', 'B');
        $w = array(60, 80, 60, 25);
        for ($i = 0; $i < count($headers); $i++) {
            $pdf->Cell($w[$i], 10, $headers[$i], 1, 0, 'C', 1);
        }
        $pdf->Ln();

        // Add data rows
        while ($row = $result->fetch_assoc()) {
            // Fetch brand_name from brand table based on brand_id
            $brandId = $row['brand_id'];
            $brandNameQuery = "SELECT Brand_Name FROM brand WHERE brand_id = '$brandId'";
            $brandResult = $conn->query($brandNameQuery);
            $brandName = ($brandResult && $brandResult->num_rows > 0) ? $brandResult->fetch_assoc()['Brand_Name'] : 'N/A';

            $pdf->Cell($w[0], 8, $row['req_prod'], 1);
            $pdf->Cell($w[1], 8, $row['req_desc'], 1);
            $pdf->Cell($w[2], 8, $brandName, 1);
            $pdf->Cell($w[3], 8, $row['req_quantity'], 1);
            $pdf->Ln();
        }

        // Save the PDF file with a purchase order filename
        $pdfFilePath = 'C:\RF/purchase_order_' . $sup_id . '.pdf';
        $pdf->Output($pdfFilePath, 'F');

        return $pdfFilePath;
    } else {
        return false;
    }
}

class MYPDF extends TCPDF {

    // Load table data from file
    public function LoadData($file) {
        // Read file lines
        $lines = file($file);
        $data = array();
        foreach ($lines as $line) {
            $data[] = explode(';', chop($line));
        }
        return $data;
    }

    // Colored table
    public function ColoredTable($header, $data) {
        // Colors, line width, and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(40, 35, 40, 45);
        $num_headers = count($header);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, number_format($row[2]), 'LR', 0, 'R', $fill);
            $this->Cell($w[3], 6, number_format($row[3]), 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}
?>

<?php
include "tcpdf/tcpdf.php";

function generatePDFReceipt($rows, $total_amount, $amount_paid, $change, $invoice_id) {
    // Create a new PDF document
    $pdf = new TCPDF();

    // Add a page to the PDF
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('times', 'B', 14);

    // Add title
    $pdf->Cell(0, 10, "Mimi's Pet Corner", 0, 1, 'C');

    $pdf->SetFont('times', '', 12);
    $pdf->Cell(0, 10, 'Mahayahay, Gabi', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Cordova', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Tax No. 429-885-215-001', 0, 1, 'C');
    $pdf->Cell(0, 10, '09284331344', 0, 1, 'C');
    $pdf->SetFont('times', '', 12);
    $pdf->Cell(0, 10, 'Invoice No.: ' . $invoice_id, 0, 1, 'C');
    // Add order details
    $pdf->SetFont('times', 'B', 12);
    $pdf->Cell(0, 10, 'Order Details', 0, 1, 'C');
    

    // Add individual order details
    foreach ($rows as $row) {
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 10, 'Order ID: ' . $row['order_id'], 0, 1, 'C');
        $pdf->Cell(0, 10, 'Order Name: ' . $row['order_name'], 0, 1, 'C');
        $pdf->Cell(0, 10, 'Product Description: ' . $row['prod_desc'], 0, 1, 'C');
        $pdf->Cell(0, 10, 'Quantity: ' . $row['order_qty'], 0, 1, 'C');
        $pdf->Cell(0, 10, 'Price: ' . $row['order_price'], 0, 1, 'C');
        $pdf->Cell(0, 5, '', 0, 1, 'C'); // Add some space between entries
    }

    $pdf->SetFont('times', 'B', 12);
    $pdf->Cell(0, 10, 'Total Amount: ' . $total_amount, 0, 1, 'C');
    $pdf->Cell(0, 10, 'Amount Paid: ' . $amount_paid, 0, 1, 'C');
    $pdf->Cell(0, 10, 'Change: ' . $change, 0, 1, 'C');

    // Specify the file name
    $filename = "C:/RF/receipt_inv_" . $invoice_id . ".pdf";

    // Save the PDF to the specified file
    $pdf->Output($filename, 'F');
}
?>
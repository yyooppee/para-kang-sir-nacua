<?php
include "components/db.php";
include "tcpdf/tcpdf.php";

// Include your database connection code here

// Function to generate a PDF for approved requisitions with the same sup_id
function generateApprovedRequisitionsPDF($conn, $sup_id)
{
    // Fetch approved requisitions with the specified sup_id
    $sql = "SELECT * FROM requisition WHERE sup_id = '$sup_id' AND req_status = 'APPROVED'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $pdf = new TCPDF();
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();

        // Set column headers
        $headers = array('Requisition ID', 'Product Name', 'Product Description', 'Brand Name', 'Quantity', 'Status');
        $pdf->SetFillColor(200, 220, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('', 'B');
        $w = array(25, 40, 40, 40, 25, 25);
        for ($i = 0; $i < count($headers); $i++) {
            $pdf->Cell($w[$i], 10, $headers[$i], 1, 0, 'C', 1);
        }
        $pdf->Ln();

        // Add data rows
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell($w[0], 8, $row['req_id'], 1);
            $pdf->Cell($w[1], 8, $row['req_prod'], 1);
            $pdf->Cell($w[2], 8, $row['req_desc'], 1);

            // Fetch brand_name from brand table based on brand_id
            $brandId = $row['brand_id'];
            $brandNameQuery = "SELECT Brand_Name FROM brand WHERE brand_id = '$brandId'";
            $brandResult = $conn->query($brandNameQuery);
            $brandName = ($brandResult && $brandResult->num_rows > 0) ? $brandResult->fetch_assoc()['Brand_Name'] : 'N/A';

            $pdf->Cell($w[3], 8, $brandName, 1);
            $pdf->Cell($w[4], 8, $row['req_quantity'], 1);
            $pdf->Cell($w[5], 8, $row['req_status'], 1);
            $pdf->Ln();
        }

        // Save the PDF file
        $pdfFilePath = 'C:\RF/requisitions_' . $sup_id . '.pdf';
        $pdf->Output($pdfFilePath, 'F');

        return $pdfFilePath;
    } else {
        return false;
    }
}

// Handle form submission
if (isset($_POST['submit'])) {
    $sup_id = $_POST['sup_id'];

    // Check if the supplier id is set and not empty
    if (!empty($sup_id)) {
        // Generate PDF for approved requisitions with the specified sup_id
        $pdfFilePath = generateApprovedRequisitionsPDF($conn, $sup_id);

        if ($pdfFilePath) {
            echo "<script>alert('PDF generated successfully. Path: $pdfFilePath');</script>";
        } else {
            echo "<script>alert('No approved requisitions found for the specified Supplier ID');</script>";
        }
    } else {
        echo "<script>alert('Please enter a Supplier ID to generate the PDF');</script>";
    }
}
?>

<html>
<head>
    <title>Print Requisition Form</title>
    <?php include "components/head.php"; ?>
    <!-- Include any necessary styles or scripts here -->
</head>
<body>
<a href="request.php"><button type="button">Back</button></a>
    <form action="" method="post">
        <div class="content">
            <div class="container">
            
                <h3>Print Requisition Form</h3><br>
                <label for="sup_id">Enter Supplier ID:</label>
                <input type="text" id="sup_id" name="sup_id" required>
                <input type="submit" name="submit" value="Generate Requisition Form">
                
            </div>
        </div>
    </form>
    <!-- Include any additional content or styling as needed -->
</body>
</html>

<?php 
include "components/db.php"; 
include "components/func.php";
?>

<?php 
$sql = "SELECT * FROM orderlist WHERE order_stat = 'pending';";
$result = $conn->query($sql);
?>

<html>
<head>
    <?php include "components/head.php"; ?>
</head>
<body>
    <?php include "components/nav.php"; ?>

    <div class="content">
        <div class="container">
            <h1> Mimi's Pet Shop </h1>
            <p>Mahayahay, Gabi, Cordova</p>
            <p>mimispetcorner@gmail.com</p>
        </div>
    </div>

    <div class="content">
        <div class="container">  
            <h2> Invoice Id </h2>
            <?php
                // Get the highest invoice ID
                $sql_max_invoice = "SELECT MAX(invo_id) AS max_invoice_id FROM invoice";
                $result_max_invoice = mysqli_query($conn, $sql_max_invoice);
            
                if ($row_max_invoice = mysqli_fetch_assoc($result_max_invoice)) {
                    // Use the highest invoice ID for the new invoice
                    $invoice_id = $row_max_invoice['max_invoice_id']; // Increment the value
                    ?>
                    <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>">
                    <h4><?php echo $invoice_id; ?></h4>
                    <?php
                }
            ?>
        </div>
    </div>

    <div class="content">
        <div class="container">  
            <h2> Cart </h2>
            <form method="post" action="">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr class="bg-dark text-white">
                            <th>order_id</th>
                            <th>prod_id</th>
                            <th>order_name</th>
                            <th>prod_desc</th>
                            <th>order_qty</th>
                            <th>order_price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_amount = 0;

                        while ($row = mysqli_fetch_assoc($result)) {
                            $total_amount += $row['order_price']; // Accumulate total amount
                            ?>
                            <tr>
                                <td><?php echo $row['order_id']?></td>
                                <td><?php echo $row['prod_id']?></td>
                                <td><?php echo $row['order_name']?></td>
                                <td><?php echo $row['prod_desc']?></td>
                                <td><?php echo $row['order_qty']?></td>
                                <td><?php echo $row['order_price']?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <p>Total Amount: <?php echo $total_amount; ?></p>
                <label for="amount_paid">Amount Paid:</label>
                <input type="number" name="amount_paid" id="amount_paid" required>
                <input type="submit" value="Purchase" name="buy">
                <input type="button" value="Edit Cart" onclick="window.location.href='editcart.php';">
           </form>

           <?php
            // Handle the Purchase button logic
            if(isset($_POST["buy"])){
                // Assuming emp_id is hardcoded as 1
                $emp_id = 15;

                // Get the amount paid from the form
                $amount_paid = $_POST["amount_paid"];

                // Calculate change
                $change = $amount_paid - $total_amount;

                // Update order statuses to 'completed'
                $sql_update_orders = "UPDATE orderlist SET order_stat = 'completed' WHERE order_stat = 'pending'";
                $result_update_orders = mysqli_query($conn, $sql_update_orders);

                if($result_update_orders){
                    // Update the existing invoice with the current invoice ID
                    $sql_update_invoice = "UPDATE invoice SET invo_amnt = $total_amount, invo_stat = 'closed' WHERE invo_id = $invoice_id";
                    $result_update_invoice = mysqli_query($conn, $sql_update_invoice);

                    if($result_update_invoice){
                        // Generate PDF receipt with change
                        generatePDFReceipt($result, $total_amount, $amount_paid, $change, $invoice_id);
                        echo "<script>alert('Purchase successful.')</script>";
                    } else {
                        echo "<script>alert('Error updating invoice: " . mysqli_error($conn) . "')</script>";
                    }
                } else {
                    echo "<script>alert('Error updating orders: " . mysqli_error($conn) . "')</script>";
                }
            }
            ?>
        </div>
    </div>

    <?php include "components/scripts.php"; ?>
</body>
</html>

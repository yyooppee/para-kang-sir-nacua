<?php
include "components/db.php";

// Start a session
session_start();

$sql = "SELECT * FROM product p
        JOIN inventory i ON p.prod_id = i.prod_id
        WHERE i.inv_item_qty > 0";
$result = $conn->query($sql);

// Set default values for user type and ID
$userType = '';
$userId = '';

// Check if the user is an employee
if (isset($_SESSION['emp_id']) && !isset($_SESSION['emp_id_alert_shown'])) {
    echo "<script>alert('emp_id is set: " . $_SESSION['emp_id'] . "')</script>";
    $_SESSION['emp_id_alert_shown'] = true; // Set the flag to true
} else {
    // echo "<script>alert('emp_id is NOT set')</script>"; // Uncomment this line if you want to show the alert when not set
}

?>

<html>
<head>
    <?php include "components/head.php"; ?>
    <!-- Add this script inside the head tag or include it in component/scripts.php -->
    <script>
        function validateQuantity(input) {
            if (input.value <= 0) {
                alert("Quantity must be greater than 0");
                input.value = ""; // Clear the input field
                return false;
            }
            return true;
        }
    </script>
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
            <h2>Pet Supplies</h2>
            <form method="post" action="">
                <!-- Hidden fields to store user type and ID -->
                <input type="hidden" name="userType" value="<?php echo $userType; ?>">
                <input type="hidden" name="userId" value="<?php echo $userId; ?>">

                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $prod_id = $row['prod_id'];
                    ?>
                    <div class="product">
                        <button type="button" class="collapsible">
                            <h2><?php echo $row['prod_name']; ?></h2>
                        </button>
                        <div class="content">
                            <p>Description: <?php echo $row['prod_desc']; ?></p>
                            <p>Price: <?php echo $row['prod_price']; ?></p>
                            <input type="number" name="quantity_<?php echo $prod_id; ?>" value="" oninput="validateQuantity(this)">
                            <input type="hidden" name="prod_desc_<?php echo $prod_id; ?>" value="<?php echo $row['prod_desc']; ?>">
                            <!-- Convert prod_price to float before submitting -->
                            <input type="hidden" name="prod_price_<?php echo $prod_id; ?>" value="<?php echo floatval($row['prod_price']); ?>">
                            <input type="hidden" name="prod_id_<?php echo $prod_id; ?>" value="<?php echo $prod_id; ?>">
                            <input type="submit" name="button_<?php echo $prod_id; ?>" value="Add to Cart">
                        </div>
                    </div>
                    <?php
                }
                ?>
            </form>
        </div>
    </div>

    <?php
    // Handle the Add to Cart button logic
    foreach ($result as $row) {
        $prod_id = $row['prod_id'];
        $button_name = "button_" . $prod_id;

        if (isset($_POST[$button_name])) {
            $quantity = $_POST["quantity_$prod_id"];

            if ($quantity <= 0) {
                echo "<script>alert('Quantity must be greater than 0')</script>";
                continue; // Skip processing this item
            }

            $userType = $_POST["userType"];
            $userId = $_POST["userId"];

            // Assuming prod_desc is posted from the form
            $prod_desc = mysqli_real_escape_string($conn, $_POST["prod_desc_$prod_id"]);

            // Fetch prod_id based on prod_desc
            $query_prod_id = "SELECT prod_id FROM product WHERE prod_desc = '$prod_desc'";
            $result_prod_id = mysqli_query($conn, $query_prod_id);

            if ($result_prod_id) {
                // Check if a row is returned
                if ($row_prod_id = mysqli_fetch_assoc($result_prod_id)) {
                    $prod_id = $row_prod_id['prod_id'];
                    $ord_name = $row['prod_name'];
                    $ord_stat = 'pending';
                    $ord_qty = $_POST["quantity_$prod_id"];

                    // Convert prod_price to float
                    $subtotal_price = floatval($_POST["prod_price_$prod_id"]);

                    // Check if there is an open invoice for the current user
                    $sql_check_invoice = "SELECT invo_id FROM invoice WHERE emp_id = {$_SESSION['emp_id']} AND invo_stat = 'open'";
                    $result_check_invoice = mysqli_query($conn, $sql_check_invoice);

                    if ($result_check_invoice) {
                        // If an open invoice exists, use its ID
                        if ($row_invoice = mysqli_fetch_assoc($result_check_invoice)) {
                            $invoice_id = $row_invoice['invo_id'];
                        } else {
                            // If no open invoice exists, create a new one
                            $sql_insert_invoice = "INSERT INTO invoice (invo_amnt, invo_date, emp_id, invo_stat) 
                                                  VALUES (0, NOW(), {$_SESSION['emp_id']}, 'open')";
                            $result_insert_invoice = mysqli_query($conn, $sql_insert_invoice);

                            if ($result_insert_invoice) {
                                $invoice_id = mysqli_insert_id($conn);
                            } else {
                                echo "<script>alert('Error creating invoice: " . mysqli_error($conn) . "')</script>";
                            }
                        }

                        // Insert the order into the orderlist table
                        $ord_price = $subtotal_price * $ord_qty;

                        // Fetch inv_item_qty based on prod_id
                        $query_inventory_qty = "SELECT inv_item_qty FROM inventory WHERE prod_id = '$prod_id'";
                        $result_inventory_qty = mysqli_query($conn, $query_inventory_qty);

                        if ($result_inventory_qty) {
                            // Check if a row is returned
                            if ($row_inventory_qty = mysqli_fetch_assoc($result_inventory_qty)) {
                                $inv_item_qty = $row_inventory_qty['inv_item_qty'];

                                // Check if the order quantity is greater than the available inventory
                                if ($ord_qty > $inv_item_qty) {
                                    echo "<script>alert('Insufficient inventory. Available quantity: $inv_item_qty')</script>";
                                } else {
                                    // Subtract the order quantity from the inventory item quantity
                                    $sql_update_inventory = "UPDATE inventory SET inv_item_qty = inv_item_qty - $ord_qty WHERE prod_id = '$prod_id'";
                                    $result_update_inventory = mysqli_query($conn, $sql_update_inventory);

                                    if ($result_update_inventory) {
                                        // Insert the order into the orderlist table
                                        $sql_insert_order = "INSERT INTO orderlist (prod_id, order_name, order_qty, order_price, prod_desc, invo_id, order_stat)
                                        VALUES ('$prod_id', '$ord_name', '$ord_qty', '$ord_price', '$prod_desc', '$invoice_id', '$ord_stat')";

                                        $result_insert_order = mysqli_query($conn, $sql_insert_order);

                                        if ($result_insert_order) {
                                            echo "<script>alert('Data inserted and inventory updated')</script>";
                                        } else {
                                            echo "<script>alert('Error inserting order data: " . mysqli_error($conn) . "')</script>";
                                        }
                                    } else {
                                        echo "<script>alert('Error updating inventory: " . mysqli_error($conn) . "')</script>";
                                    }
                                }
                            } else {
                                echo "<script>alert('Error fetching inventory quantity')</script>";
                            }
                        } else {
                            echo "<script>alert('Error querying inventory: " . mysqli_error($conn) . "')</script>";
                        }
                    } else {
                        echo "<script>alert('Error checking invoice: " . mysqli_error($conn) . "')</script>";
                    }
                } else {
                    echo "<script>alert('Product not found')</script>";
                }
            } else {
                echo "<script>alert('Error querying product: " . mysqli_error($conn) . "')</script>";
            }
        }
    }
    ?>

    <?php include "components/scripts.php"; ?>
</body>
</html>

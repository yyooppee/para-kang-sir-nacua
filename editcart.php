<?php
include "components/db.php";

$sql = "SELECT * FROM orderlist WHERE order_stat = 'pending'";
$result = $conn->query($sql);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
            <form method="post" action="">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr class="bg-dark text-white">
                            <th>Order ID</th>
                            <th>Product ID</th>
                            <th>Order Name</th>
                            <th>Product Description</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($rows as $row): ?>
                            <tr>
                                <td><?= $row['order_id'] ?></td>
                                <td><?= $row['prod_id'] ?></td>
                                <td><?= $row['order_name'] ?></td>
                                <td><?= $row['prod_desc'] ?></td>
                                <td>
                                    <input type="number" name="quantity[<?= $row['order_id'] ?>]" value="<?= $row['order_qty'] ?>" min="1">
                                </td>
                                <td><?= $row['order_price'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php
                if(isset($_POST["update"])) {
                    foreach ($rows as $row) {
                        $order_id = $row["order_id"];
                        $newQuantity = $_POST['quantity'][$order_id];
                        $prod_id = $row['prod_id'];
                        $prod_price_sql = "SELECT prod_price FROM product WHERE prod_id = '$prod_id'";
                        $result_prod_price = mysqli_query($conn, $prod_price_sql);
                
                        if ($result_prod_price && $prod_price_row = mysqli_fetch_assoc($result_prod_price)) {
                            $prod_price = $prod_price_row['prod_price'];
                            $newPrice = $prod_price * $newQuantity;

                            // Calculate the difference in quantity
                            $quantityDifference = $newQuantity - $row['order_qty'];

                            // Update the orderlist table
                            $update_qty_price_sql = "UPDATE orderlist SET order_qty = '$newQuantity', order_price = '$newPrice' WHERE order_id = '$order_id'";
                            $result_update_qty_price = mysqli_query($conn, $update_qty_price_sql);

                            // Update the inventory item quantity
                            $update_inv_qty_sql = "UPDATE inventory SET inv_item_qty = inv_item_qty - '$quantityDifference' WHERE prod_id = '$prod_id'";
                            $result_update_inv_qty = mysqli_query($conn, $update_inv_qty_sql);

                            if (!$result_update_qty_price || !$result_update_inv_qty) {
                                echo "<script>alert('Error updating cart or inventory')</script>";
                            }
                        } else {
                            echo "<script>alert('Error fetching product price')</script>";
                        }
                    }
                
                    echo "<script>alert('Cart and Inventory Successfully updated!')</script>";
                }
                ?>
                <input type="submit" value="Update Cart" name="update">
            </form>
        </div>
    </div>

    <?php include "components/scripts.php"; ?>
</body>
</html>

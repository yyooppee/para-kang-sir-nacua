<?php
include "components/db.php";

// Function to retrieve product details by ID
function getProductDetails($conn, $productId) {
    $query = "SELECT * FROM product WHERE Prod_Id = '$productId'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

// Update product
if (isset($_POST["update"])) {
    $productId = $_POST["UProductId"];
    $columnToUpdate = $_POST["columnToUpdate"];
    $newValue = $_POST["newValue"];

    // Validate input based on columnToUpdate
    if (($columnToUpdate == 'Prod_Price' || $columnToUpdate == 'Inv_Item_Qty') && !is_numeric($newValue)) {
        echo "<script>alert('Invalid input for $columnToUpdate. Please enter a numeric value.')</script>";
    } else {
        // Update the product table
        $updateProductQuery = "UPDATE product SET $columnToUpdate='$newValue' WHERE prod_id='$productId'";
        mysqli_query($conn, $updateProductQuery);

        // Update the inventory table
        if ($columnToUpdate == 'Inv_Item_Qty') {
            $updateInventoryQuery = "UPDATE inventory SET inv_item_qty='$newValue' WHERE prod_id='$productId'";
            mysqli_query($conn, $updateInventoryQuery);
        }

        echo "<script>alert('Product and inventory updated successfully');</script>";
    }
}


// Display Product Details
$getallprod = "SELECT * FROM product";
$result = mysqli_query($conn, $getallprod);

$getbrandname = "SELECT product.prod_id, product.prod_name, brand.brand_name
                 FROM product
                 INNER JOIN brand ON product.brand_id = brand.brand_id";

$brand_result = mysqli_query($conn, $getbrandname);
?>

<html>
<head>
    <?php include "Style.php"; ?>

</head>
<body>
    <?php include "header.php"; ?>

    <div class="content">
        <div class="container">
            <h1> Mimi's Pet Shop </h1>
        </div>
    </div>

    <!-- Display Product Details -->
<div class="content">
    <div class="container">
        <h2>Product Details</h2>

        <!-- Search Box -->
        <label for="search">Search Product:</label>
        <input type="text" id="search" oninput="filterTable()" placeholder="Type to search"><br><br>

        <form action="" method="post">
            <table>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Product Description</th>
                    <th>Brand</th>
                    <th>Product Price</th>
                    <th>Item Quantity</th>
                </tr>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    // Fetch brand name
                    $brand_query = "SELECT brand_name FROM brand WHERE brand_id = " . $row['brand_id'];
                    $brand_result = mysqli_query($conn, $brand_query);
                    $brand_data = mysqli_fetch_assoc($brand_result);

                    // Fetch inventory item quantity
                    $inv_query = "SELECT * FROM inventory WHERE prod_id = " . $row['prod_id'];
                    $inv_result = mysqli_query($conn, $inv_query);
                    $inv_data = mysqli_fetch_assoc($inv_result);

                    // Only display rows where the inventory item quantity is greater than 0
                    if ($inv_data !== null && $inv_data['inv_item_qty'] > 0) {
                        ?>
                        <tr>
                            <td><?php echo $row['prod_id']; ?></td>
                            <td class="editable" onclick="editCell('<?php echo $row['prod_id']; ?>', 'Prod_Name', '<?php echo $row['prod_name']; ?>')"><?php echo $row['prod_name']; ?></td>
                            <td class="editable" onclick="editCell('<?php echo $row['prod_id']; ?>', 'Prod_Desc', '<?php echo $row['prod_desc']; ?>')"><?php echo $row['prod_desc']; ?></td>
                            <!-- Remove onclick attribute for brand column -->
                            <td><?php echo $brand_data['brand_name']; ?></td>
                            <td class="editable" onclick="editNumberCell('<?php echo $row['prod_id']; ?>', 'Prod_Price', '<?php echo $row['prod_price']; ?>')"><?php echo $row['prod_price']; ?></td>
                            <td><?php echo $inv_data['inv_item_qty']; ?></td>
                        </tr>
                    <?php
                    }
                }
                ?>
            </table>

            <input type="hidden" id="UProductId" name="UProductId">
            <input type="hidden" id="columnToUpdate" name="columnToUpdate">
            <input type="number" id="newValue" name="newValue" style="display: none;" min="0" step="0.01">
            <input type="submit" name="update" value="Update" style="display: none;">
        </form>
    </div>
</div>


<?php include "scripts.php"; ?>
<script>
    function editCell(productId, columnToUpdate, currentValue) {
        document.getElementById("UProductId").value = productId;
        document.getElementById("columnToUpdate").value = columnToUpdate;
        document.getElementById("newValue").value = currentValue;
        document.getElementById("newValue").type = "text";
        document.getElementById("newValue").style.display = "inline";
        document.getElementById("update").style.display = "inline";
    }

    function editNumberCell(productId, columnToUpdate, currentValue) {
        document.getElementById("UProductId").value = productId;
        document.getElementById("columnToUpdate").value = columnToUpdate;
        document.getElementById("newValue").value = currentValue;
        document.getElementById("newValue").type = "number";
        document.getElementById("newValue").min = "0";
        document.getElementById("newValue").step = "0.01";
        document.getElementById("newValue").style.display = "inline";
        document.getElementById("update").style.display = "inline";
    }
    function filterTable() {
        // Get input value from the search box
        var searchInput = document.getElementById('search').value.toLowerCase();

        // Get all table rows
        var rows = document.querySelectorAll('table tr');

        // Loop through each row and hide/show based on the search input
        for (var i = 1; i < rows.length; i++) {
            var productName = rows[i].getElementsByTagName('td')[1].innerText.toLowerCase();

            if (productName.includes(searchInput)) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }
</script>

</body>
</html>

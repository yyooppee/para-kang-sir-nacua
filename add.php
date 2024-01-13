<?php
session_start();
include "components/db.php";

$pname = "";
$pdesc = "";
$pprice = "";
$brand = "";
$qty = "";

// Function to retrieve product details by ID
function getProductDetails($conn, $productId) {
    $query = "SELECT * FROM product WHERE Prod_Id = '$productId'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

// Add new product
if (isset($_POST["submit"])) {
    $pname = $_POST["ProdName"];
    $pdesc = $_POST["ProdDesc"];
    $pprice = $_POST["ProdPrice"];
    $brand = $_POST["Brand"];
    $qty = $_POST["InvItemQty"];

    // Check if the brand already exists
    $checkBrandQuery = "SELECT * FROM brand WHERE UPPER(Brand_Name) = UPPER('$brand')";
    $brandResult = mysqli_query($conn, $checkBrandQuery);

    if ($brandResult !== false) {
        // Check if the query was successful
        if (mysqli_num_rows($brandResult) > 0) {
            // Brand already exists, retrieve the existing brand ID
            $brandData = mysqli_fetch_assoc($brandResult);
            $brandId = $brandData["brand_id"];
        } else {
            // Brand doesn't exist, insert a new brand and get the brand ID
            $insertBrandQuery = "INSERT INTO brand (Brand_Name) VALUES ('$brand')";
            mysqli_query($conn, $insertBrandQuery);
            $brandId = mysqli_insert_id($conn);
        }

        // Step 3: Insert product into the product table with the retrieved or new brand ID
        $query2 = "INSERT INTO product (Prod_Name, Prod_Desc, Prod_Price, Brand_Id) VALUES ('$pname', '$pdesc', '$pprice', '$brandId')";
        mysqli_query($conn, $query2);

        // Step 4: Insert data into the inventory table
        $query3 = "INSERT INTO inventory (Inv_Item_Qty, Prod_id) SELECT '$qty', Prod_Id FROM product WHERE Prod_Name = '$pname'";
        mysqli_query($conn, $query3);

        echo "<script>alert('Product is added');</script>";
    } else {
        // Error in the query, handle accordingly
        echo "<script>alert('Error checking Brand');</script>";
    }
}
// Function to fetch received requisition data with "RECEIVED" status, including brand_name
function getReceivedRequisitionsWithBrandName($conn) {
    $query = "SELECT requisition.req_id, requisition.req_prod, requisition.req_desc, requisition.req_quantity, requisition.req_item_price, requisition.req_status, brand.Brand_Name
              FROM requisition
              JOIN brand ON requisition.brand_id = brand.brand_id
              WHERE requisition.req_status = 'RECEIVED'";
    $result = mysqli_query($conn, $query);
    return $result;
}
if (isset($_POST["reqId"])) {
    $reqId = $_POST["reqId"];

    // Fetch data from requisition and brand tables
    $query = "SELECT requisition.req_prod, requisition.req_desc, requisition.req_quantity, requisition.req_item_price, brand.Brand_Name
              FROM requisition
              JOIN brand ON requisition.brand_id = brand.brand_id
              WHERE requisition.req_id = '$reqId'";
    $result = mysqli_query($conn, $query);

    if ($result !== false && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        // Insert data into product table
        $insertProductQuery = "INSERT INTO product (Prod_Name, Prod_Desc, Prod_Price, Brand_Id) 
                               VALUES ('{$data['req_prod']}', '{$data['req_desc']}', '{$data['req_item_price']}', 
                                       (SELECT brand_id FROM brand WHERE Brand_Name = '{$data['Brand_Name']}'))";
        mysqli_query($conn, $insertProductQuery);

        // Get the ID of the inserted product
        $productId = mysqli_insert_id($conn);

        // Insert data into inventory table
        $insertInventoryQuery = "INSERT INTO inventory (Inv_Item_Qty, Prod_Id) 
                                VALUES ('{$data['req_quantity']}', '$productId')";
        mysqli_query($conn, $insertInventoryQuery);

        // Update requisition status to indicate it has been processed
        $updateRequisitionQuery = "UPDATE requisition SET req_status = 'PROCESSED' WHERE req_id = '$reqId'";
        mysqli_query($conn, $updateRequisitionQuery);

        echo "Product and inventory data added successfully";
    } else {
        echo "Error fetching data from requisition and brand tables";
    }
}
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

    <div class="content">
    <div class="container">
        <!-- Table to display received requisitions -->
        <table border="1">
            <thead>
                <tr>
                    <th>Requisition ID</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Brand</th>
                    <th>Quantity</th>
                    <th>Item Price</th>
                    <th>Status</th>
                    <th>Action</th> <!-- New column for action buttons -->
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch received requisitions with brand_name
                $receivedRequisitions = getReceivedRequisitionsWithBrandName($conn);

                // Display data in the table
                
                while ($row = mysqli_fetch_assoc($receivedRequisitions)) {
                    ?>
                    <tr>
                        <td><?php echo $row['req_id']; ?></td>
                        <td><?php echo $row['req_prod']; ?></td>
                        <td><?php echo $row['req_desc']; ?></td>
                        <td><?php echo $row['Brand_Name']; ?></td>
                        <td><?php echo $row['req_quantity']; ?></td>
                        <td><?php echo $row['req_item_price']; ?></td>
                        <td><?php echo $row['req_status']; ?></td>
                        <td>
                            <button class="add-product-btn" data-req-id="<?php echo $row['req_id']; ?>">Add Prod</button>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Product Form -->
<form id="addProductForm" style="display: none;" method="post">
    <input type="hidden" id="reqIdInput" name="reqId" value="">
</form>

<script>
    // Function to handle the asynchronous form submission
    function addProduct(reqId) {
        var form = document.getElementById('addProductForm');
        var reqIdInput = document.getElementById('reqIdInput');

        // Set the reqId value in the form
        reqIdInput.value = reqId;

        // Perform asynchronous form submission using JavaScript fetch
        fetch('add_product.php', {
            method: 'POST',
            body: new FormData(form),
        })
        .then(response => response.text())
        .then(data => {
            alert(data); // Display the response message
            location.reload(); // Reload the page
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Add click event listener to all buttons with class "add-product-btn"
    var addProductButtons = document.getElementsByClassName('add-product-btn');
    for (var i = 0; i < addProductButtons.length; i++) {
        addProductButtons[i].addEventListener('click', function() {
            // Get the reqId from the data attribute
            var reqId = this.getAttribute('data-req-id');
            
            // Call the addProduct function with reqId
            addProduct(reqId);
        });
    }
</script>

    

    <?php include "scripts.php"; ?>
</body>
</html>

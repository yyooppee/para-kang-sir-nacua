<?php
include "components/db.php";

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

        // Check if the brand already exists
        $checkBrandQuery = "SELECT * FROM brand WHERE UPPER(Brand_Name) = UPPER('{$data['Brand_Name']}')";
        $brandResult = mysqli_query($conn, $checkBrandQuery);

        if ($brandResult !== false) {
            // Check if the query was successful
            if (mysqli_num_rows($brandResult) > 0) {
                // Brand already exists, retrieve the existing brand ID
                $brandData = mysqli_fetch_assoc($brandResult);
                $brandId = $brandData["brand_id"];
            } else {
                // Brand doesn't exist, insert a new brand and get the brand ID
                $insertBrandQuery = "INSERT INTO brand (Brand_Name) VALUES ('{$data['Brand_Name']}')";
                mysqli_query($conn, $insertBrandQuery);
                $brandId = mysqli_insert_id($conn);
            }

            // Insert data into product table
            $insertProductQuery = "INSERT INTO product (Prod_Name, Prod_Desc, Prod_Price, Brand_Id) 
                                   VALUES ('{$data['req_prod']}', '{$data['req_desc']}', '{$data['req_item_price']}', '$brandId')";
            mysqli_query($conn, $insertProductQuery);

            // Get the ID of the inserted product
            $productId = mysqli_insert_id($conn);

            // Insert data into inventory table
            $insertInventoryQuery = "INSERT INTO inventory (Inv_Item_Qty, Prod_Id) 
                                    VALUES ('{$data['req_quantity']}', '$productId')";
            mysqli_query($conn, $insertInventoryQuery);

            // Update requisition status to indicate it has been processed
            $updateRequisitionQuery = "UPDATE requisition SET req_status = 'DONE' WHERE req_id = '$reqId'";
            mysqli_query($conn, $updateRequisitionQuery);

            echo "Product and inventory data added successfully";
        } else {
            echo "Error checking Brand";
        }
    } else {
        echo "Error fetching data from requisition and brand tables";
    }
} else {
    echo "Invalid request";
}
?>

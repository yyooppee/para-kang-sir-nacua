<?php
include "components/db.php";

// Add Supplier
if (isset($_POST["submit"])) {
    $type = $_POST["sup_type"];
    $email = $_POST["sup_email"];
    $phone = $_POST["sup_phone"];

    $query = "INSERT INTO supplier (sup_type, sup_email, sup_phone) VALUES('$type', '$email', '$phone')";
    mysqli_query($conn, $query);

    echo "<script>alert('Supplier is added');</script>";
}

// Update Supplier
if (isset($_POST["update"])) {
    $updateSupId = $_POST["UProductId"];
    $columnToUpdate = $_POST["columnToUpdate"];
    $newValue = $_POST["newValue"];

    // Update the supplier table
    $updateSupplierQuery = "UPDATE supplier SET $columnToUpdate='$newValue' WHERE sup_id='$updateSupId'";
    mysqli_query($conn, $updateSupplierQuery);

    echo "<script>alert('Supplier updated successfully');</script>";
}

// Fetch all suppliers
$getAllSuppliersQuery = "SELECT * FROM supplier";
$supplierResult = mysqli_query($conn, $getAllSuppliersQuery);
?>

<html>
<head>
    <?php include "Style.php"; ?>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        td.editable {
            cursor: pointer;
        }

        td.editable:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <?php include "header.php"; ?>

    <div class="content">
        <div class="container">
            <h1> Mimi's Pet Shop </h1>
        </div>
    </div>

    <!-- Add Supplier Form -->
    <form action="" method="post">
        <div class="content">
            <div class="container">
                <h2>Add Supplier</h2>
                <label for="sup_type">Supplier Type:</label>
                <select class="" name="sup_type" required>
                    <option value="" selected hidden>--SELECT--</option>
                    <option value="Purrfect Treats Petshop">Purrfect Treats Petshop</option>
                    <option value="All Pet Supplies">All Pet Supplies</option>
                    <option value="Caminade Petshop">Caminade Petshop</option>
                    <option value="My Pet Station">My Pet Station</option>
                </select>
                <label for="sup_email">Supplier Email:</label>
                <input type="text" id="sup_email" name="sup_email" required><br><br>
                <label for="sup_phone">Supplier Phone:</label>
                <input type="text" id="sup_phone" name="sup_phone" required><br><br>
                <input type="submit" name="submit" value="Add">
            </div>
        </div>
    </form>



    <?php include "scripts.php"; ?>
</body>
</html>

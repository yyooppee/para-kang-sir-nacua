<?php
session_start();

    include("admin_comp/function.php");
    include("admin_comp/connection.php");

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {

        $adm_email = $_POST['adminEmail'];
        $adm_address = $_POST['adminAddress'];
        $adm_phone = $_POST['adminPhone'];
        $adm_username = $_POST['adminUsername'];
        $adm_password = $_POST['adminPassword'];

        if(!empty($adm_email) && !empty($adm_address) && !empty($adm_phone) && !empty($adm_username) && !empty($adm_password))
        {
            $query = "insert into admin (ad_email, ad_address, ad_phone, ad_username, ad_password) 
            values('$adm_email', '$adm_address', '$adm_phone', '$adm_username', '$adm_password')";

            mysqli_query($con, $query);

            header("Location: ad_login.php");
            die;
        }
        else
        {
            echo "please enter info";
        }

    }

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom right, #bf40bf, pink);
            text-align: center;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        h2 {
            color: #333;
        }

        form {
            background-color: #fff;
            border-radius: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            margin-top: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
            text-align: left;
        }

        input {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <h2>Admin Sign Up</h2>

    <form method="post" action="create_ad.php">
        <label for="adminEmail">Email:</label>
        <input type="text" id="adminEmail" name="adminEmail" required>

        <label for="adminAddress">Address:</label>
        <input type="text" id="adminAddress" name="adminAddress" required>

        <label for="adminPhone">Phone:</label>
        <input type="text" id="adminPhone" name="adminPhone" required>

        <label for="adminUsername">Username:</label>
        <input type="text" id="adminUsername" name="adminUsername" required>

        <label for="adminPassword">Password:</label>
        <input type="password" id="adminPassword" name="adminPassword" required>

        <input type="submit" value="Sign Up">
        <div class="form-link">
                <label for="reg"><a href ="ad_login.php">Go back</a>
            </div>
    </form>

</body>
</html>

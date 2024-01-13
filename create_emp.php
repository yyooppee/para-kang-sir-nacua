<?php
session_start();

include("emp_comp/emp_func.php");
include("emp_comp/emp_conn.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $emp_fname = $_POST['empFname'];
    $emp_lname = $_POST['empLname'];
    $emp_add = $_POST['empAdd'];
    $emp_phone = $_POST['empPhone'];
    $emp_email = $_POST['empEmail'];
    $emp_username = $_POST['empUsername'];
    $emp_password = $_POST['empPassword'];

    if (!empty($emp_fname) && !empty($emp_lname) && !empty($emp_add) && !empty($emp_phone) &&
        !empty($emp_email) && !empty($emp_username) && !empty($emp_password)) {

        // Fetch an ad_id from the admin table
        $query = "SELECT ad_id FROM admin ORDER BY RAND() LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $ad_id = $row['ad_id'];

            // Set emp_status to "Active" by default
            $emp_status = "Active";

            // Insert data into the employee table with the fetched ad_id and default emp_status
            $insertQuery = "INSERT INTO employee (ad_id, emp_fname, emp_lname, emp_address, emp_phone,
                           emp_email, emp_username, emp_password, emp_status) 
                           VALUES ('$ad_id', '$emp_fname', '$emp_lname', '$emp_add', '$emp_phone',
                           '$emp_email', '$emp_username', '$emp_password', '$emp_status')";

            if (mysqli_query($con, $insertQuery)) {
                echo "Employee account created successfully";
                header("Location: emp_login.php");
                die;
            } else {
                echo "Error inserting into employee table: " . mysqli_error($con);
            }
        } else {
            echo "Error fetching admin data: " . mysqli_error($con);
        }
    } else {
        echo "Please enter all required information.";
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Sign Up</title>
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

    <h2>Employee Sign Up</h2>

    <form method="post" action="create_emp.php">
        <label for="empFname">Firstname:</label>
        <input type="text" id="empFname" name="empFname" required>

        <label for="empLname">Lastname:</label>
        <input type="text" id="empLname" name="empLname" required>

        <label for="empAdd">Address:</label>
        <input type="text" id="empAdd" name="empAdd" required>

        <label for="empPhone">Phone:</label>
        <input type="text" id="empPhone" name="empPhone" required>

        <label for="empEmail">Email:</label>
        <input type="text" id="empEmail" name="empEmail" required>

        <label for="empUsername">Username:</label>
        <input type="text" id="empUsername" name="empUsername" required>

        <label for="empPassword">Password:</label>
        <input type="password" id="empPassword" name="empPassword" required>

        <input type="submit" value="Sign Up">
        <div class="form-link">
                <label for="reg"><a href ="emp_login.php">Go back</a>
            </div>
    </form>

</body>
</html>

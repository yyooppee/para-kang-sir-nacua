<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Employee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: left;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 0; 
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .success {
            color: green;
            margin-top: 10px;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
    <script>
        function confirmUpdate() {
            return confirm("Complete Updating?");
        }
    </script>
</head>
<body>

<?php
include("admin_comp/function.php");
include("admin_comp/connection.php");
session_start();
$user_data = check_login($con);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_id = mysqli_real_escape_string($con, $_POST['emp_id']);

    $query = "SELECT * FROM employee WHERE emp_id = '$emp_id'";
    $result = mysqli_query($con, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            echo "<form action='update_emp.php' method='post' onsubmit='return confirmUpdate();'>
                    <h2>Update Employee</h2>
                    <label for='emp_fname'>First Name:</label>
                    <input type='text' id='emp_fname' name='emp_fname' value='{$row['emp_fname']}' required><br>

                    <label for='emp_lname'>Last Name:</label>
                    <input type='text' id='emp_lname' name='emp_lname' value='{$row['emp_lname']}' required><br>

                    <label for='emp_email'>Email:</label>
                    <input type='email' id='emp_email' name='emp_email' value='{$row['emp_email']}' required><br>

                    <label for='emp_phone'>Phone:</label>
                    <input type='text' id='emp_phone' name='emp_phone' value='{$row['emp_phone']}' required><br>

                    <label for='emp_address'>Address:</label>
                    <input type='text' id='emp_address' name='emp_address' value='{$row['emp_address']}' required><br>

                    <label for='emp_address'>Username:</label>
                    <input type='text' id='emp_username' name='emp_username' value='{$row['emp_username']}' required><br>

                    <label for='emp_address'>Password:</label>
                    <input type='text' id='emp_password' name='emp_password' value='{$row['emp_password']}' required><br>

                    <label for='emp_status'>Status:</label>
                    <select id='emp_status' name='emp_status' required>
                        <option value='Active' " . ($row['emp_status'] == 'Active' ? 'selected' : '') . ">Active</option>
                        <option value='Disable' " . ($row['emp_status'] == 'Disable' ? 'selected' : '') . ">Disable</option>
                    </select><br>

                    <input type='hidden' name='emp_id' value='$emp_id'>
                    <button type='submit' name='update_employee'>Update Details</button>
                </form>";
        } else {
            echo "Employee not found.";
        }
    } else {
        echo "Error fetching employee data.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_employee'])) {
    $emp_id = mysqli_real_escape_string($con, $_POST['emp_id']);
    $emp_fname = mysqli_real_escape_string($con, $_POST['emp_fname']);
    $emp_lname = mysqli_real_escape_string($con, $_POST['emp_lname']);
    $emp_email = mysqli_real_escape_string($con, $_POST['emp_email']);
    $emp_phone = mysqli_real_escape_string($con, $_POST['emp_phone']);
    $emp_address = mysqli_real_escape_string($con, $_POST['emp_address']);
    $emp_username = mysqli_real_escape_string($con, $_POST['emp_username']);
    $emp_password = mysqli_real_escape_string($con, $_POST['emp_password']);
    $emp_status = mysqli_real_escape_string($con, $_POST['emp_status']);

    $update_query = "UPDATE employee SET 
                    emp_fname = '$emp_fname',
                    emp_lname = '$emp_lname',
                    emp_email = '$emp_email',
                    emp_phone = '$emp_phone',
                    emp_address = '$emp_address',
                    emp_username = '$emp_username',
                    emp_password = '$emp_password',
                    emp_status = '$emp_status'
                    WHERE emp_id = '$emp_id'";

    $update_result = mysqli_query($con, $update_query);

    if ($update_result) {
        header("Location: ad_dashboard.php?page=manage_employee");
        exit();
    } else {
        echo "Error updating employee details.";
    }
}

?>

</body>
</html>

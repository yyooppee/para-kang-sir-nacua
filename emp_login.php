<?php
session_start();

include("emp_comp/emp_func.php");
include("emp_comp/emp_conn.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['empUsername']) && isset($_POST['empPassword'])) {
        $emp_username = $_POST['empUsername'];
        $emp_password = $_POST['empPassword'];

        if (!empty($emp_username) && !empty($emp_password)) {
            $query = "SELECT * FROM employee WHERE emp_username = '$emp_username' LIMIT 1";
            $result = mysqli_query($con, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);

                if ($user_data['emp_password'] == $emp_password) {
                    $_SESSION['emp_id'] = $user_data['emp_id'];
                    header("Location: emp_dashboard.php");
                    die;
                }
            }
            $error_message = 'Invalid username or password';
        } else {
            $error_message = 'Invalid username or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: linear-gradient(to bottom right, #bf40bf, pink);
            margin: 0;
            display: flex;
            flex-direction: column; 
            align-items: center;
            height: 100vh;
        }
        .logo-container {
            text-align: center;
            margin-top: 10px; 
        }

        .logo {
            
            width: 200px;
        }

        .login-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 350px;
            margin-top: 5px; 
        }

        .login-container h2 {
            text-align: center;
            color: #333;
        }

        .login-form {
            margin-top: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="pics/mimis_logo.jpg" alt="Logo" class="logo">
    </div>
    <div class="login-container">
        <h2>Login</h2>

        <form class="login-form" method="post" action="emp_login.php">
            <div class="form-group">
            <label for="empUsername">Username:</label>
        <input type="text" id="empUsername" name="empUsername" required>
            </div>

            <div class="form-group">
            <label for="empPassword">Password:</label>
        <input type="password" id="empPassword" name="empPassword" required>
            </div>

            <div class="form-group">
                <input type="submit" value="Login">
            </div>
            <div class="form-link">
                <label for="reg"> Dont have account? <a href ="create_emp.php">Click Here</a> 
            </div>
        </form>

        <?php
        /*if (isset($error_message)) {
            echo '<p class="error-message">' . $error_message . '</p>';
        }*/
        ?>
    </div>

</body>
</html>

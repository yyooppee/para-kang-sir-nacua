<?php
    session_start();

    include("admin_comp/function.php");
    include("admin_comp/connection.php");

    $error_message = '';

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['adminUsername']) && isset($_POST['adminPassword'])) {
        $ad_username = $_POST['adminUsername'];
        $ad_password = $_POST['adminPassword'];

        if (!empty($ad_username) && !empty($ad_password)) {
            $query = "SELECT * FROM admin WHERE ad_username = '$ad_username' LIMIT 1";
            $result = mysqli_query($con, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);

                if ($user_data['ad_password'] == $ad_password) {
                    $_SESSION['ad_id'] = $user_data['ad_id'];
                    header("Location: ad_dashboard.php");
                    die;
                } else {
                    $error_message = 'Invalid username or password';
                }
            } else {
                $error_message = 'Invalid username or password';
            }
        } else {
            $error_message = 'Please enter both username and password';
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

        <form class="login-form" method="post" action="ad_login.php">
            <div class="form-group">
                <label for="adminUsername">Username:</label>
                <input type="text" id="adminUsername" name="adminUsername">
            </div>

            <div class="form-group">
                <label for="adminPassword">Password:</label>
                <input type="password" id="adminPassword" name="adminPassword">
            </div>

            <div class="form-group">
                <input type="submit" value="Login">
            </div>
            <div class="form-link">
                <label for="reg"> Don't have an account? <a href="create_ad.php">Click Here</a> 
            </div>
        </form>
        <div class="error-message">
            <?php
                if (!empty($error_message)) {
                    echo $error_message;
                }
            ?>
        </div>
    </div>
</body>
</html>

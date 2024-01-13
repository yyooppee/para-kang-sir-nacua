<?php
    include("emp_comp/emp_func.php");
    include("emp_comp/emp_conn.php");
    session_start();
    $_SESSION;
    $user_data = check_login_emp($con);

    if (isset($_GET['logout'])) 
    {
        session_destroy(); 
        header("Location: index.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:  #ffb6c1;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }

        .employee-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
            margin-top: 15px;
        }

        .profile-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
        }

        p {
            color: #555;
        }

        .logout-btn {
            background-color: #4caf50;
            color: #fff;
            padding: 8px 8px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #45a049;
        }

        .navbar {
            display: flex;
            justify-content: space-around;
            background-color: #333;
            padding: 10px;
            border-radius: 4px;
            margin-top: 15px;
            width: 100%; 
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .navbar a:hover {
            background-color: #555;
        }
        .top-right {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        button {
        background-color: #a7d8e3; /* Pastel Blue */
        border: none;
        color: #ffffff; /* White text */
        padding: 10px 15px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 4px;
    }

    button:hover {
        background-color: #86bfc7; /* Lighter Pastel Blue on hover */
    }
    .container {
            display: inline-block;
            text-align: center;
            padding: 20px;
            color: black;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(255, 231, 167);
            background-color: #C0F9FA;
    }
    .p{
            color : orange;
            text-align: center;
    }
    </style>
</head>

<body>

    <div class="profile-bar">
        <h2>Welcome, <?php echo $user_data['emp_fname']; ?>!</h2>
        <a class="logout-btn top-right" href="?logout=true">Logout</a>
    </div>
    <br>
<br>
<br>
<br>
<br>
    

    <div class="container">
    <p>employee dashboard.</p>
</div>
<br>
<br>
<br>
<br>
<div class = "container">
    <button onclick="window.location.href='add_products.php'">Add Products</button>
    <button onclick="window.location.href='order.php'">Add Invoice</button>
</div>

</body>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #FF66B2, #C9A0DC);
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
            font-size: 50px;
        }

        button {
            padding: 10px;
            margin: 10px;
            font-size: 50px;
            cursor: pointer;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
        }

        button:hover {
            background-color: #45a049;
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
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="pics/mimis_logo.jpg" alt="Logo" class="logo">
    </div>
    <h2>Choose Login</h2>

    <form method="post">
        <button type="submit" formaction="emp_login.php">Employee Login</button>
        <button type="submit" formaction="ad_login.php">Admin Login</button>
    </form>

</body>
</html>
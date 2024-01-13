<?php
include("admin_comp/function.php");
include("admin_comp/connection.php");
session_start();
$user_data = check_login($con);

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$search_condition = '';
$error_message = '';

if (isset($_GET['search_emp_id'])) {
    $search_emp_id = mysqli_real_escape_string($con, $_GET['search_emp_id']);
    if (!empty($search_emp_id)) {
        $search_condition = " WHERE emp_id = '$search_emp_id'";
    } else {
        $error_message = "No ID entered";
    }
}

$query = "SELECT * FROM employee" . $search_condition;
$result = mysqli_query($con, $query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: linear-gradient(to bottom right, #ffb6c1, pink);
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        #sidebar {
            width: 250px;
            background-color: #C0F9FA;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #fff;
        }

        #sidebar h1 {
            margin-bottom: 20px;
        }

        #sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        #sidebar li {
            margin-bottom: 10px;
        }
*
        #sidebar a {
            text-decoration: none;
            color: black;
            font-weight: bold;
            font-size: 16px;
        }

        #sidebar a:hover {
            color: #ffc107;
        }

        #content {
            flex: 1;
            padding: 20px;
            color: #fff;
        }

        h1 {
            text-align: center;
            color: black;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #fff;
            padding: 10px;
            text-align: left;
            color: #fff;
        }

        th {
            background-color: #2a5298;
        }

        tr:nth-child(even) {
            background-color: #1e3c72;
        }
        button {
            background-color: #4CAF50; 
            color: white;
            padding: 10px 15px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            margin-right: 5px;
        }

        button:hover {
            background-color: #45a049; 
        }

        button.delete {
            background-color: #f44336; 
        }

        button.delete:hover {
            background-color: #d32f2f; 
        }
        #heading {
            margin-bottom: 20px; 
            text-align: center;
        }

        #heading h2 {
            margin-bottom: 0; 
            text-align: center;
        }
        #profile-section {
            margin-top: 20px;
        }

        #profile-section img {
            max-width: 150px;
            max-height: 150px;
            border-radius: 50%;
        }

        #edit-profile-button {
            background-color: #ffc107;
        }

        #edit-profile-button:hover {
            background-color: #e0a800;
        }

        #buttons-container {
            display: flex;
            margin-top: 10px;
        }
        #buttons-container form {
            margin-right: 10px;
        }
        .login-container {
        width: 300px;
        margin: auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        color: #000; /* Set text color to black */
    }

    .login-container label {
        display: block;
        margin-bottom: 8px;
    }

    .login-container input {
        width: 100%;
        padding: 8px;
        margin-bottom: 16px;
        box-sizing: border-box;
    }

    .login-container button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .login-container button:hover {
        background-color: #45a049;
    }

    .login-container a {
        display: block;
        margin-top: 10px;
        text-align: center;
        color: #2a5298;
        text-decoration: none;
    }

    .login-container a:hover {
        color: #1e3c72;
    }


    </style>
</head>
<body>
    
    <div id="sidebar">
        <h1>Dashboard</h1>
        <ul>
            <li><a href="?page=home">Home</a></li>
            <li><a href="?page=manage_employee">Manage Employee</a></li>
            <li><a href="Add.php">Add Product</a></li>
            <li><a href="adminorder.php">Add Invoice</a></li>
            <li><a href="?logout=true">Logout</a></li>
        </ul>
    </div>
    
    <div id="content">
    <?php
    if (!isset($_GET['page'])) {
        $_GET['page'] = 'home';
    }

    if ($_GET['page'] === 'home') {
        echo "<h1>Welcome: Admin  " . (isset($user_data['ad_username']) ? $user_data['ad_username'] : "") . "!</h1>";
        // Check if the data is for admin or employee
        if (isset($user_data['ad_username'])) {
            // Admin profile data
            echo "<div id='profile-section'>
                    <img src='" . (!empty($user_data['profile_pic']) ? $user_data['profile_pic'] : 'pics/profile-pic-default.png') . "' alt='Profile Picture'>
                    <p>Username: " . $user_data['ad_username'] . "</p>
                    <form action='' method='get'>
                        <input type='hidden' name='page' value='edit_profile'>
                        <button id='edit-profile-button' type='submit' name='edit_profile'>Edit Profile</button>
                    </form>
                  </div>";
        } elseif ($result) {
        } else {
            echo "Error fetching employee data.";
        }
    } elseif ($_GET['page'] === 'manage_employee') {
        echo "<div id='heading'>
                <h2>Manage Employee</h2>
                <form action='' method='get'>
                    <label for='search'>Search by Employee ID:</label>
                    <input type='text' id='search' name='search_emp_id'>
                    <button type='submit' name='page' value='manage_employee'>Search</button>
                </form>
                <!-- Show All Button -->
                <form action='' method='get'>
                    <input type='hidden' name='page' value='manage_employee'>
                    <button type='submit' name='show_all'>Show All</button>
                </form>
              </div>";

        if (!empty($error_message)) {
            echo "$error_message";
        } elseif ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo "<table>
                        <tr>
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Employee Email</th>
                            <th>Employee Phone</th>
                            <th>Employee Address</th>
                            <th>Employee Username</th>
                            <th>Employee Password</th>
                            <th>Employee Status</th>
                            <th>Action</th>
                        </tr>";

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['emp_id']}</td>
                            <td>{$row['emp_fname']} {$row['emp_lname']}</td>
                            <td>{$row['emp_email']}</td>
                            <td>{$row['emp_phone']}</td>
                            <td>{$row['emp_address']}</td>
                            <td>{$row['emp_username']}</td>
                            <td>{$row['emp_password']}</td>
                            <td>{$row['emp_status']}</td>
                            <td>
                                <form action='update_emp.php' method='post'>
                                    <input type='hidden' name='emp_id' value='{$row['emp_id']}'>
                                    <button class='update' type='submit'>Update</button>
                                </form>
                                <form action='delete_emp.php' method='post' class='delete-form'>
                                    <input type='hidden' name='emp_id' value='{$row['emp_id']}'>
                                    <input type='hidden' name='page' value='manage_employee'>
                                    <button class='delete' type='submit'>Delete</button>
                                </form>
                            </td>
                        </tr>";
                }

                echo "</table>";
            } else {
                echo "<p>No results found for the specified Employee ID.</p>";
            }
        } else {
            echo "Error fetching employee data.";
        }
    } elseif ($_GET['page'] === 'edit_profile') {
        // Add the code for editing the profile here
        if (isset($_GET['edit_profile'])) {
    // Fetch the admin details for editing
    $admin_query = "SELECT * FROM admin WHERE ad_id = " . $user_data['ad_id'];
    $admin_result = mysqli_query($con, $admin_query);

    if ($admin_result) {
        if (mysqli_num_rows($admin_result) > 0) {
            $admin_data = mysqli_fetch_assoc($admin_result);
        } else {
            echo "No admin data found for editing.";
        }
    } else {
        echo "Error fetching admin data: " . mysqli_error($con);
    }
}

// Check if the form is submitted for updating admin details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_admin'])) {
    $new_username = mysqli_real_escape_string($con, $_POST['new_username']);
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);

    // Update the admin details in the database
    $update_query = "UPDATE admin SET ad_username = '$new_username', ad_password = '$new_password' WHERE ad_id = " . $user_data['ad_id'];

    if (mysqli_query($con, $update_query)) {
        echo "Admin details updated successfully!";
    } else {
        echo "Error updating admin details: " . mysqli_error($con);
    }
}

// Display the form for editing admin details
?>
<h2>Edit Profile</h2>
<?php
if (isset($admin_data)) {
    ?>
    <div class="login-container">
        <form action="" method="post">
            <label for="new_username">New Username:</label>
            <input type="text" id="new_username" name="new_username" value="<?php echo $admin_data['ad_username']; ?>" required>
            <br>
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>
            <br>
            <button type="submit" name="update_admin">Update Admin Details</button>
        </form>
        <br>
        <a href="?page=home">Go Back to Home</a>
    </div>
    <?php
} else {
    echo "Error fetching admin data for editing.";
}
    }
    ?>
    </div>
</body>
</html>

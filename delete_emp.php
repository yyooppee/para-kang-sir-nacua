<?php
    include("admin_comp/function.php");
    include("admin_comp/connection.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['emp_id'])) {
        $emp_id = $_POST['emp_id'];

        $query = "DELETE FROM employee WHERE emp_id = '$emp_id'";
        $result = mysqli_query($con, $query);

        if ($result) {
            header("Location: ad_dashboard.php?page=manage_employee");
        } else {
            echo "Error deleting employee: " . mysqli_error($con);
        }
    } else {
        echo "Invalid request.";
    }
?>
<?php

function check_login_emp($con)
{
    if(isset($_SESSION['emp_id']))
    {
        $id = $_SESSION['emp_id'];
        $query = "Select * from employee where emp_id = '$id' limit 1";
        
        $result = mysqli_query($con, $query);
        if($result && mysqli_num_rows($result) > 0)
        {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
     }
     else if(basename($_SERVER['PHP_SELF']) !== 'emp_dashboard.php') {
        header("Location: emp_dashboard.php");
        die;
     }
     //header("Location: dashboard.php");
}

?>
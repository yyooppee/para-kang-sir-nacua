<?php

function check_login($con)
{
    if (isset($_SESSION['ad_id']))
    {
        $id = $_SESSION['ad_id'];
        $query = "SELECT * FROM admin WHERE ad_id = '$id' LIMIT 1";
        
        $result = mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result) > 0)
        {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }
    else if (basename($_SERVER['PHP_SELF']) !== 'ad_dashboard.php')
    {
        header("Location: ad_dashboard.php");
        die;
    }
}
?>

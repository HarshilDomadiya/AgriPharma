<?php
include("../Functions/functions.php");

if (isset($_GET['id'])) {
    global $con;
    
    if (isset($_SESSION['phonenumber'])) {
        $sess_phone_number = $_SESSION['phonenumber'];
        $saved_id = $_GET['id'];
        
        // Delete from saveforlater
        $delete_saved = "DELETE FROM saveforlater WHERE id = '$saved_id' AND phonenumber = '$sess_phone_number'";
        $run_delete = mysqli_query($con, $delete_saved);
        
        if ($run_delete) {
            echo "<script>window.alert('Item removed from saved list!');</script>";
        }
        
        echo "<script>window.location.href='saveforlater.php';</script>";
    } else {
        echo "<script>window.alert('Please Login First!');</script>";
        echo "<script>window.location.href='../auth/BuyerLogin.php';</script>";
    }
}
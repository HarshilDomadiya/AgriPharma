<?php
include("../Functions/functions.php");

global $con;

if (isset($_SESSION['phonenumber'])) {
    $sess_phone_number = $_SESSION['phonenumber'];
    
    // Delete all saved items for this user
    $delete_all = "DELETE FROM saveforlater WHERE phonenumber = '$sess_phone_number'";
    $run_delete = mysqli_query($con, $delete_all);
    
    if ($run_delete) {
        echo "<script>window.alert('All saved items have been removed!');</script>";
    }
    
    echo "<script>window.location.href='saveforlater.php';</script>";
} else {
    echo "<script>window.alert('Please Login First!');</script>";
    echo "<script>window.location.href='../auth/BuyerLogin.php';</script>";
}
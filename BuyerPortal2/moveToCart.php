<?php
include("../Functions/functions.php");

if (isset($_POST['add_to_cart'])) {
    global $con;
    
    if (isset($_SESSION['phonenumber'])) {
        $sess_phone_number = $_SESSION['phonenumber'];
        $product_id = $_POST['product_id'];
        $saved_id = $_POST['saved_id'];
        $qty = $_POST['qty'];
        
        // Check if product is already in cart
        $check_pro = "SELECT * FROM cart WHERE phonenumber = $sess_phone_number AND product_id='$product_id'";
        $run_check = mysqli_query($con, $check_pro);
        
        if (mysqli_num_rows($run_check) > 0) {
            // Update quantity if already in cart
            $update_qty = "UPDATE cart SET qty = qty + $qty WHERE phonenumber = $sess_phone_number AND product_id='$product_id'";
            $run_update = mysqli_query($con, $update_qty);
        } else {
            // Get product price for subtotal calculation
            $get_price = "SELECT product_price FROM products WHERE product_id = '$product_id'";
            $run_price = mysqli_query($con, $get_price);
            $row_price = mysqli_fetch_array($run_price);
            $product_price = $row_price['product_price'];
            
            $subtotal = $product_price * $qty;
            
            // Add to cart
            $insert_cart = "INSERT INTO cart (product_id, phonenumber, qty, subtotal) 
                           VALUES ('$product_id', '$sess_phone_number', '$qty', '$subtotal')";
            $run_insert = mysqli_query($con, $insert_cart);
        }
        
        // Remove from saveforlater
        $delete_saved = "DELETE FROM saveforlater WHERE id = '$saved_id'";
        $run_delete = mysqli_query($con, $delete_saved);
        
        echo "<script>window.alert('Item added to cart!');</script>";
        echo "<script>window.location.href='CartPage.php';</script>";
    } else {
        echo "<script>window.alert('Please Login First!');</script>";
        echo "<script>window.location.href='../auth/BuyerLogin.php';</script>";
    }
}
<?php
include("../Functions/functions.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Profile</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <a href="https://icons8.com/icon/83325/roman-soldier"></a>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://kit.fontawesome.com/c587fc1763.js" crossorigin="anonymous"></script>
</head>
<style>
    .guard {
        width: 100%;
        text-align: center;
        border-bottom: 1px solid #ffc857;
        line-height: 0.1em;
        margin: 10px 0 20px;
        font-family: serif;
    }

    .guard span {
        background-color: white;
        padding: 0 10px;
    }

    .myfooter {
        background-color: #292b2c;
        color: goldenrod;
        margin-top: 15px;
    }

    .aligncenter {
        text-align: center;
    }

    a {
        color: goldenrod;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    nav {
        background-color: #292b2c;
    }

    .navbar-custom {
        background-color: #292b2c;
    }

    /* change the brand and text color */
    .navbar-custom .navbar-brand,
    .navbar-custom .navbar-text {
        background-color: #292b2c;
    }

    .navbar-custom .navbar-nav .nav-link {
        background-color: #292b2c;
    }

    .navbar-custom .nav-item.active .nav-link,
    .navbar-custom .nav-item:hover .nav-link {
        background-color: #292b2c;
    }


    .mybtn {
        border-color: green;
        border-style: solid;
    }


    .right {
        display: flex;
    }

    .left {
        display: none;
    }

    .cart {
        margin-right: -9px;
    }

    .profile {
        margin-right: 2px;
    }

    .login {
        margin-right: -2px;
        margin-top: 12px;
        display: none;
    }

    .searchbox {
        width: 60%;
    }

    .lists {
        display: inline-block;
    }

    .moblists {
        display: none;
    }

    .logins {
        text-align: center;
        margin-right: -30%;
        margin-left: 35%;
    }

    .profile-card {
        max-width: 800px;
        margin: 0 auto;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .profile-header {
        background-color: #292b2c;
        color: goldenrod;
        padding: 20px;
        text-align: center;
    }

    .profile-img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 5px solid goldenrod;
        margin: 0 auto 15px;
        display: block;
        background-color: #fff;
    }

    .profile-content {
        padding: 20px;
    }

    .profile-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .profile-info {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .profile-info:last-child {
        border-bottom: none;
    }

    .profile-info-label {
        font-weight: bold;
        display: inline-block;
        width: 120px;
    }

    .profile-actions {
        text-align: center;
        padding: 20px;
        background-color: #f9f9f9;
    }

    @media only screen and (min-device-width:320px) and (max-device-width:480px) {
        .profile-card {
            width: 95%;
            margin: 0 auto;
        }

        .profile-img {
            width: 120px;
            height: 120px;
        }

        .right {
            display: none;
            background-color: #ff5500;
        }

        .left {
            display: flex;
        }

        .moblogo {
            display: none;
        }

        .logins {
            text-align: center;
            margin-right: 35%;
            padding: 15px;
        }

        .searchbox {
            width: 95%;
            margin-right: 5%;
            margin-left: 0%;
        }

        .moblists {
            display: inline-block;
            text-align: center;
            width: 100%;
        }
    }
</style>

<body>

<nav class="navbar navbar-expand-xl ">
    <div class=" flex-row-reverse left ">
        <div class="p-2">
            <div class="icon2">
                <a href="CartPage.php"> <i class="fa" style="font-size:30px; color:green ;margin-top:2px;">&#61562;</i></a>
                <span id="icon" style="color:green"> <?php echo totalItems(); ?> </span>
            </div>
        </div>
        <div class="p-2 ml-5"><i class='far fa-user-circle' style='font-size:30px; color: green;margin-top:2px;'></i></div>
        <a class="float-left" href="bhome.php">
            <img src="agro.png" class="float-left mr-5 ml-0 " alt="Logo" style="height:50px;">
        </a>
    </div>
    <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"><i class="fas fa-bars p-1 " style="color:green;margin-right:-9%;font-size:28px;"></i></span>
    </button>
    <a class="float-left" href="bhome.php">
        <img src="agro.png" class="float-left mr-2 moblogo" alt="Logo" style="height:50px;">
    </a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="input-group mb-1 ml-2 searchbox">
            <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-search" style="font-size:20px;color:green; "></i></div>
            </div>
            <form action="SearchResult.php" method="get" enctype="multipart/form-data">
                <input type="text" class="form-control " id="inlineFormInputGroup" name="search" placeholder="Search for fruits,vegetables or crops " style="width:500px;">
            </form>
        </div>
        <?php
        getUsername();
        ?>
        <div class="list-group moblists">
            <?php
            if (isset($_SESSION['phonenumber'])) {
                echo "<a href='BuyerProfile.php' class='list-group-item list-group-item-action' style='background-color:#292b2c;text-align:center;color:goldenrod'>Profile</a>";
                echo "<a href= 'Transaction.php' class='list-group-item list-group-item-action' style='background-color:#292b2c;text-align:center;color:goldenrod'>Transactions</a>";
                echo "<a href='saveforlater.php' class='list-group-item list-group-item-action' style='background-color:#292b2c;text-align:center;color:goldenrod'>Save For Later</a>";
                echo "<a href='#' class='list-group-item list-group-item-action' style='background-color:#292b2c;text-align:center;color:goldenrod'>Subscriptions</a>";
                echo "<a href='farmers.php' class='list-group-item list-group-item-action' style='background-color:#292b2c;text-align:center;color:goldenrod'>Farmers</a>";
                echo "<a href='../Includes/logout.php' class='list-group-item list-group-item-action ' style='background-color:#292b2c;text-align:center;color:goldenrod'>Logout</a>";
            } else {
                echo "<a href='../auth/BuyerLogin.php' class='list-group-item list-group-item-action ' style='background-color:#292b2c;text-align:center;color:goldenrod'>Login</a>";
            }
            ?>
        </div>
    </div>

    <div class=" flex-row-reverse right ">
        <div class="p-2 cart">
            <div class="icon2">
                <a href="CartPage.php"> <i class="fa" style="font-size:30px; color:green">&#61562;</i></a>
                <span id="icon" style="color:green"> <?php echo totalItems(); ?> </span>
            </div>
        </div>
        <div class="dropdown p-2 settings ">
            <button class="btn  dropdown-toggle text-success" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Settings
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <?php
                if (isset($_SESSION['phonenumber'])) {
                    echo "<a href='BuyerProfile2.php' class='dropdown-item  ' style='padding-right:-20px;'>Profile</a>";
                    echo "<a href='Transaction.php' class='dropdown-item ' style='padding-right:-20px;'>Transactions</a>";
                    echo "<a href='#' class='dropdown-item'  style='padding-right:-20px;'>Subscriptions</a>";
                    echo "<a href='saveforlater.php' class='dropdown-item' style='padding-right:-20px;'>Save For Later</a>";
                    echo "<a href='farmers.php' class='dropdown-item' style='padding-right:-20px;' >Farmers</a>";
                    echo "<a href='../Includes/logout.php' class='dropdown-item ' style='padding-right:-20px;'>Logout</a>";
                } else {
                    echo "<a href='../auth/BuyerLogin.php' class='dropdown-item ' style='padding-right:-20px;'>Login</a>";
                }
                ?>
            </div>
        </div>
        <div class="text-success login">Login</div>
    </div>
</nav>

<div class="text-center container">
    <br>
    <b>
        <h1 class="guard text-center" style="font-family: 'Times New Roman', Times, serif;"><span><b>Farmer Profile</b></span></h1>
    </b>
</div>

<div class="container mt-5 mb-5">
    <?php
    if (isset($_GET['id'])) {
        $farmer_id = $_GET['id'];
        
        // Fetch farmer details from database
        global $con;
        $query = "SELECT * FROM farmerregistration WHERE farmer_id = $farmer_id";
        $result = mysqli_query($con, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $farmer_name = $row['farmer_name'];
            $farmer_phone = $row['farmer_phone'];
            $farmer_address = $row['farmer_address'];
            $farmer_state = $row['farmer_state'];
            $farmer_district = $row['farmer_district'];
            
            // Determine which icon to use based on farmer_id
            $icon_num = ($farmer_id % 2) + 1;
            $icon = "iconbig" . $icon_num . ".png";
            
            echo "
            <div class='profile-card'>
                <div class='profile-header'>
                    <img src='$icon' class='profile-img' alt='Farmer Profile'>
                    <h2>$farmer_name</h2>
                    <p>Farmer ID: $farmer_id</p>
                </div>
                
                <div class='profile-content'>
                    <div class='profile-info'>
                        <span class='profile-info-label'>Full Name:</span>
                        <span>$farmer_name</span>
                    </div>
                    
                    <div class='profile-info'>
                        <span class='profile-info-label'>Phone Number:</span>
                        <span>$farmer_phone</span>
                    </div>
                    
                    <div class='profile-info'>
                        <span class='profile-info-label'>Address:</span>
                        <span>$farmer_address</span>
                    </div>
                    
                    <div class='profile-info'>
                        <span class='profile-info-label'>District:</span>
                        <span>$farmer_district</span>
                    </div>
                    
                    <div class='profile-info'>
                        <span class='profile-info-label'>State:</span>
                        <span>$farmer_state</span>
                    </div>
                </div>
                
                <div class='profile-actions'>
                    <a href='farmers.php' class='btn btn-warning border-dark'><i class='fas fa-arrow-left'></i> Back to Farmers List</a>
                    <a href='#' class='btn btn-success border-dark ml-2'><i class='fas fa-envelope'></i> Contact Farmer</a>
                </div>
            </div>
            ";
            
            // Display farmer's products
            echo "
            <div class='mt-5'>
                <h3 class='text-center mb-4'>Products by $farmer_name</h3>
                <div class='row'>
            ";
            
            $products_query = "SELECT * FROM products WHERE farmer_fk = $farmer_id";
            $products_result = mysqli_query($con, $products_query);
            
            if (mysqli_num_rows($products_result) > 0) {
                while ($product = mysqli_fetch_assoc($products_result)) {
                    $product_id = $product['product_id'];
                    $product_title = $product['product_title'];
                    $product_image = $product['product_image'];
                    $product_price = $product['product_price'];
                    
                    echo "
                    <div class='col-md-4 mb-4'>
                        <div class='card h-100'>
                            <img src='../Admin/product_images/$product_image' class='card-img-top' alt='$product_title' style='height: 200px; object-fit: contain;'>
                            <div class='card-body'>
                                <h5 class='card-title'>$product_title</h5>
                                <p class='card-text'>Price: ₹$product_price per kg</p>
                                <a href='productdetails.php?id=$product_id' class='btn btn-warning'>View Details</a>
                            </div>
                        </div>
                    </div>
                    ";
                }
            } else {
                echo "<div class='col-12 text-center'><p>No products available from this farmer</p></div>";
            }
            
            echo "
                </div>
            </div>
            ";
            
        } else {
            echo "<div class='alert alert-danger text-center'>Farmer not found</div>";
        }
    } else {
        echo "<div class='alert alert-danger text-center'>Invalid request. Please select a farmer from the list.</div>";
    }
    ?>
</div>

<section id="footer" class="myfooter">
    <div class="container">
        <div class="row text-center text-xs-center text-sm-left text-md-left">
            <div class="col aligncenter">
                <p>Payment Option</p>
                <img src="../Images/Website/paytm1.jpg" alt="paytm">
                <img src="../Images/Website/cod.jpg" alt="paytm" style="height:37px">
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-5">
                <ul class="list-unstyled list-inline social text-center">
                    <li class="list-inline-item"><a href="javascript:void();"><i class="fa fa-facebook"></i></a></li>
                    <li class="list-inline-item"><a href="javascript:void();"><i class="fa fa-twitter"></i></a></li>
                    <li class="list-inline-item"><a href="javascript:void();"><i class="fa fa-instagram"></i></a></li>
                    <li class="list-inline-item"><a href="javascript:void();"><i class="fa fa-google-plus"></i></a></li>
                    <li class="list-inline-item"><a href="javascript:void();" target="_blank"><i class="fa fa-envelope"></i></a></li>
                </ul>
            </div>
            </hr>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-2 text-center">
                <p><u><a href="https://www.AgriPharma.com/">AgriPharma Corporation</a></u> is a Multitrading Company for farmers ang traders</p>
                <p class="h6">Copy All right Reversed.<a class="text-green ml-2" href="https://www.google.com" target="_blank">Agrotech</a></p>
            </div>
            </hr>
        </div>
    </div>
</section>

</body>
</html>
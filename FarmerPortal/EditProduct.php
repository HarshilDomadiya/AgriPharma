<?php
include("../Includes/db.php");
session_start();

// Check if user is logged in
if(!isset($_SESSION['phonenumber'])) {
    echo "<script>alert('Please login first!')</script>";
    echo "<script>window.open('../auth/FarmerLogin.php','_self')</script>";
    exit();
}

$sessphonenumber = $_SESSION['phonenumber'];

// Get farmer details
$get_farmer = "SELECT * FROM farmerregistration WHERE farmer_phone = '$sessphonenumber'";
$run_farmer = mysqli_query($con, $get_farmer);
$row_farmer = mysqli_fetch_array($run_farmer);
$farmer_id = $row_farmer['farmer_id'];
$farmer_name = $row_farmer['farmer_name'];

// Process form submission for inserting new product
if(isset($_POST['insert_pro'])) {
    $product_title = mysqli_real_escape_string($con, $_POST['product_title']);
    $product_cat = mysqli_real_escape_string($con, $_POST['product_cat']);
    $product_type = mysqli_real_escape_string($con, $_POST['product_type']);
    $product_stock = mysqli_real_escape_string($con, $_POST['product_stock']);
    $product_price = mysqli_real_escape_string($con, $_POST['product_price']);
    $product_expiry = mysqli_real_escape_string($con, $_POST['product_expiry']);
    $product_desc = mysqli_real_escape_string($con, $_POST['product_desc']);
    $product_keywords = mysqli_real_escape_string($con, $_POST['product_keywords']);
    $product_delivery = mysqli_real_escape_string($con, $_POST['product_delivery']);
    
    // Image handling
    $product_image = "";
    if(isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $product_image = $_FILES['product_image']['name'];
        $temp_name = $_FILES['product_image']['tmp_name'];
        
        // Generate unique name to prevent overwriting
        $product_image = time() . '_' . $product_image;
        
        // Move uploaded file
        if(!move_uploaded_file($temp_name, "../product_images/$product_image")) {
            echo "<script>alert('Failed to upload image. Please try again.')</script>";
            $product_image = "";
        }
    }
    
    $insert_product = "INSERT INTO products (farmer_fk, product_title, product_cat, product_type, product_image, product_stock, product_price, product_expiry, product_desc, product_keywords, product_delivery) 
                      VALUES ('$farmer_id', '$product_title', '$product_cat', '$product_type', '$product_image', '$product_stock', '$product_price', '$product_expiry', '$product_desc', '$product_keywords', '$product_delivery')";
    
    $run_product = mysqli_query($con, $insert_product);
    
    if($run_product) {
        echo "<script>alert('Product has been added successfully!')</script>";
        echo "<script>window.open('EditProduct.php','_self')</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "')</script>";
    }
}

// Process form submission for updating product quantity
if(isset($_POST['update_quantity'])) {
    $product_id = mysqli_real_escape_string($con, $_POST['product_id']);
    $quantity_change = floatval($_POST['quantity_change']);
    $action = mysqli_real_escape_string($con, $_POST['action']);
    
    // Verify product belongs to this farmer
    $check_product = "SELECT p.* FROM products p 
                     JOIN farmerregistration f ON p.farmer_fk = f.farmer_id 
                     WHERE p.product_id = '$product_id' AND f.farmer_phone = '$sessphonenumber'";
    $run_check = mysqli_query($con, $check_product);
    
    if(mysqli_num_rows($run_check) > 0) {
        $row_product = mysqli_fetch_array($run_check);
        $current_stock = $row_product['product_stock'];
        
        // Calculate new stock based on action
        if($action == 'increase') {
            $new_stock = $current_stock + $quantity_change;
        } else {
            $new_stock = $current_stock - $quantity_change;
            // Prevent negative stock
            if($new_stock < 0) {
                $new_stock = 0;
            }
        }
        
        // Update the stock in database
        $update_stock = "UPDATE products SET product_stock = $new_stock WHERE product_id = '$product_id'";
        $run_update = mysqli_query($con, $update_stock);
        
        if($run_update) {
            echo "<script>alert('Product quantity has been updated successfully!')</script>";
            echo "<script>window.open('EditProduct.php','_self')</script>";
        } else {
            echo "<script>alert('Error updating quantity: " . mysqli_error($con) . "')</script>";
        }
    } else {
        echo "<script>alert('You do not have permission to update this product!')</script>";
    }
}

// Process form submission for deleting product
if(isset($_POST['delete_product'])) {
    $product_id = mysqli_real_escape_string($con, $_POST['product_id']);
    
    // Verify product belongs to this farmer
    $check_product = "SELECT p.* FROM products p 
                     JOIN farmerregistration f ON p.farmer_fk = f.farmer_id 
                     WHERE p.product_id = '$product_id' AND f.farmer_phone = '$sessphonenumber'";
    $run_check = mysqli_query($con, $check_product);
    
    if(mysqli_num_rows($run_check) > 0) {
        // Delete product image first
        $row_product = mysqli_fetch_array($run_check);
        $product_image = $row_product['product_image'];
        
        if(!empty($product_image) && file_exists("../product_images/$product_image")) {
            unlink("../product_images/$product_image");
        }
        
        // Delete product from database
        $delete_product = "DELETE FROM products WHERE product_id = '$product_id'";
        $run_delete = mysqli_query($con, $delete_product);
        
        if($run_delete) {
            echo "<script>alert('Product has been deleted successfully!')</script>";
            echo "<script>window.open('EditProduct.php','_self')</script>";
        } else {
            echo "<script>alert('Error deleting product: " . mysqli_error($con) . "')</script>";
        }
    } else {
        echo "<script>alert('You do not have permission to delete this product!')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../portal_files/bootstrap.min.css">

    <title>Farmer - Product Management</title>
    <style>
        @import url(https://fonts.googleapis.com/css?family=Raleway:300,400,600);

        body {
            margin: 0;
            font-size: .9rem;
            font-weight: 400;
            line-height: 1.6;
            color: #212529;
            text-align: left;
            background-color: #f5f8fa;
        }

        .my-form,
        .login-form {
            font-family: Raleway, sans-serif;
        }

        .my-form {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .my-form .row {
            margin-left: 0;
            margin-right: 0;
        }

        .login-form {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .login-form .row {
            margin-left: 0;
            margin-right: 0;
        }

        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
            background-color: #28a745;
            color: white;
        }

        .nav-tabs .nav-link {
            color: #28a745;
            font-weight: 600;
        }

        .product-list {
            margin-top: 20px;
        }

        .product-item {
            background: #fff;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: relative;
        }

        .product-item:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .product-title {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
            color: #28a745;
        }

        .product-details {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .product-details > div {
            margin-bottom: 8px;
            flex-basis: 30%;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .quantity-controls .input-group {
            margin-bottom: 10px;
        }

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .product-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        .no-products {
            padding: 30px;
            text-align: center;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .stat-card {
            flex: 1;
            background: #fff;
            border-radius: 5px;
            padding: 15px;
            margin: 0 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card:first-child {
            margin-left: 0;
        }

        .stat-card:last-child {
            margin-right: 0;
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
        }

        .search-filter {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .search-filter .form-control {
            max-width: 300px;
        }

        @media (max-width: 768px) {
            .product-details > div {
                flex-basis: 100%;
            }
            
            .product-image {
                position: static;
                margin-bottom: 15px;
            }
            
            .stats-container {
                flex-direction: column;
            }
            
            .stat-card {
                margin: 5px 0;
            }
            
            .search-filter {
                flex-direction: column;
            }
            
            .search-filter .form-control {
                max-width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <main class="my-form">
            <div class="cotainer">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h4 class="mb-0">Welcome, <?php echo $farmer_name; ?></h4>
                            </div>
                            <div class="card-body">
                                <?php
                                // Get product statistics
                                $total_products_query = "SELECT COUNT(*) as total FROM products p 
                                                        JOIN farmerregistration f ON p.farmer_fk = f.farmer_id 
                                                        WHERE f.farmer_phone = '$sessphonenumber'";
                                $run_total = mysqli_query($con, $total_products_query);
                                $total_products = mysqli_fetch_array($run_total)['total'];
                                
                                $total_stock_query = "SELECT SUM(product_stock) as total_stock FROM products p 
                                                     JOIN farmerregistration f ON p.farmer_fk = f.farmer_id 
                                                     WHERE f.farmer_phone = '$sessphonenumber'";
                                $run_stock = mysqli_query($con, $total_stock_query);
                                $total_stock = mysqli_fetch_array($run_stock)['total_stock'];
                                if(!$total_stock) $total_stock = 0;
                                
                                $total_value_query = "SELECT SUM(product_stock * product_price) as total_value FROM products p 
                                                     JOIN farmerregistration f ON p.farmer_fk = f.farmer_id 
                                                     WHERE f.farmer_phone = '$sessphonenumber'";
                                $run_value = mysqli_query($con, $total_value_query);
                                $total_value = mysqli_fetch_array($run_value)['total_value'];
                                if(!$total_value) $total_value = 0;
                                ?>
                                
                                <div class="stats-container">
                                    <div class="stat-card">
                                        <div class="stat-value"><?php echo $total_products; ?></div>
                                        <div class="stat-label">Total Products</div>
                                    </div>
                                    <div class="stat-card">
                                        <div class="stat-value"><?php echo $total_stock; ?> kg</div>
                                        <div class="stat-label">Total Stock</div>
                                    </div>
                                    <div class="stat-card">
                                        <div class="stat-value">₹<?php echo number_format($total_value, 2); ?></div>
                                        <div class="stat-label">Inventory Value</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="products-tab" data-toggle="tab" href="#products" role="tab" aria-controls="products" aria-selected="true">
                                    <i class="fas fa-list"></i> My Products
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="add-tab" data-toggle="tab" href="#add" role="tab" aria-controls="add" aria-selected="false">
                                    <i class="fas fa-plus-circle"></i> Add New Product
                                </a>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="myTabContent">
                            <!-- My Products Tab -->
                            <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="products-tab">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="text-center font-weight-bold">Manage Your Products <i class="fas fa-box"></i></h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="search-filter">
                                            <input type="text" id="productSearch" class="form-control" placeholder="Search products...">
                                            <select id="categoryFilter" class="form-control">
                                                <option value="">All Categories</option>
                                                <?php
                                                $get_cats = "SELECT * FROM categories";
                                                $run_cats = mysqli_query($con, $get_cats);
                                                while ($row_cats = mysqli_fetch_array($run_cats)) {
                                                    $cat_id = $row_cats['cat_id'];
                                                    $cat_title = $row_cats['cat_title'];
                                                    echo "<option value='$cat_id'>$cat_title</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        <div class="product-list" id="productList">
                                            <?php
                                            // Get all products for this farmer
                                            $get_products = "SELECT p.*, c.cat_title FROM products p 
                                                           JOIN farmerregistration f ON p.farmer_fk = f.farmer_id 
                                                           LEFT JOIN categories c ON p.product_cat = c.cat_id
                                                           WHERE f.farmer_phone = '$sessphonenumber' 
                                                           ORDER BY p.product_title";
                                            $run_products = mysqli_query($con, $get_products);
                                            
                                            if(mysqli_num_rows($run_products) > 0) {
                                                while($row_products = mysqli_fetch_array($run_products)) {
                                                    $product_id = $row_products['product_id'];
                                                    $product_title = $row_products['product_title'];
                                                    $product_stock = $row_products['product_stock'];
                                                    $product_price = $row_products['product_price'];
                                                    $product_type = $row_products['product_type'];
                                                    $product_expiry = $row_products['product_expiry'];
                                                    $product_image = $row_products['product_image'];
                                                    $product_cat = $row_products['product_cat'];
                                                    $cat_title = $row_products['cat_title'];
                                                    $product_desc = $row_products['product_desc'];
                                                    $product_delivery = $row_products['product_delivery'];
                                                    
                                                    // Calculate total value of this product
                                                    $product_value = $product_stock * $product_price;
                                            ?>
                                            <div class="product-item" data-category="<?php echo $product_cat; ?>">
                                                <?php if(!empty($product_image) && file_exists("../product_images/$product_image")): ?>
                                                    <img src="../product_images/<?php echo $product_image; ?>" alt="<?php echo $product_title; ?>" class="product-image">
                                                <?php endif; ?>
                                                
                                                <div class="product-title"><?php echo $product_title; ?> (<?php echo $product_type; ?>)</div>
                                                
                                                <div class="product-details">
                                                    <div>
                                                        <strong>Category:</strong> <?php echo $cat_title; ?>
                                                    </div>
                                                    <div>
                                                        <strong>Current Stock:</strong> <?php echo $product_stock; ?> kg
                                                    </div>
                                                    <div>
                                                        <strong>Price:</strong> ₹<?php echo $product_price; ?> per kg
                                                    </div>
                                                    <div>
                                                        <strong>Total Value:</strong> ₹<?php echo number_format($product_value, 2); ?>
                                                    </div>
                                                    <div>
                                                        <strong>Expiry:</strong> <?php echo date('d M Y', strtotime($product_expiry)); ?>
                                                    </div>
                                                    <div>
                                                        <strong>Delivery:</strong> <?php echo ($product_delivery == 'yes') ? 'Available' : 'Not Available'; ?>
                                                    </div>
                                                </div>
                                                
                                                <form action="EditProduct.php" method="post" class="quantity-update-form">
                                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                                    <div class="quantity-controls">
                                                        <div class="input-group mr-3" style="max-width: 200px;">
                                                            <div class="input-group-prepend">
                                                                <label class="input-group-text" for="action_<?php echo $product_id; ?>">Action:</label>
                                                            </div>
                                                            <select class="custom-select" id="action_<?php echo $product_id; ?>" name="action" required>
                                                                <option value="increase">Increase Stock</option>
                                                                <option value="decrease">Decrease Stock</option>
                                                            </select>
                                                        </div>
                                                        <div class="input-group" style="max-width: 250px;">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Quantity:</span>
                                                            </div>
                                                            <input type="number" class="form-control" name="quantity_change" min="0.1" step="0.1" required>
                                                            <div class="input-group-append">
                                                                <button type="submit" name="update_quantity" class="btn btn-primary">
                                                                    <i class="fas fa-sync-alt"></i> Update
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="product-actions">
                                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#productDetails_<?php echo $product_id; ?>">
                                                            <i class="fas fa-info-circle"></i> Details
                                                        </button>
                                                        
                                                        <button type="submit" name="delete_product" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">
                                                            <i class="fas fa-trash-alt"></i> Delete
                                                        </button>
                                                    </div>
                                                </form>
                                                
                                                <!-- Product Details Modal -->
                                                <div class="modal fade" id="productDetails_<?php echo $product_id; ?>" tabindex="-1" role="dialog" aria-labelledby="productDetailsLabel_<?php echo $product_id; ?>" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-success text-white">
                                                                <h5 class="modal-title" id="productDetailsLabel_<?php echo $product_id; ?>"><?php echo $product_title; ?> Details</h5>
                                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <?php if(!empty($product_image) && file_exists("../product_images/$product_image")): ?>
                                                                    <img src="../product_images/<?php echo $product_image; ?>" alt="<?php echo $product_title; ?>" class="img-fluid mb-3" style="max-height: 200px; display: block; margin: 0 auto;">
                                                                <?php endif; ?>
                                                                
                                                                <table class="table table-bordered">
                                                                    <tr>
                                                                        <th>Product ID</th>
                                                                        <td><?php echo $product_id; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Title</th>
                                                                        <td><?php echo $product_title; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Category</th>
                                                                        <td><?php echo $cat_title; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Type</th>
                                                                        <td><?php echo $product_type; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Stock</th>
                                                                        <td><?php echo $product_stock; ?> kg</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Price</th>
                                                                        <td>₹<?php echo $product_price; ?> per kg</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Expiry</th>
                                                                        <td><?php echo date('d M Y', strtotime($product_expiry)); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Delivery</th>
                                                                        <td><?php echo ($product_delivery == 'yes') ? 'Available' : 'Not Available'; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Description</th>
                                                                        <td><?php echo $product_desc; ?></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                                }
                                            } else {
                                                echo '<div class="no-products">
                                                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                                        <h5>You haven\'t added any products yet.</h5>
                                                        <p>Go to the \'Add New Product\' tab to add your first product.</p>
                                                        <button class="btn btn-success" onclick="document.getElementById(\'add-tab\').click();">
                                                            <i class="fas fa-plus-circle"></i> Add New Product
                                                        </button>
                                                      </div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Add New Product Tab -->
                            <div class="tab-pane fade" id="add" role="tabpanel" aria-labelledby="add-tab">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="text-center font-weight-bold">Insert Your New Product <i class="fas fa-leaf"></i></h4>
                                    </div>
                                    <div class="card-body">
                                        <form name="my-form" action="EditProduct.php" method="post" enctype="multipart/form-data">
                                            <div class="form-group row">
                                                <label for="product_title" class="col-md-4 col-form-label text-md-right text-center font-weight-bolder">Product Title:</label>
                                                <div class="col-md-6">
                                                    <input type="text" id="product_title" class="form-control" name="product_title" placeholder="Enter product title" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="product_stock" class="col-md-4 col-form-label text-md-right text-center font-weight-bolder">Product Stock:(In kg)</label>
                                                <div class="col-md-6">
                                                    <input type="number" id="product_stock" class="form-control" name="product_stock" placeholder="Enter product stock" min="0.1" step="0.1" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="product_cat" class="col-md-4 col-form-label text-md-right text-center font-weight-bolder">Product Categories:</label>
                                                <div class="col-md-6">
                                                    <select name="product_cat" id="product_cat" required class="form-control">
                                                        <option value="">Select a Category</option>
                                                        <?php
                                                        $get_cats = "select * from categories";
                                                        $run_cats =  mysqli_query($con, $get_cats);
                                                        while ($row_cats = mysqli_fetch_array($run_cats)) {
                                                            $cat_id = $row_cats['cat_id'];
                                                            $cat_title = $row_cats['cat_title'];
                                                            echo "<option value='$cat_id'>$cat_title</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="product_type" class="col-md-4 col-form-label text-md-right text-center font-weight-bolder">Product type :</label>
                                                <div class="col-md-6">
                                                    <input type="text" id="product_type" class="form-control" name="product_type" placeholder="Example: potato, apple, etc." required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="product_expiry" class="col-md-4 col-form-label text-md-right text-center font-weight-bolder">Product Expiry :</label>
                                                <div class="col-md-6">
                                                    <input id="product_expiry" class="form-control" type="date" name="product_expiry" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="product_image" class="col-md-4 col-form-label text-md-right text-center font-weight-bolder">Product Image :</label>
                                                <div class="col-md-6">
                                                    <input id="product_image" type="file" name="product_image" class="form-control-file">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="product_price" class="col-md-4 col-form-label text-md-right text-center font-weight-bolder">Product MRP : (Per kg)</label>
                                                <div class="col-md-6">
                                                    <input type="number" id="product_price" class="form-control" name="product_price" placeholder="Enter Product price" min="0.01" step="0.01" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="product_desc" class="col-md-4 col-form-label text-md-right text-center font-weight-bolder"> Product Description:</label>
                                                <div class="col-md-6">
                                                    <textarea name="product_desc" id="product_desc" class="form-control" rows="3" required></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="product_keywords" class="col-md-4 col-form-label text-md-right text-center font-weight-bolder">Product Keywords:</label>
                                                <div class="col-md-6">
                                                    <input type="text" id="product_keywords" class="form-control" name="product_keywords" placeholder="Example: best potatoes, organic" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="product_delivery" class="col-md-4 col-form-label text-md-right text-center font-weight-bolder">Delivery :</label>
                                                <div class="col-md-6">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="delivery_yes" name="product_delivery" value="yes" required>
                                                        <label class="form-check-label" for="delivery_yes">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="delivery_no" name="product_delivery" value="no">
                                                        <label class="form-check-label" for="delivery_no">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row mb-0">
                                                <div class="col-md-6 offset-md-4">
                                                    <button type="submit" class="btn btn-success" name="insert_pro">
                                                        <i class="fas fa-plus-circle"></i> Add Product
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    
    <script>
        // Product search functionality
        $(document).ready(function() {
            $("#productSearch").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#productList .product-item").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            
            // Category filter
            $("#categoryFilter").on("change", function() {
                var value = $(this).val();
                if (value === "") {
                    $("#productList .product-item").show();
                } else {
                    $("#productList .product-item").hide();
                    $("#productList .product-item[data-category='" + value + "']").show();
                }
            });
        });
    </script>
</body>
</html>

<?php
    session_start();
    $user_id = $_SESSION['user_id'];
    if(isset($_SESSION['emailaddress']) || isset($_SESSION['firstname']) || isset($_SESSION['lastname'])) {
        $emailaddress = $_SESSION['emailaddress'];
        $firstname = $_SESSION['firstname'];
        $lastname = $_SESSION['lastname'];
    }
    require_once('partials/_dbconnection.php');
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : 0;
    if($product_id) {
        if(!isset($_SESSION['login']) || $_SESSION['login'] != true){
            redirect("login.php");
            exit;
          } else {
            $cart_token = isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : '';
            if (!$cart_token) {
                $cart_token = random_string();
                setcookie($cookie_name, $cart_token, time() + (86400 * 30),"/");
            }
            // GET CART DETAILS
            $existsSql = "SELECT cart_id FROM `cart` WHERE user_id = $user_id LIMIT 1";
            $result = mysqli_query($conn, $existsSql);
            $cart_row = mysqli_fetch_assoc($result);
            $numExistsRows = mysqli_num_rows($result);
            $cart_id = $cart_row['cart_id'];

            // GET PRODUCT DETAILS
            $query = "SELECT product_name, price, discount FROM products WHERE product_id = $product_id";
            $result1 = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result1);
            $product_name = $row['product_name'];
            $price = $row['price'];
            $discount = $row['discount'];
            
            // GET user_id USUING EMAIL ADDRESS
            $quary1 = "SELECT user_id FROM users WHERE emailaddress = '$emailaddress'";
            $result2 = mysqli_query($conn, $quary1);
            $row1 = mysqli_fetch_assoc($result2);
            $user_id = $row1['user_id'];

            if($numExistsRows > 0){
                // CHECK IF PRODUCT ALREADY EXISTS IN CART ITEMS
                $query1 = "SELECT product_id FROM `cart_items` WHERE product_id = $product_id and cart_id = $cart_id";
                $result2 = mysqli_query($conn, $query1);
                $row1 = mysqli_num_rows($result2);
                if ($row1 > 0) {
                    $sql = "UPDATE cart_items SET quantity = quantity + 1, total = total + price WHERE product_id = $product_id and cart_id =$cart_id";
                    mysqli_query($conn, $sql);
                }  else{ 
                    $sql = "INSERT INTO cart_items (`cart_id`, `product_id`, `product_name`,`price`,`discount`,`total`) VALUES ($cart_id,$product_id,'".$product_name."',$price,$discount,$price)";
                    mysqli_query($conn, $sql);
                }
            } else {
                $sql = "INSERT INTO cart (`cart_token`,`user_id`,`emailaddress`) VALUES ('".$cart_token."',$user_id,'$emailaddress')";
                mysqli_query($conn, $sql);
                $cart_id = $conn->insert_id;
                $sql = "INSERT INTO cart_items (`cart_id`, `product_id`, `product_name`,`price`,`discount`,`total`) VALUES ($cart_id,$product_id,'$product_name',$price,$discount,$price)";
                mysqli_query($conn, $sql);
            }
            redirect("index.php");
        }
    } else {
        redirect('index.php');
    }   
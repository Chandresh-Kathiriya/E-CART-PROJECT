<?php
session_start();
require_once '_dbconnection.php';
if(!isset($_SESSION['login']) || $_SESSION['login'] != true){
  redirect("login.php");
  exit;
}

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $encrypt_product_id = $_GET['pdid'];
    $product_id = decrypt($encrypt_product_id);

    $getCartid = "SELECT 
                        cart_id
                  FROM cart
                  WHERE user_id = $user_id";
    $result = mysqli_query($conn, $getCartid);
    $row = mysqli_fetch_array($result);
    $cart_id = $row['cart_id'];
    
    $sql1 = "SELECT `quantity` FROM cart_items WHERE cart_id = $cart_id AND product_id = $product_id";
    $result1 = mysqli_query($conn, $sql1);
    $row1 = mysqli_fetch_array($result1);
    $quantity1 = $row1['quantity'];
    if($quantity1 == 1){
        // $sql4 = "DELETE FROM `cart_items` WHERE product_id = $product_id AND cart_id = $cart_id";
        // $result4 = mysqli_query($conn, $sql4);
        redirect("/e_cart/cart.php");
    } else {
        $decreseQuantity = "UPDATE `cart_items` SET `quantity`= quantity - 1, `total` = total - price WHERE cart_id = '$cart_id' AND product_id = '$product_id'";
        $result = mysqli_query($conn, $decreseQuantity);
        redirect("/e_cart/cart.php");
    }
}
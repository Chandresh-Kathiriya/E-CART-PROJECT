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

    $getCartid = "SELECT c.cart_id
                  FROM cart c
                  WHERE user_id = $user_id";
    $result = mysqli_query($conn, $getCartid);
    $row = mysqli_fetch_array($result);
    $cart_id = $row['cart_id'];

    $increseQuantity = "UPDATE `cart_items` SET `quantity`= quantity + 1, `total` = total + price WHERE cart_id = '$cart_id' AND product_id = '$product_id'";
        $result = mysqli_query($conn, $increseQuantity);
        redirect("/e_cart/cart.php");

}
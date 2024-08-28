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

  $sql = "SELECT `cart_id` FROM cart WHERE user_id = $user_id";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);
  $cart_id = $row['cart_id'];

  $sql1 = "SELECT `product_id` FROM `cart_items` WHERE cart_id = $cart_id";
  $result1 = mysqli_query($conn, $sql1);
  $numbersofproduct = mysqli_num_rows($result1);

  if ($numbersofproduct == 1) {
      $sql2 = "DELETE FROM `cart_items` WHERE product_id = $product_id";
      $result2 = mysqli_query($conn, $sql2);
      $sql3 = "DELETE FROM `cart` WHERE cart_id = $cart_id";
      $result3 = mysqli_query($conn, $sql3);
      redirect("/e_cart/cart.php");
  } else {
      $sql4 = "DELETE FROM `cart_items` WHERE product_id = $product_id";
      $result4 = mysqli_query($conn, $sql4);
      redirect("/e_cart/cart.php");
  }
}
    
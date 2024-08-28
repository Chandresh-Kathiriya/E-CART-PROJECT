<?php
session_start();
require_once 'partials/_dbconnection.php';
if(!isset($_SESSION['login']) || $_SESSION['login'] != true){
    redirect("login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $assets_url.'css/bootstrap.min.css' ?>">
    <link rel="stylesheet" href="<?= $assets_url.'css/bootstrap.all.min.css' ?>"/>
    <link rel="stylesheet" href="<?= $assets_url.'css/mainbody.css' ?>">
    <link rel="stylesheet" href="<?= $assets_url.'css/dropdown.css'?>"> 
    <title>MY CART</title>
</head>
<body>
    <?php require 'partials/_navbar.php'?>
    <?php require 'partials/_cart.php'?>
    <script>
         function myFunction() {
          document.getElementById("myDropdown").classList.toggle("show");
          }
      </script>
</body>
</html>
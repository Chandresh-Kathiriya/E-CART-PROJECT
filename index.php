<?php
session_start();
require_once 'partials/_dbconnection.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= $assets_url.'css/bootstrap.min.css'?>">
    <link rel="stylesheet" href="<?= $assets_url.'css/bootstrap.all.min.css'?>" />
    <link rel="stylesheet" href="<?= $assets_url.'css/mainbody.css'?>"> 
    <link rel="stylesheet" href="<?= $assets_url.'css/dropdown.css'?>"> 
    <link rel="stylesheet" href="<?= $assets_url.'css/font.awesome.min.css'?>">

    <title>E CART</title>
    <!-- For add logo with title -->
    <!-- <link rel="icon" href="/E_CART/assets/uploads/logo/logo2.png" type="image/icon type"> -->
  </head>
  <body>
      <?php require 'partials/_navbar.php'?>
      <?php require 'partials/_crousal.php'?>
      <?php require 'partials/_mainbody.php'?>
      <?php require 'partials/_footer.php'?>
      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="<?= $assets_url.'js/slim.min.js' ?>"></script>
      <script src="<?= $assets_url.'js/bootstrap.min.js'?>"></script>
      <script src="<?= $assets_url.'js/jquery.mask.min.js'?>"></script>
      <script src="<?= $assets_url.'js/custom.js?t='.time() ?>"></script>
  </body>
</html>
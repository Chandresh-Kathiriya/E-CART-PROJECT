<?php
session_start();
require_once 'partials/_dbconnection.php';
if(!isset($_SESSION['login']) || $_SESSION['login'] != true){
  redirect("login.php");
  exit;
}
if(isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $getuserdetails = "SELECT `firstname`,`lastname`,`emailaddress` FROM `users` WHERE user_id = $user_id";
  $result = mysqli_query($conn, $getuserdetails);
  $row = mysqli_fetch_array($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= $assets_url.'css/bootstrap.min.css'?>">
    <link rel="stylesheet" href="<?= $assets_url.'css/bootstrap.all.min.css'?>" />
    <link rel="stylesheet" href="<?= $assets_url.'css/mainbody.css'?>">
    <link rel="stylesheet" href="<?= $assets_url.'css/checkout.css'?>">
    <link rel="stylesheet" href="<?= $assets_url.'css/dropdown.css'?>"> 
    <link rel="stylesheet" href="<?= $assets_url.'css/font.awesome.min.css'?>">
    <title>My Account</title>
</head>
<body>
      <?php require 'partials/_navbar.php'?>
        <div class="col-25 container my-5 stylefont" style="text-align: center;">
            <h1 class="py-2"><strong>Personal Details</strong></h1>
        </div>
        <div class="container my-3" id="container">
            <label for="firstname">First Name :</label>
            <div class="stylefont">
                <h4><strong><?php echo $row['firstname']?></strong></h4></div>
            </div>
        </div>
        <div class="container my-3">
            <label for="lastname">Last Name :</label>
            <div class="stylefont">
                <h4><strong><?php echo $row['lastname']?></strong></h4></div>
            </div>
        </div>
        <div class="my-3 container">
            <label for="emailAddress">Email address :</label>
            <div class="stylefont">
                <h4><strong><?php echo $row['emailaddress']?></strong></h4></div>
        </div>
      <div class="col">
      <div class="container my-3" style="display: flex;">
        <div class=" col-50 p-3" >
          <a href="update_profile.php"><button class="btn btn-secondary ">Update Profile</button></a>
        </div>
      </div>
      <div class="container my-3" style="display: flex;">
        <div class=" col-50 p-3" >
          <a href="myorders.php"><button class="btn btn-secondary ">My Orders</button></a>
        </div>
        <div class=" col-50 p-3" >
          <a href="cart.php"><button class="btn btn-secondary ">My Cart</button></a>
        </div>
        <div class=" col-50 p-3" >
          <a href="index.php"><button class="btn btn-secondary ">Home Page</button></a>
        </div>
        <div class=" col-50 p-3" >
          <a href="logout.php"><button class="btn btn-secondary ">Log Out</button></a>
        </div>
      </div>
    </div>
      <?php require 'partials/_footer.php'?>
      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="<?= $assets_url.'js/slim.min.js' ?>"></script>
      <script src="<?= $assets_url.'js/bootstrap.min.js'?>"></script>
      <script src="<?= $assets_url.'js/jquery.mask.min.js'?>"></script>
      <script src="<?= $assets_url.'js/custom.js?t='.time() ?>"></script>
      
</body>
</html>
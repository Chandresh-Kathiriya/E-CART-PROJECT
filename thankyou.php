<?php
    session_start();
    require_once 'partials/_dbconnection.php';
    if(!isset($_SESSION['login']) || $_SESSION['login'] != true){
      redirect("login.php");
      exit;
    }
    $user_id = $_SESSION['user_id'];
    $encrypt_order_id = $_GET['oid'];
    $order_id = decrypt($encrypt_order_id);

  $sql = "SELECT `product_name`,`price` FROM `order_items` WHERE `order_id` = $order_id";
  $result = mysqli_query($conn, $sql);
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $assets_url.'css/bootstrap.min.css'?>">
    <link rel="stylesheet" href="<?= $assets_url.'css/bootstrap.all.min.css'?>" />
    <link rel="stylesheet" href="<?= $assets_url.'css/mainbody.css'?>">
    <link rel="stylesheet" href="<?= $assets_url.'css/dropdown.css'?>"> 
    <link rel="stylesheet" href="<?= $assets_url.'css/checkout.css'?>">
    <title>Thank You</title>
</head>
<body>
  <?php require 'partials/_navbar.php'?>

  <div class="col-25">
  <div class="container my-2" style="text-align: center;"> 
    <div class="stylefont">
      <h1><strong> Thank You !!!</strong></h1></div>
    </div>
  </div>  
  <div class="col">
  <div class="col-25">
    <div class="container my-2" style="text-align: center;"> 
      <h3>for Shopping with us!</h3> 
      <h3>You will receive an email shortly.</h3> 
      <h3>If you didn't receive any mail contact us on <a href="#">abc@gmail.com</a></h3>
    </div>
    </div>
    </div>  
    <div class="col" >
      <div class="container" style="display: flex;">
        <div class=" col-50 p-3" >
          <a href="index.php"><button class="btn btn-secondary ">Go To HOME PAGE</button></a>
        </div>
        <div class=" col-50 p-3" >
          <a href="logout.php"><button class="btn btn-secondary ">Log Out</button></a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <div class="container my-2"> 
          <h2>Ordered Products</span></h2>
           <hr> 
          <?php
          $total = 0;
          while($row = mysqli_fetch_assoc($result)){
            $total += $row['price'];
            ?>
        <p><a><?php echo $row["product_name"];?></a> <span class="price"><?php echo $row["price"]; ?></span></p> 
        
        <?php
          }
          ?>
          <hr> 
          <p><h2><b>Total <span class="price" style="color:black"><?= $total ?></b></span></h2></p>
        </div>
      </div>
    </div>
    
</body>
</html>
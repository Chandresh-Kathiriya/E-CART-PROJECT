<?php
session_start();
require_once 'partials/_dbconnection.php';
if(!isset($_SESSION['login']) || $_SESSION['login'] != true){
  redirect("login.php");
  exit;
}
    if(isset($_SESSION['user_id'])) {
      $user_id = $_SESSION['user_id'];
      $encrypt_order_id = $_GET['odid'];
      $order_id = decrypt($encrypt_order_id);
      if ($order_id == null) {
        redirect("index.php");
      } else {
        $sql = "SELECT 
              oi.quantity,
              oi.product_name,
              p.productimage,
              p.price
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.product_id
        WHERE order_id = $order_id";
        $result = mysqli_query($conn, $sql);
      }  
    } 
    else{
      echo 'cart_id not found';
    }
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $assets_url.'css/bootstrap.min.css'?>">
    <link rel="stylesheet" href="<?= $assets_url.'css/bootstrap.all.min.css' ?>" />
    <link rel="stylesheet" href="<?= $assets_url.'css/myorder.css'?>">
    <link rel="stylesheet" href="<?= $assets_url.'css/dropdown.css'?>"> 
    <link rel="stylesheet" href="<?= $assets_url.'css/mainbody.css'?>">
    <title>Order Details</title>
</head>
<body>
<?php require 'partials/_navbar.php'?>
<section class="vh-50 gradient-custom-2">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-md-10 col-lg-8 col-xl-6">
        <div class="card card-stepper" style="border-radius: 16px;">
          <div class="card-header p-4">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <p class="text-muted mb-2"> Order ID: <span class="fw-bold text-body"><?= $order_id?></span></p>
                <p class="text-muted mb-2"> Place On: <span class="fw-bold text-body">12,March 2019</span> </p>
                <p class="text-muted mb-2">Tracking Status On: <span class="text-body">
                <?php 
                date_default_timezone_set("Asia/Calcutta");
                echo date("h:i a, ").date(" d/m/Y") . " Today";
                ?>
                </span></p>
              </div>
              <div>
                <!-- <h6 class="mb-0"> <a href="#">View Details</a> </h6> -->
              </div>
            </div>
          </div>
            
          <div class="card-body p-4">
          <?php
            $total = 0;
            while ($row = mysqli_fetch_assoc($result)) {
              $total += ($row["price"] * $row["quantity"]);
          ?>
            <div class="d-flex flex-row mb-4 pb-2">
              <div class="flex-fill my-4" style="width: 50%;">
                
                <h5 class="bold"><?php echo $row["product_name"];?></h5>
                <p class="text-muted"> Quantity: <?php echo $row["quantity"];?></p>
                <h4 class="mb-3"> <?php echo $row["price"] * $row["quantity"]; ?> INR  <span class="small text-muted"></span></h4>
                
              </div>
              <div>
                <img class="align-self-center img-fluid"
                  src="<?php echo $product_image_path.$row["productimage"];?>" alt="Image is uploading soon..." style="max-width: 250px; max-height: 250px; min-width: 100px; min-height: 0px;">
              </div>
            </div>
            <hr>
          
            <?php
        }
        ?>
          <div class="d-flex flex-row mb-4 pb-2">
            <div class="flex-fill my-4">
              <h5 class="bold">Total : </h5>
            </div>
            <div class="my-4">
              <h5 class="mb-3"><?php echo $total?> INR<span class="small text-muted"></span></h5>
            </div>
          </div>
        </div>
          <div class="card-footer p-4">
              <ul id="progressbar-1" class="mx-0 mt-0 mb-5 px-0 pt-0 pb-4">
                <li class="step0 active" id="step1"><span
                    style="margin-left: 22px; margin-top: 12px;">PLACED</span></li>
                <li class="step0 active text-center" id="step2"><span>SHIPPED</span></li>
                <li class="step0 text-muted text-end" id="step3" style="text-align: end;"><span
                    style="margin-right: 22px;">DELIVERED</span></li>
              </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
         function myFunction() {
          document.getElementById("myDropdown").classList.toggle("show");
          }
</script>
</body>
</html>
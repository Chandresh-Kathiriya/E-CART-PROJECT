<?php
session_start();
require_once 'partials/_dbconnection.php';
if(!isset($_SESSION['login']) || $_SESSION['login'] != true){
  redirect("login.php");
  exit;
}
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $quary = "SELECT order_id FROM orders WHERE user_id = $user_id ORDER BY order_id DESC";
    $result = mysqli_query($conn, $quary);
    $row  = mysqli_fetch_assoc($result);
    $order_id = $row['order_id'];

    $quary2 = "SELECT product_id FROM order_items WHERE order_id = $order_id ORDER BY order_id DESC";
    $result2 = mysqli_query($conn, $quary2);
    $numExistsRows2 = mysqli_num_rows($result2);
      if($numExistsRows2 > 0){
        $sql = "SELECT * FROM `orders` WHERE user_id = $user_id ORDER BY order_id DESC";
        $result = mysqli_query($conn, $sql);

        
        
      } else {
        // redirect("index.php");
          echo 'Please Select Atleast 1 Product to Proceed';  
      }
} 
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
    <title>Orders</title>
</head>
<body>
<?php require 'partials/_navbar.php'?>
<table class="table table-sm">
  <thead>
    <tr>
      <th scope="col">Sr No.</th>
      <th scope="col">Order ID</th>
      <th scope="col">Order Name</th>
      <th scope="col">Price</th>
      <th scope="col">Date</th>
    </tr>
  </thead>
  <?php
            $total = 0;
            $srno = 0;
            while ($row = mysqli_fetch_assoc($result)) {
            //   $total += ($row["price"] );

              $sql1 = "SELECT `order_name` FROM `orders` WHERE `order_id` = $order_id";
              $result1 = mysqli_query($conn, $sql1);
              $row1 = mysqli_fetch_assoc($result1);
              $order_name = $row1['order_name'];

              $srno += 1;
          ?>
  <tbody>
        <tr>
            <th><?php echo $srno?></th>
            <td>
              <a href="order_details.php?odid=<?php echo encrypt($row['order_id']);?>">
                <?php echo $row['order_id']?>
              </a>
            </td>
            <td><?php echo $row['order_name']?></td>
            <td><?php echo $row["total"]?></td>
            <td><?php echo $row['created_at']?></td>
        </tr>
  </tbody>
  <?php
            }
            ?>
</table>
<script>
         function myFunction() {
          document.getElementById("myDropdown").classList.toggle("show");
          }
</script>
</body>
</html>
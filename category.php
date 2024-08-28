<?php
session_start();
require_once 'partials/_dbconnection.php';
if(!isset($_SESSION['login']) || $_SESSION['login'] != true){
  redirect("login.php");
  exit;
}
    $encrypt_category_id = $_GET['ctid'];
    $category_id = decrypt($encrypt_category_id);

        $sql = "SELECT `product_id`,`product_name`,`price`,`discount`,`productimage` FROM products WHERE category_id = $category_id";
        $all_product = $conn->query($sql);

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
    <link rel="stylesheet" href="<?= $assets_url.'css/font.awesome.min.css'?>">
  <title>Category</title>
</head>
<body>
  <?php require 'partials/_navbar.php'?>
    <main>
        <?php
            while($row = mysqli_fetch_assoc($all_product)){
                $product_id = $row["product_id"];
        ?>
            <div class="card">
                <div class="image">
                    <a href="product_detail.php?pid=<?php echo encrypt($product_id);?>">
                        <img src="<?php echo $product_image_path.$row["productimage"];?>" alt="Image is not available.">
                    </a>
                </div>
                <div class="caption">
                    <p class="product_name"><b><?php echo $row["product_name"];?></b></p>
                    <p class="price">Price : <?php echo $row["price"];?> INR</b></p>
                    <p class="discount"><b>MRP : <del> <?php echo $row["discount"];?> INR</del></b></p>
                </div>
                <button class="add add_to_cart" type="button" data-product_id="<?= $row['product_id']; ?>">ADD TO CART</button>
            </div>
        <?php
            }
        ?>
        <form class="d-none" id="add_to_cart" method="post" action="add_cart.php">
            <input type="hidden" name="product_id" class="product_id" value="0">
        </form>
    </main>

    <?php require 'partials/_footer.php'?>
    <script src="<?= $assets_url.'js/slim.min.js' ?>"></script>
      <script src="<?= $assets_url.'js/bootstrap.min.js'?>"></script>
      <script src="<?= $assets_url.'js/jquery.mask.min.js'?>"></script>
      <script src="<?= $assets_url.'js/custom.js?t='.time() ?>"></script>
</body>
</html>
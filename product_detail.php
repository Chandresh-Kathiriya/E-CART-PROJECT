<?php
session_start();
require_once 'partials/_dbconnection.php';
?>
<!DOCTYPE html>
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
    <title>Product Detail</title>
</head>
<body>
      <?php require 'partials/_navbar.php'?>
    <?php
        $encrypt_order_id = $_GET['pid'];
        $product_id = decrypt($encrypt_order_id);

        $sql = "SELECT `product_id`,`product_name`,`price`,`discount`,`productimage`,`category_id` FROM products WHERE product_id = $product_id";
        $all_product = $conn->query($sql);
        $row = mysqli_fetch_assoc($all_product); 
        $product_id = $row['product_id'];
        $category_id = $row['category_id'];

    ?>
        <main>
            <div class="container my-3">
                <div class="image">
                    <div class="card" style="max-width: 400px; max-height: 400px;">
                        <img src="<?php echo $product_image_path.$row["productimage"];?>" alt="Image Not Available...">
                    </div>
                </div>
                <div class="caption px-3">
                    <p class="product_name"><strong><h3><?php echo $row["product_name"];?></h3></strong></p>
                    <p class="price"><strong><h5>Price : <?php echo $row["price"];?> INR</h5></strong></p>
                    <p class="discount">MRP : <del> <?php echo $row["discount"];?> INR</del></p>
                    <div class="row px-3">
                        <p class="description"><b>Product Description : </b><br>    Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora labore alias temporibus reprehenderit delectus cum dicta dolorum perspiciatis expedita, animi molestiae odit eius? Quas iste quidem voluptas provident id, nihil possimus minima sed eius! Sapiente soluta architecto repudiandae, earum officiis eligendi inventore dolore alias aspernatur enim repellat delectus minus nulla excepturi consequuntur non in quas minima laudantium quo? Labore tempore ipsam eveniet molestias? Quae repellendus odit rem vel nam ad accusantium pariatur magni. Repudiandae esse similique ratione, id cupiditate, animi a sequi pariatur, soluta molestias sed illum debitis? Expedita repellendus quasi voluptatibus quisquam excepturi ea voluptate omnis sit numquam quaerat.</p>
                    </div>
                    <button class="btn btn-secondary add_to_cart px-5 my-2" type="button" data-product_id="<?= $row['product_id']; ?>">ADD TO CART</button>
                </div>
            </div>
        </main>
        <main>
            <h3 class="px-4 my-3">Similar Products :</h3>
        </main>
        <main>
            <?php
                $sql1 = "SELECT `product_id`,`product_name`,`price`,`discount`,`productimage` FROM products WHERE category_id = $category_id AND NOT product_id = $product_id LIMIT 3";
                $all_product1 = $conn->query($sql1);
                while($row1 = mysqli_fetch_assoc($all_product1)){
                    $product_id1 = $row1['product_id'];
            ?>
                <div class="card">
                    <div class="image">
                        <a href="product_detail.php?pid=<?php echo encrypt($product_id1);?>">
                            <img src="<?php echo $product_image_path.$row1["productimage"];?>" alt="Image is not available.">
                        </a>
                    </div>
                    <div class="caption">
                        <p class="product_name"><b><?php echo $row1["product_name"];?></b></p>
                        <p class="price">Price : <?php echo $row1["price"];?> INR</b></p>
                        <p class="discount"><b>MRP : <del> <?php echo $row1["discount"];?> INR</del></b></p>
                    </div>
                    <button class="add add_to_cart" type="button" data-product_id="<?= $row1['product_id']; ?>">ADD TO CART</button>
                </div>
            <?php
                }
            ?>
        </main>
        <form class="d-none" id="add_to_cart" method="post" action="add_cart.php">
            <input type="hidden" name="product_id" class="product_id" value="0">
        </form>
      <?php require 'partials/_footer.php'?>
      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="<?= $assets_url.'js/slim.min.js' ?>"></script>
      <script src="<?= $assets_url.'js/bootstrap.min.js'?>"></script>
      <script src="<?= $assets_url.'js/custom.js?t='.time() ?>"></script>
      <script src="<?= $assets_url.'js/jquery.mask.min.js'?>"></script>
</body>
</html>
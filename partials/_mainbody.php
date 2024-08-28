<?php
    require_once 'partials/_dbconnection.php';
        $sql = "SELECT `product_id`,`product_name`,`price`,`discount`,`productimage`,`available` FROM products";
        $all_product = $conn->query($sql);
?>
    <main>
        <?php
            while($row = mysqli_fetch_assoc($all_product)){
                $product_id = $row["product_id"];
                $available = $row['available'];
                if($available == 1){
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
        }
        ?>
        <form class="d-none" id="add_to_cart" method="post" action="add_cart.php">
            <input type="hidden" name="product_id" class="product_id" value="0">
        </form>
    </main>
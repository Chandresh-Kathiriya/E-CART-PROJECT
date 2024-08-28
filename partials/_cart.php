<?php
    require_once 'partials/_dbconnection.php';
    $cart_id = 0;
    if(isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $quary = "SELECT cart_id FROM cart WHERE user_id = $user_id";
        $result = mysqli_query($conn, $quary);
        $row = mysqli_fetch_assoc($result);
        if($row) {
            $cart_id = $row['cart_id'];
        }
    } else{
        echo 'cart_id not found';
    }
    // $cart_token = isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : '';
    $result1 = [];
    if($cart_id) {
        $sql = "SELECT 
                    ci.product_id, 
                    ci.product_name,
                    ci.quantity, 
                    ci.total as cart_total, 
                    ci.price as cart_price, 
                    ci.discount as cart_discount, 
                    p.price, 
                    p.discount, 
                    p.productimage 
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.product_id
        WHERE cart_id = $cart_id";
        $result1 = mysqli_query($conn, $sql);
        // $all_product = $conn->query($sql);
    }
?>
<main>
<div class="container my-4 ">
    <div class="text-left">
        <h3>
            <b>MY CART</b>
        </h3>
    </div>
</div>
    <?php
        $total = 0;
        if($result1) {
            while ($row1 = mysqli_fetch_assoc($result1)) {
                $product_id = $row1['product_id'];
                $total += $row1['cart_total'];
    ?>
        <div class="container-fluid"> 
            <div class="row px-5"> 
                <div class="col-md-7"> 
                    <div class="shopping-cart">              
                        <form action="" method="" class="cart-items"> 
                            <div class="border rounded my-1">
                                <div class="row bg-white my-3" >
                                    <div class="col-md-3" >
                                        <img src="<?php echo $product_image_path.$row1["productimage"];?>" alt="Image1" class="img-fluid">                    
                                    </div>                    
                                    <div class="col-md-6">                    
                                        <h5 class="pt-2"><?php echo $row1["product_name"];?></h5>                    
                                        <h6 class="pt-2"><b>Price: <?php echo $row1["price"];?> INR</b></h6>                    
                                        <small class="text-secondary">MRP: <del><?php echo $row1["discount"]; ?> INR</del></small>  
                                        <h5 class="pt-2"><b>Total: <?php echo $row1["cart_total"];?> INR</b></h5>                
                                        <a href="/e_cart/partials/_delete_from_cart.php?pdid=<?php echo encrypt($product_id);?>" class="btn btn-danger mx-2">
                                            Remove Now
                                        </a>
                                    </div>                    
                                    <div class="col-md-3 my-5 ">
                                        <div style="display: inline-flex;">
                                            <a class="btn bg-light border rounded-circle mx-2" href="/e_cart/partials/_decrease_quantity_of_product.php?pdid=<?php echo encrypt($product_id);?>" >
                                                <i class="fa fa-minus black-icon"></i>
                                            </a>
                                            <input type="text" value="<?php echo $row1["quantity"];?>" class="form-control w-25-d-inline" disabled></input>
                                            <a class="btn bg-light border rounded-circle mx-2" href="/e_cart/partials/_increase_quantity_of_product.php?pdid=<?php echo encrypt($product_id);?>" ><i class="fa fa-plus"></i></a>
                                        </div>
                                    </div>                    
                                </div>             
                            </div>
                        </form>
                    </div>
                </div>
        </div>
        </div>
    <?php
            }
    ?>
        <div class="container-fluid">
            <div class="row px-5">
                <div class="col-md-7">
                    <div class="shopping-cart">                    
                        <form action="/e_cart/checkout.php" method="" class="cart-items"> 
                            <div class="border rounded my-1" >
                                <div class="col bg-white my-1" style="padding: 30px; text-align: center;">   
                                    <h5 class="pt-2"><b>TOTAL : <?= $total ?> INR</b></h5>   <br>               
                                    <button type="checkout" class="btn btn-secondary mx-2" name="checkout">CHECKOUT</button>   
                                </div>             
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php
        } else {
    ?>
        <div class="container-fluid">
            <div class="row px-5">
                <div class="col-md-10 offset-1">
                    <div class="shopping-cart">                    
                        <form action="/e_cart/checkout.php" method="" class="cart-items"> 
                            <div class="border rounded my-1" >
                                <div class="col bg-white my-1" style="padding: 30px; text-align: center;">                     
                                    <h5 class="pt-2">Your Cart is Empty.</h5><br>   
                                    <a href="index.php">            
                                        <button type="button" class="btn btn-secondary mx-2" name="checkout">Continue Shopping</button>   
                                    </a>
                                </div>             
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>     
    <?php
        }
    ?>
</main>
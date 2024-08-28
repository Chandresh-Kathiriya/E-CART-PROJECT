<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once 'partials/_dbconnection.php';

$total_cart_item = 0;
$cart_token = isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : '';
if($cart_token) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
    if($user_id) { 
      $sql = "SELECT 
              count(ci.cart_id) as total_items
          FROM cart_items ci
          JOIN cart c ON c.cart_id = ci.cart_id
          WHERE c.cart_token = '$cart_token'
                AND c.user_id = $user_id";
      $cart_item_result = mysqli_query($conn, $sql);
      $cart_items_exists = mysqli_num_rows($cart_item_result);
      if($cart_items_exists) {
          $cart_item_row = mysqli_fetch_assoc($cart_item_result);
          $total_cart_item = $cart_item_row['total_items'];
      }
    }
}

if (isset($_SESSION['login']) && $_SESSION['login']==true) {
  $login = true;
}
else{
  $login = false;
}
?>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/E_CART"><h3 class="my-1">E CART</h3></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ui class="navbar-nav mr-auto">
        <li class="nav-item active px-3 my-auto">
          <a class="nav-link ml-4" href="/E_CART">
            Home
          </a>
        </li>
      </ul>
      <div class="dropdown">
        <button class="btn bg-dark text-white sub-category">Category</button>
        <div id="myDropdown" class="dropdown-content">
          <a href="/E_CART/category.php?ctid=<?php echo encrypt(1);?>">Television</a>
          <a href="/E_CART/category.php?ctid=<?php echo encrypt(2);?>">Perfume</a>
          <a href="/E_CART/category.php?ctid=<?php echo encrypt(3);?>">Water Heater</a>
          <a href="/E_CART/category.php?ctid=<?php echo encrypt(4);?>">Water Purifier</a>
          <a href="/E_CART/category.php?ctid=<?php echo encrypt(5);?>">Laptop</a>
          <a href="/E_CART/category.php?ctid=<?php echo encrypt(6);?>">Book</a>
          <a href="/E_CART/category.php?ctid=<?php echo encrypt(7);?>">Home Theater</a>
          <a href="/E_CART/category.php?ctid=<?php echo encrypt(8);?>">Washing Machine</a>
          <a href="/E_CART/category.php?ctid=<?php echo encrypt(9);?>">Printer</a>
          <a href="/E_CART/category.php?ctid=<?php echo encrypt(10);?>">Watch</a>
          <a href="/E_CART/category.php?ctid=<?php echo encrypt(11);?>">Air Conditioner</a>
          <a href="/E_CART/category.php?ctid=<?php echo encrypt(12);?>">Mobile</a>
          <a href="/E_CART/category.php?ctid=<?php echo encrypt(13);?>">Tablet</a>
        </div>
      </div>
          <!-- <select class="btn btn-secondary bg-dark" name="expire_month" id="nav_btn">
            <option value="" style="color: white;">Category</option>
            <a href="/E_CART/category.php">Television</a>
            <option value="" style="color: white;">Television</option>
            <option value="" style="color: white;">Perfume</option>
            <option value="" style="color: white;">Water heater</option>
            <option value="" style="color: white;">Water purifier</option>
            <option value="" style="color: white;">Laptop</option>
            <option value="" style="color: white;">Book</option>
            <option value="" style="color: white;">Home Theater</option>
            <option value="" style="color: white;">Washing Machine</option>
            <option value="" style="color: white;">Printer</option>
            <option value="" style="color: white;">Watch</option>
            <option value="" style="color: white;">Air Conditioner</option>
            <option value="" style="color: white;">Mobile</option>
            <option value="" style="color: white;">Tablet</option>
          </select> -->
        
    </div>
    <?php
    if (!$login) {
    ?>
      <form class="form-inline my-2 my-lg-0">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="/E_CART/login.php">Login</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="/E_CART/registration.php">Registration</a>
            </li>
          </ul>
        </div>
      </form>
    </nav>
    <?php
    }
    if ($login) {
    ?>
      <form class="form-inline my-2 my-lg-0">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="/E_CART/cart.php">
                <i class="fa fa-shopping-cart white-icon">
                  <span class="badge cart-badge rounded-circle bg-light text-black my-2">
                   <?= $total_cart_item ?>
                  </span>
                </i>
              </a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="/E_CART/myorders.php">My Orders</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="/E_CART/my_account.php">My Account</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="/E_CART/logout.php">Log Out</a>
            </li>
          </ul>
        </div>
      </form>
    </nav>
  <?php
    }
  ?>
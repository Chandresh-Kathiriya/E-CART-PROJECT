<?php
  require_once 'partials/_dbconnection.php';
  if(isset($_SESSION['emailaddress'])) {
    $emailaddress = $_SESSION['emailaddress'];
    $quary = "SELECT cart_id FROM cart WHERE emailaddress = '$emailaddress'";
    $result = mysqli_query($conn, $quary);
    $row = mysqli_fetch_assoc($result);
    $cart_id = isset($row['cart_id']) ? $row['cart_id'] : '';
    $quary1 = "SELECT firstname, lastname FROM users WHERE emailaddress = '$emailaddress'";
    $result1 = mysqli_query($conn, $quary1);
    $row1 = mysqli_fetch_assoc($result1);
    $firstname = $row1['firstname'];
    $lastname = $row1['lastname'];
  }
  
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
              WHERE cart_id='$cart_id'";
              $result1 = mysqli_query($conn, $sql);
  }
?>

<main>
<form action="" method="POST" id="checkout_form">
  <div class="row my-3">
      <div class="col-75">
          <div class="container"> 
              <div class="row">
                  <div class="col-40">
                      <h3>Billing AND Shipping Address</h3>
                      <label for="fname"><span class="required">*</span>First Name</label>
                      <input type="text" id="firstname" name="firstname" placeholder="John" value="<?= $firstname?>">
                      <label for="fname"><span class="required">*</span>Last Name</label>
                      <input type="text" id="fname" name="lastname" placeholder="Doe" value="<?= $lastname?>">
                      <label for="email"><span class="required">*</span>Email</label>
                      <input type="text" id="email" name="emailaddress" disabled   placeholder="john@example.com" value="<?= $_SESSION['emailaddress']?>">
                      <label for="adr"><span class="required">*</span>Address</label>
                      <input type="text" id="adr" name="address" placeholder="542 W. 15th Street" value="<?= isset($_POST['address']) ? $_POST['address'] : '' ?>">
            
                      <div class="row two-input">
                        <div class="col-40">
                          <label for="city"><span class="required">*</span>City</label>
                          <input type="text" id="city" name="city" placeholder="New York" value="<?= isset($_POST['city']) ? $_POST['city'] : '' ?>">
                        </div>
                        <div class="col-40">
                          <label for="state"><span class="required">*</span>State</label>
                          <input type="text" id="state" name="state" placeholder="NY" value="<?= isset($_POST['state']) ? $_POST['state'] : '' ?>">
                        </div>
                      </div>
                      <div class="row two-input">
                          <div class="col-40">
                              <label for="country"><span class="required">*</span>Country</label>
                              <input type="text" minlength="2" maxlength="2" id="country" name="country" placeholder="USA" value="<?= isset($_POST['country']) ? $_POST['country'] : '' ?>">
                          </div>
                          <div class="col-40">
                              <label for="zip_code"><span class="required">*</span>Zip</label>
                              <input type="text" minlength="6" maxlength="6" id="zip" name="zip_code" placeholder="10001" value="<?= isset($_POST['zip_code']) ? $_POST['zip_code'] : '' ?>">
                          </div>
                      </div>
                    </div>
          
          <div class="col">
            <h3>Payment</h3>
            <label for="fname">Accepted Cards</label>
            <div class="icon-container">
              <i class="fa fa-cc-visa" style="color: navy;"></i>  
              <i class="fa fa-cc-amex" style="color: red"></i>
              <i class="fa fa-cc-mastercard" style="color: red;"></i>
              <i class="fa fa-cc-discover" style="color: orange;"></i>
            </div>
            <label for="cname"><span class="required">*</span>Name on Card</label>
            <input type="text" maxlength="26" id="name_on_card" name="name_on_card" placeholder="John More Doe">
            <label for="ccnum"><span class="required">*</span>Card number</label>
            <!-- <div>
              <input type="text" minlength="16" maxlength="16" id="card_number" name="card_number" placeholder="1111-2222-3333-4444">
            </div> -->
            <div id="card_number" class="custom-element"></div>
            <div class="col-40">
              <!-- <div class="col-40">
              <label for="expmonth">Exp. Month</label>
                <select class="btn btn-secondary bg-white" name="expire_month" id="expmonth" style="color: gray; width: 90%;">
                  <optgroup label="Select Month"> 
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                  </optgroup>
                </select>
              </div> -->
            </div>
              <div class="my-3">
                <div class="col-40 ">
                  <label for="expdate"><span class="required">*</span>Exp</label>
                  <div id="expdate" class="custom-element"></div>
                  <!-- <div>
                    <input type="text" id="expdate" name="expire_date" placeholder="01/25">
                  </div> -->
                </div>
                <div class="col-40">
                  <label for="cvv"><span class="required">*</span>CVC</label>
                  <div id="cvv" class="custom-element"></div>
                  <!-- <div>
                    <input type="password" id="cvv" name="cvv" placeholder="352">
                  </div> -->
                </div>
              </div>
              <input type="hidden" name="cc_token" class="cc_token" value="">    
          </div>
    </div>
    <div class="cc-error"></div>
    <p style="color: lightslategrey;"><span class="required">* </span>feilds are required</p>
    <button type="button" class="btn btn-secondary" id="submit_checkout">Continue To Checkout</button> 
  </div>
</div>

  <div class="col-25" style="width: max-content;">
      <div class="container" >
      <h4 >Cart Details</span></h4>
      
      <hr>
      <?php
        $sub_total = $discount = $total = 0;
        while($row1 = mysqli_fetch_assoc($result1)){
        $total += $row1['cart_total'];
      ?>
        <p><a><?php echo $row1["product_name"];?>&nbsp;</a>  <span class="price"><?php echo $row1["price"]; ?> * <?php echo $row1['quantity'];?></span></p>
        
      <?php
        }
      ?>
        <h6><br></h6>
        <hr>
      <p>Total <span class="price" style="color:black"><b><?= $total ?> INR</b></span></p>
    </div>
  </div>
</div>
</form>
</main>
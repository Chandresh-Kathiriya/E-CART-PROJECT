<?php
session_start();
$showerror = false;

require_once 'partials/_dbconnection.php';
if(!isset($_SESSION['login']) || $_SESSION['login'] != true){
  redirect("Location: login.php");
  exit;
}
require_once 'curl.php';
$error_message = '';
$has_error = 0;

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $address = $_POST["address"];
  $city = $_POST["city"];
  $state = $_POST["state"];
  $country = $_POST["country"];
  $zip_code = $_POST["zip_code"];
  $name_on_card = $_POST["name_on_card"];
  $cc_token = $_POST['cc_token'];
  $charge_id = '';

  // GET user_id
  $user_id = $_SESSION['user_id'];
  $emailaddress = $_SESSION['emailaddress'];

  if(empty($address) || empty($city) || empty($state) || empty($country) || empty($zip_code)){
    $showerror = "Please Fill The Details First To continue.";
  } else{
    $payment_success = false;

    // GET CART ID
    $sql3 = "SELECT cart_id FROM cart WHERE user_id = $user_id";
    $result3 = mysqli_query($conn, $sql3);
    $row3 = mysqli_fetch_assoc($result3);
    $cart_id = $row3['cart_id'];
    
    // GET ORDER TOTAL AMOUNT
    $sql4 = "SELECT  total FROM cart_items WHERE cart_id = $cart_id";
    $result4 = mysqli_query($conn, $sql4);
    $total = 0;
    while ($row4 = mysqli_fetch_assoc($result4)) {
      $total += $row4['total'];    
    }

    
    $headers = [
      'Content-Type: application/x-www-form-urlencoded',
      "Authorization: Bearer $stripe_api_key"
    ];

    if($cc_token) {
      $stripe_card_token_id = $cc_token;

      $quary1 = "SELECT stripe_customer_id FROM users WHERE user_id = $user_id";
      $result2 = mysqli_query($conn, $quary1);
      $row1 = mysqli_fetch_assoc($result2);
      $user_stripe_id = $row1['stripe_customer_id'];

      if($user_stripe_id) {
        $update = true;
        $custmer_data = ['name' => $name_on_card,
                        'source' => $stripe_card_token_id
                      ];
        $customer_response = cURL("https://api.stripe.com/v1/customers/$user_stripe_id",
                            'POST',
                            http_build_query($custmer_data),
                            $headers
                          );
      } else {
        $update = false;
        // CREATE CUSTOMER
        $custmer_data = ['name' => $name_on_card,
                        'email' => $emailaddress,
                        'source' => $stripe_card_token_id,
                        'address' => [
                                    'line1' => $address,
                                    'postal_code' => $zip_code,
                                    'city' => $city,
                                    'state' => $state,
                                    'country' => $country
                                  ]
                      ];
        $customer_response = cURL('https://api.stripe.com/v1/customers',
                            'POST',
                            http_build_query($custmer_data),
                            $headers
                          );
      }

      if(!$customer_response['error']) {
        $stripe_customer_id = $customer_response['message']['id'];

        // UPDATE STRIPE CUSTOMER ID TO USERS
        if(!$update) {
          $sql = "UPDATE users SET stripe_customer_id = '$stripe_customer_id' WHERE user_id = $user_id";
          mysqli_query($conn, $sql);
        }

        // CHARGE THE CUSTOMER
        $charge_data = ['amount' => ($total * 100),
          'currency' => $currency,
          // 'source' => $stripe_card_token_id,
          'customer' => $stripe_customer_id,
          'description' => 'Cart ID: '.$cart_id];

        $charge_response = cURL('https://api.stripe.com/v1/charges',
                                'POST',
                                http_build_query($charge_data),
                                $headers
                              );
        if(!$charge_response['error']) {
          $charge_response = $charge_response['message'];
          $charge_id = $charge_response['id'];
          $charge_status = $charge_response['status'];

          if($charge_status == 'succeeded' || $charge_status == 'pending') {
            $payment_success = true;
            $charge_source = $charge_response['source'];
            $card_number = $charge_source['last4'];
            $expire_month = sprintf("%02d", $charge_source['exp_month']);
            $expire_year = $charge_source['exp_year'];
          }
        } else {
          $error_message = isset($charge_response['message']['error']['message']) ? $charge_response['message']['error']['message'] : '';
          $has_error = 1;
        }
      } else {
        $error_message = isset($customer_response['message']['error']['message']) ? $customer_response['message']['error']['message'] : '';
        $has_error = 1;
      }
    }

    if($payment_success) {
        // GET NEXT ORDER NUMBER
        $sql7="SELECT order_number FROM `orders` ORDER BY order_id DESC LIMIT 1";
        $result7 = mysqli_query($conn, $sql7);
        $data = mysqli_fetch_assoc($result7);
        if(isset($data['order_number']) && $data['order_number']) {
          $order_number = $data['order_number'] + 1;
        } else {
          $order_number = 1;
        }
        
        // INSERT ORDER INTO DATABASE
        $sql5 = "INSERT INTO `orders` (`order_number`,`order_name`,`user_id`,`emailaddress`,`total`) VALUES ($order_number,'#$order_number', $user_id ,'$emailaddress', '$total')";
        $result5 = mysqli_query($conn, $sql5);
        $order_id = $conn->insert_id;

        // SELECT CART ITEMS
        $query = "SELECT ci.product_id,
                        ci.quantity,
                        p.product_name, 
                        p.price 
                  FROM cart_items ci
                  JOIN products p ON p.product_id = ci.product_id
                  WHERE cart_id = $cart_id";
        $result = mysqli_query($conn, $query);

        // CREATE ORDER ITEMS
        while ($cart_row = mysqli_fetch_assoc($result)) {
          $product_id = $cart_row['product_id'];
          $product_name = $cart_row['product_name'];
          $price = $cart_row['price'];
          $quantity = $cart_row['quantity'];

          $sql9 = "INSERT INTO `order_items`(`order_id`, `product_id`, `product_name`, `price`, `quantity`) VALUES ($order_id,$product_id,'$product_name',$price,$quantity)";
          $result9 = mysqli_query($conn, $sql9);
        }
        
        // CREATE ORDER PAYMENT
        $sql1 = "INSERT INTO `order_payment` (`user_id`,`order_id`,`name_on_card`, `card_number`, `expire_month`, `expire_year`, `charge_id`) VALUES ($user_id,$order_id,'$name_on_card', '$card_number', '$expire_month', '$expire_year', '$charge_id')";
        $result1 = mysqli_query($conn, $sql1);

        // CREATE USER ADDRESS
        $sql = "INSERT INTO `user_address` (`user_id`,`address`, `city`, `state`, `country`, `zip_code`) VALUES ($user_id,'$address', '$city', '$state', '$country', $zip_code)";
        $result = mysqli_query($conn, $sql);

        // FOR DELETE CART DATA 
        $sql = "DELETE FROM `cart_items` WHERE cart_id = $cart_id";
        $result = mysqli_query($conn, $sql);
        $sql2 = "DELETE FROM `cart` WHERE user_id = $user_id";
        $result2 = mysqli_query($conn, $sql2);

        redirect("thankyou.php?oid=".encrypt($order_id));
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $assets_url.'css/bootstrap.min.css'?>">
    <link rel="stylesheet" href="<?= $assets_url.'css/font.awesome.min.css' ?>">
    <link rel="stylesheet" href="<?= $assets_url.'css/sweetalert2.min.css' ?>">
    <link rel="stylesheet" href="<?= $assets_url.'css/mainbody.css'?>">
    <link rel="stylesheet" href="<?= $assets_url.'css/dropdown.css'?>"> 
    <link rel="stylesheet" href="<?= $assets_url.'css/checkout.css?t=8'?>">
    <title>CHECK OUT</title>
</head>
<body>
<?php require 'partials/_navbar.php'?> 
<?php
    if($showerror) {
    ?> 
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>OOPS!! </strong> <?= $showerror?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php
    }
    ?>
  <?php require 'partials/_checkout.php'?> 
  <?php require 'partials/_footer.php'?> 
</body>
    <script src="<?= $assets_url.'js/slim.min.js' ?>"></script>
    <script src="<?= $assets_url.'js/bootstrap.min.js'?>"></script>
    <script src="<?= $assets_url.'js/jquery.mask.min.js'?>"></script>
    <script src="<?= $assets_url.'js/sweetalert2.all.min.js'?>"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="<?= $assets_url.'js/custom.js?t='.time()?>"></script>
    
    <script>
      var error_html = '<div class="alert alert-danger alert-dismissible fade card-error-child" role="alert">'+
                          '<div class="error"></div>'+
                          '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                        '</div>';
      var stripe = Stripe("<?= $stripe_publisher_key ?>");
      var style = {
                  base: {
                    iconColor: '#000000',
                    color: '#000000',
                    lineHeight: '24px',
                    fontSize: '16px',
                    '::placeholder': {
                      color: '#a9a9a9',
                    },
                  },
                  invalid: {
                    iconColor: '#F70000',
                    color: '#F70000',
                  },
                  complete: {
                    iconColor: '#008000',
                    color: '#008000',
                  },
                };
      var elements = stripe.elements();

      var cardNumber = elements.create('cardNumber', {
                                          style: style
                                        });
      cardNumber.mount('#card_number');
      var cardExpiry = elements.create('cardExpiry', {
                                          style: style
                                        });
      cardExpiry.mount('#expdate');
      var cardCvc = elements.create('cardCvc', {
                                          style: style
                                        });
      cardCvc.mount('#cvv');

      cardNumber.on('change', function(event) {
        doSomething(event);
      });

      cardExpiry.on('change', function(event) {
        doSomething(event);
      });

      cardCvc.on('change', function(event) {
        doSomething(event);
      });

      function doSomething(result) {
        $(".cc-error").html("");
        $(".card-error-child").removeClass("show");
        if (result.token) {
          // In this example, we're simply displaying the token
          $('.cc_token').val(result.token.id);
          $('#checkout_form').submit();
        } else if (result.error) {
          $(".cc-error").html(error_html);
          $(".card-error-child").addClass("show");
          $(".error").html(result.error.message);
        }
      }

      $(document).ready(function() {
        $("#submit_checkout").click(function(e) {
          // e.preventDefault();
          stripe.createToken(cardNumber).then(doSomething);
        });

        if(<?= $has_error ?>) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "<?= $error_message ?>"
          });
        }
      });
    </script>
</html>
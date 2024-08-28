<?php
$showalert = false;
$showerror = false;
include 'partials/_dbconnection.php';
if($_SERVER["REQUEST_METHOD"] == "POST"){
  $emailaddress = $_POST["emailaddress"];
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $password = $_POST["password"];
  $confirmpassword = $_POST["confirmpassword"];
  if(empty($emailaddress) || empty($firstname) || empty($lastname) || empty($password) || empty($confirmpassword)){
    $showerror = "Please Fill Your Details First :(";
  } else {
    //$existsemail = false;
    $existsSql = "SELECT emailaddress FROM `users` WHERE emailaddress = '$emailaddress'";
    $result = mysqli_query($conn, $existsSql);
    $numExistsRows = mysqli_num_rows($result);
    if($numExistsRows > 0){
        // $existsemail = true;
        $showerror = "Email already exists";
    } else {
      //$existsemail = false;
      if (($password == $confirmpassword)){
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users` (`emailaddress`, `firstname`, `lastname`, `password`) VALUES ('$emailaddress', '$firstname', '$lastname', '$hash_password')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
          // $showalert = true;
          redirect('index.php');
        }
      } 
      else {
          $showerror = "PASSWORD DOESN'T MATCH";
      }
    }
  }
}
?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= $assets_url.'css/bootstrap.min.css'?>">
    <link rel="stylesheet" href="<?= $assets_url.'css/mainbody.css'?>">
    <link rel="stylesheet" href="<?= $assets_url.'css/dropdown.css'?>"> 

    <title>Registration</title>
  </head>
  <body>
    <?php require 'partials/_navbar.php'?>
    <?php
    if ($showalert) {
    ?> <div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>YUPP!</strong> your registration is successfull !!!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <?php
    }
    if ($showerror) {
    ?> <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>OOPS!!</strong> <?=$showerror?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
    <?php
    }
    ?>
    <div class="container my-4 ">
        <h1 class="text-center">REGISTER YOUR DETAILS</h1>

        <form action="/E_CART/registration.php" method="post">
  <div class="form-group">
    <label for="emailAddress">
      <span class="required">*</span>
      Email address :
    </label>
    <input type="email" maxlength="50" class="form-control" id="emailAddress" name="emailaddress" aria-describedby="emailHelp">
  </div>
  <div class="form-group">
    <label for="firstName">
      <span class="required">*</span>
      Your First Name :
    </label>
    <input type="text" maxlength="20" class="form-control" id="firstName" name="firstname">
  </div>
  <div class="form-group">
    <label for="lastName">
      <span class="required">*</span>
      Your Last Name :
    </label>
    <input type="text" maxlength="20" class="form-control" id="lastName" name="lastname">
  </div>
  <div class="form-group">
    <label for="password">
      <span class="required">*</span>
      Password :
    </label>
    <input type="password" maxlength="20" minlength="8" class="form-control" id="password" name="password">
  </div>
  <div class="form-group">
    <label for="confirmPassword">
      <span class="required">*</span>
      Confirm Password :
    </label>
    <input type="password" maxlength="20" minlength="8" class="form-control" id="confirmPassword" name="confirmpassword">
  </div>
  <p style="color: lightslategrey;"><span class="required">* </span>feilds are required</p>
  <button type="register" class="btn btn-primary">Register</button>
</form>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?= $assets_url.'js/slim.min.js' ?>"></script>
    <script src="<?= $assets_url.'js/bootstrap.min.js'?>"></script>
    <script src="<?= $assets_url.'js/proper.min.js'?>"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script> -->
    
  </body>
</html>
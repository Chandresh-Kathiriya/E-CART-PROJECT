<?php
session_start();
$login = false;
$showerror = false;
include 'partials/_dbconnection.php';
if(isset($_SESSION['login']) && $_SESSION['login'] == true){
  redirect("index.php");
  exit;
}
if($_SERVER["REQUEST_METHOD"] == "POST"){
  $emailaddress = $_POST["emailaddress"];
  $password = $_POST["password"];
  if(empty($emailaddress) && empty($password)){
    $showerror = "Please First Enter Email AND Password :(";
  } elseif(!empty($emailaddress) && empty($password)){
    $showerror = "Please Enter Password :(";
  } elseif(!empty($password) && empty($emailaddress)){
    $showerror = "Please Enter Email :(";
  } else{
    $sql = "SELECT emailaddress, password, user_id, firstname, lastname 
            FROM `users` 
            WHERE emailaddress = '$emailaddress'";
      $result = mysqli_query($conn, $sql);
      $numbersofuser = mysqli_num_rows($result);

      if ($numbersofuser == 1) {
        while($row=mysqli_fetch_assoc($result)){
          if (password_verify($password, $row['password'])) {
            $login = true;
            $user_id = (int) $row['user_id'];
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $_SESSION['login'] = true;
            $_SESSION['emailaddress'] = $emailaddress;  
            $_SESSION['user_id'] = $user_id;
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            redirect("index.php");
          }else {
            $showerror = "Wrong Password Entered, Please Try Again :(";
          }
        }
      } else {
        $showerror = "Invalid credentials";
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
    <title>Login</title>
  </head>
  <body>
    <?php require 'partials/_navbar.php'?>
    <?php
    if ($login) {
    ?> 
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>YUPP!</strong> You are log in successfully!!!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php
    }
    if($showerror) {
    ?> 
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>OOPS!!</strong> <?=$showerror?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php
    }
    ?>
      <div class="container my-4 ">
        <h1 class="text-center">Login Here</h1>
        <form action="/E_CART/login.php" method="post">
          <div class="form-group">
            <label for="emailAddress">
              <span class="required">*</span>
              Email address :
            </label>
            <input type="email" maxlength="50" class="form-control" id="emailAddress" name="emailaddress" aria-describedby="emailHelp">
          </div>
          <div class="form-group">
            <label for="password">
              <span class="required">*</span>
              Password :
            </label>
            <input type="password" maxlength="20" minlength="8" class="form-control" id="password" name="password">
          </div>
          <p style="color: lightslategrey;"><span class="required">* </span>feilds are required</p>
          <button type="register" class="btn btn-primary">Login</button><br>
        </form>
</div>
      </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?= $assets_url.'js/slim.min.js' ?>"></script>
    <script src="<?= $assets_url.'js/bootstrap.min.js'?>"></script>
    
  </body>
</html>
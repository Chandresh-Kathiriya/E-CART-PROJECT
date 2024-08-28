<?php
session_start();
$user_id = $_SESSION['user_id'];
$showerror = false;
include 'partials/_dbconnection.php';
if(!isset($_SESSION['login']) || $_SESSION['login'] != true){
    redirect("login.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];


    $getuserdetails = "SELECT `password` from `users` WHERE user_id = $user_id";
    $result = mysqli_query($conn, $getuserdetails);
    $row = mysqli_fetch_assoc($result);

    if(empty($current_password)){
        $showerror = "Please First Enter Your Current Password :(";
    } 
    else if (!empty($current_password) && empty($first_name) && empty($last_name) && empty($new_password) && empty($confirm_password)) {
        $showerror = "Enter Any Details Which You Want To Update...";
    } 
    else if (!empty($current_password) && (!empty($first_name) || !empty($last_name)) && empty($new_password) && empty($confirm_password)) {
        if(password_verify($current_password, $row['password'])){
            if(!empty($first_name) && empty($last_name)){
                $updateFirstName = "UPDATE `users` SET `firstname` = '$first_name' WHERE user_id = $user_id";
                $result = mysqli_query($conn, $updateFirstName);
                redirect('my_account.php');
            } else if(empty($first_name) && !empty($last_name)){
                $updateLastName = "UPDATE `users` SET `lastname` = '$last_name' WHERE user_id = $user_id";
                $result = mysqli_query($conn, $updateLastName);
                redirect('my_account.php');
            } else {
                $updateFirstAndLastName = "UPDATE `users` SET  `firstname` = '$first_name',`lastname` = '$last_name' WHERE user_id = $user_id";
                $result = mysqli_query($conn, $updateFirstAndLastName);
                redirect('my_account.php');
            }
        } else {
            $showerror = "Current Password is Incorrect :(";
        }
    } 
    else if (!empty($current_password) && !empty($first_name) && empty($last_name) && !empty($new_password) && !empty($confirm_password)) {
        $showerror = "you want to update first name and password";
        if(password_verify($current_password, $row['password'])){
            if($new_password == $confirm_password){
                if($new_password == $current_password){
                    $showerror = "New Password Should Be Different From Current Password :(";
                } else {
                    $hash_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $updateFirstnameAndPassword = "UPDATE `users` SET `password` = '$hash_password',`firstname` = '$first_name' WHERE user_id = $user_id";
                    $result = mysqli_query($conn, $updateFirstnameAndPassword);
                    $showerror = "first name and password Upadted Successfully";
                    redirect('my_account.php');
                }
            } else {
                $showerror = "Confirm Password Should Be Same As New Password :(";
            }
        } else {
            $showerror = "Current Password is Incorrect :(";
        }
    } 
    else if (!empty($current_password) && empty($first_name) && !empty($last_name) && !empty($new_password) && !empty($confirm_password)) {
        $showerror = "you want to update last name and password";
        if(password_verify($current_password, $row['password'])){
            if($new_password == $confirm_password){
                if($new_password == $current_password){
                    $showerror = "New Password Should Be Different From Current Password :(";
                } else {
                    $hash_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $updateLastnameAndPassword = "UPDATE `users` SET `password` = '$hash_password',`lastname` = '$last_name' WHERE user_id = $user_id";
                    $result = mysqli_query($conn, $updateLastnameAndPassword);
                    $showerror = "last name and password Upadted Successfully";
                    redirect('my_account.php');
                }
            } else {
                $showerror = "Confirm Password Should Be Same As New Password :(";
            }
        } else {
            $showerror = "Current Password is Incorrect :(";
        }
    } 
    else if (!empty($current_password) && !empty($first_name) && !empty($last_name) && !empty($new_password) && !empty($confirm_password)) {
        $showerror = "you want to update all";
        if(password_verify($current_password, $row['password'])){
            if($new_password == $confirm_password){
                if($new_password == $current_password){
                    $showerror = "New Password Should Be Different From Current Password :(";
                } else {
                    $hash_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $updateAllDetails = "UPDATE `users` SET `password` = '$hash_password',`firstname` = '$first_name', `lastname` = '$last_name' WHERE user_id = $user_id";
                    $result = mysqli_query($conn, $updateAllDetails);
                    $showerror = "All Details Upadted Successfully";
                    redirect('my_account.php');
                }
            } else {
                $showerror = "Confirm Password Should Be Same As New Password :(";
            }
        } else {
            $showerror = "Current Password is Incorrect :(";
        }
    } 
    else {
        $showerror = "you want to update password";
        if(password_verify($current_password, $row['password'])){
            if($new_password == $confirm_password){
                if($new_password == $current_password){
                    $showerror = "New Password Should Be Different From Current Password :(";
                } else {
                    $hash_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $updatepassword = "UPDATE `users` SET `password` = '$hash_password' WHERE user_id = $user_id";
                    $result = mysqli_query($conn, $updatepassword);
                    $showerror = "Password Upadte Successfully";
                    redirect('my_account.php');
                }
            }
        } else {
            $showerror = "Current Password is Incorrect :(";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $assets_url.'css/bootstrap.min.css' ?>">
    <link rel="stylesheet" href="<?= $assets_url.'css/bootstrap.all.min.css' ?>"/>
    <link rel="stylesheet" href="<?= $assets_url.'css/mainbody.css' ?>">
    <title>Change Password</title>
</head>
<body>
    <?php require 'partials/_navbar.php' ?>
    <?php
    if($showerror) {
    ?> 
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>OOPS!!</strong> <?= $showerror?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php
    }
    ?>
    <div class="container my-4">
        <form action="/E_CART/change_password.php" method="post">    
            <div class="form-group">
                <label for="first_name">First Name :</label>
                <input type="text" class="form-control" id="first_last" name="first_name">
            </div>
            <div class="form-group">
                <label for="last_name">Last Name :</label>
                <input type="text" class="form-control" id="last_name" name="last_name">
            </div>
            <div class="form-group">
                <label for="current_password">
                    <span class="required">*</span>
                    Current Password :
                </label>
                <input type="password" class="form-control" id="current_password" name="current_password">
            </div>
            <div class="form-group">
                <label for="new_password">New Password :</label>
                <input type="password" class="form-control" id="new_password" name="new_password">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password :</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
            </div>
            <p style="color: lightslategrey;"><span class="required">* </span>feilds are required</p>
            <button type="register" class="btn btn-primary">Update</button>
            <a href="my_account.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?= $assets_url.'js/slim.min.js' ?>"></script>
    <script src="<?= $assets_url.'js/bootstrap.min.js'?>"></script>
</body>
</html>
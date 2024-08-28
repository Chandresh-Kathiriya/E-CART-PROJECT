<?php
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "userdetails";

    $conn = mysqli_connect($server, $username, $password, $database);
    if(!$conn){
        die ("Error". mysqli_connect_error());
    }

    $local_host_url = 'http://localhost/e_cart/';
    $assets_url = $local_host_url.'assets/';
    $product_image_path = $assets_url.'uploads/products/';

    $cookie_name = 'add_cart_items';
    $currency = 'INR';
    $stripe_api_key = 'sk_test_51Pic1BJkT4u45quDTf0mbst1xW19RQBKHTg77bko9DvFwSNibGky5WDyW8NKD0t4a60gXsevS3SZlnIldXhz4foX002G03O7Kl';
    $stripe_publisher_key = 'pk_test_51Pic1BJkT4u45quDUgiGDF1jM0xuNXYVmE1QSvWow9XzChqOcSsgjF2GcwHTdzaXETf1ENLGzomP7wjCoaOLr7E100MNsfPbcK';

    function redirect($page_name) {
        header("Location: $page_name");
    }

    function random_string(){
        $characters = uniqid().'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = substr(str_shuffle($characters), 0, 20);
        return $randstring;
    }

    function encrypt($data){
        $method = "AES-256-CBC";
        $key = "encryptionKey123";
        $options = 0;
        $iv = '1234567891011121';

        $encryptedData = openssl_encrypt($data, $method, $key, $options,$iv);
        return base64_encode($encryptedData);
    }

    function decrypt($encryptedData){
        $method = "AES-256-CBC";
        $key = "encryptionKey123";
        $options = 0;
        $iv = '1234567891011121';

        $decryptedData = openssl_decrypt(base64_decode($encryptedData), $method, $key, $options, $iv);
        return $decryptedData;
    }
?>
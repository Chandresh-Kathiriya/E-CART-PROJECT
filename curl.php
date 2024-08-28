<?php
function cURL($url, $method = 'GET', $post_data = [], $headers = [], $json_data = '') {
    // Create a new cURL resource
    $curl = curl_init();

    if (!$curl) {
        die("Couldn't initialize a cURL handle");
    }

    // Set the file URL to fetch through cURL
    curl_setopt($curl, CURLOPT_URL, $url);

    if($json_data) {
        $post_data = json_encode($json_data);
    }

    if($headers) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }

    if($method == 'POST') {
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    }

    // Follow redirects, if any
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

    // Fail the cURL request if response code = 400 (like 404 errors)
    // curl_setopt($curl, CURLOPT_FAILONERROR, true);

    // Returns the status code
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Wait 10 seconds to connect and set 0 to wait indefinitely
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);

    // Execute the cURL request for a maximum of 50 seconds
    curl_setopt($curl, CURLOPT_TIMEOUT, 50);

    // Do not check the SSL certificates
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

    // Fetch the URL and save the content in $html variable
    $result = curl_exec($curl);

    $response = [];
    // Check if any error has occurred
    if (curl_errno($curl)) {
        $response = [
            'error' => true,
            'message' => 'cURL error: ' . curl_error($curl)
        ];
    } else if($result === false) {
        $response = [
            'error' => true,
            'message' => 'Something goes wrong.'
        ];
    } else {
        $result = json_decode($result, true);
        // will display the page contents i.e its html.
        if(isset($result['error'])) {
            $response = [
                'error' => true,
                'message' => $result
            ];
        } else {
            $response = [
                'error' => false,
                'message' => $result
            ];
        }
    }

    // close cURL resource to free up system resources
    curl_close($curl);
    return $response;
}
?>
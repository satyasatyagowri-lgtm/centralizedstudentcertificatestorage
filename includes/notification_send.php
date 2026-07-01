<?php header('Content-Type: text/html; charset=utf-8'); 
function sendNotification($deviceToken, $title, $body, $data,$app_serverkey) {
    // Set your FCM server key
    $serverKey = $app_serverkey;

    // Create the notification payload
    $payload = [
        'to' => $deviceToken,
        'data' => [
            'title' => $title,
            'body' => $body,
            'custom_data_key1' => $data['key1'],
            'custom_data_key2' => $data['key2']
        ]
    ];

    // Set additional headers
    $headers = [
        'Authorization: key=' . $serverKey,
        'Content-Type: application/json'
    ];

    // Convert the payload to JSON
    $jsonPayload = json_encode($payload);

    // Create and configure the cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);

    // Send the request
    $result = curl_exec($ch);

    // Check for errors
    if ($result === false) {
        $error = curl_error($ch);
        echo "cURL Error: $error";
    }

    // Close cURL
    curl_close($ch);

    // Handle the result
    echo $result;
}

// Example usage 
/*$deviceToken = $deviceToken; // Replace with the device token of the recipient
$title = $title; 
$body = $body;
$data = $data;
[   
'key1' => 'https://hfts.in/durgaprasad/home.php?p=notifications',
'key2' => 'www.google.com' 
 
];*/

sendNotification($deviceToken, $title, $body, $data,$app_serverkey);

?>
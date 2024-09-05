<?php
include '../db/conn.php';
date_default_timezone_set('Asia/Kathmandu');
// Validate and sanitize input
$oid = isset($_REQUEST['oid']) ? mysqli_real_escape_string($conn, $_REQUEST['oid']) : '';
$amt = isset($_REQUEST['amt']) ? floatval($_REQUEST['amt']) : 0;
$refId = isset($_REQUEST['refId']) ? mysqli_real_escape_string($conn, $_REQUEST['refId']) : '';

if ($oid && $amt && $refId) {
    $sql = "SELECT * FROM orders WHERE invoice_no = '$oid'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $order = mysqli_fetch_assoc($result);

        $url = "https://uat.esewa.com.np/epay/transrec";
        $data = [
            'amt' => $order['total'],
            'rid' => $refId,
            'pid' => $order['invoice_no'],
            'scd' => 'epay_payment'
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);

        if ($response === false) {
            die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
        }

        curl_close($curl);

        $response_code = get_xml_node_value('response_code', $response);

        if (trim($response_code) == 'Success') {
            // Update the order status
            $update_sql = "UPDATE orders SET status = 'Paid', time = NOW() WHERE order_id = '" . $order['order_id'] . "'";
            if (mysqli_query($conn, $update_sql)) {
                // Display an alert and redirect
                echo '<script>alert("Payment Successful");</script>';
                echo '<meta http-equiv="refresh" content="0;url=../Frontend/home/orders.php">';
                exit(); // Ensure script termination after redirection
            } else {
                die('Database update error: ' . mysqli_error($conn));
            }
        } else {
            die('Payment verification failed.');
        }
    } else {
        die('Order not found.');
    }
} else {
    die('Invalid request parameters.');
}

function get_xml_node_value($node, $xml) {
    if ($xml === false) {
        return false;
    }
    $found = preg_match('#<' . $node . '(?:\s+[^>]+)?>(.*?)</' . $node . '>#s', $xml, $matches);
    if ($found !== false) {
        return $matches[1];
    }
    return false;
}
?>

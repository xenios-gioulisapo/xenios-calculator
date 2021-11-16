<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$data = json_decode(file_get_contents('https://xeniosmarkets.com/api/public?command=returnTicker'), true);
ini_set('display_errors', 1);
// print_r(array_keys($data));

// $array = (array) $object;  


$base_pairs = array();
$market_pairs = array();
foreach ($data as $key => $val) {
    $base_pairs[] = explode('_', $key)[0];
    $market_pairs[] = explode('_', $key)[1];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./style.css">
    <title>Calculator</title>
</head>

<body>
    <h1>XeniosMarkets Calculator</h1>
    <form action="index.php" method="GET">
        <select name="base_coin">
            <?php
            foreach (array_unique($market_pairs) as &$value) {
                echo ('<option>' . $value . '</option>');
            }
            ?>
        </select>
        <input type="text" placeholder="Amount" name="amount" required>
        <select name="market_coin">
            <?php
            foreach (array_unique($base_pairs) as &$value) {
                echo ('<option>' . $value . '</option>');
            }
            ?>
        </select>
        <button name="equals">Convert</button>
        <div>
            <?php
            if (isset($_GET['equals'])) {
                $from_coin = $_GET['base_coin'];
                $to_coin = $_GET['market_coin'];
                $amount = $_GET['amount'];
                $result = 0.0;
                if (array_key_exists($from_coin . '_' . $to_coin, $data)) {
                    $result = (float)$data[$from_coin . '_' . $to_coin]['last'] * (float)$amount;
                    printf("%f", $result); // floating point representation
                } elseif(array_key_exists($to_coin . '_' . $from_coin, $data)) {
                    $result = round(1/(float)$data[$to_coin . '_' . $from_coin]['last'] * (float)$amount, 8);
                    printf("%f", $result); // floating point representation
                } else {
                    echo('Not Valid Pair');
                }
            } else {
                echo '0';
            }

            $data = json_decode(file_get_contents('https://xeniosmarkets.com/api/public\?command\=returnTicker'), true);
            print_r($data);
            echo $data;

            ?>
        </div>
    </form>
</body>

</html>
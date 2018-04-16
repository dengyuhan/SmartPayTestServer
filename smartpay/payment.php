<?php
require_once(__DIR__ . '/alipay-sdk-PHP-3.0.0/AopSdk.php');

@$pay_type = $_GET['pay_type'];

$data = new PaymentInfo;
if ($pay_type == "wechat") {
    $data->success = true;
    $data->message = "微信支付";
    $data->params = buildWeChatTestParams();
} else if ($pay_type == "alipay") {
    $data->success = true;
    $data->message = "支付宝支付";
    $data->params = buildAliPayTestParams();
} else {
    $data->success = false;
    $data->message = "请选择支付方式";
}
//转换json 不进行encode
echo json_encode($data, JSON_UNESCAPED_UNICODE);

/**
 * 微信支付的测试参数
 * @return bool|string
 */
function buildWeChatTestParams()
{
    $opts = array('http' =>
        array(
            'method' => 'GET',
        )
    );
    $context = stream_context_create($opts);
    $result = file_get_contents("http://wxpay.wxutil.com/pub_v2/app/app_pay.php", false, $context);
    //截取出正确的json
    $new_result = substr($result, strripos($result, "{"), strlen($result) - 1);
    return $new_result;
}

/**
 * 支付宝的测试参数
 */
function buildAliPayTestParams()
{
    $test_appid = "2014100900013222";
    $test_rsa2_private = "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC98aoOKirBjPTKN4vLTi8vpYwWTLG6WJh701BnCl3C5gT3lQQGeXf+ZphXBwD9e3fRyJai9cpxiis7eUbonrqrmUoIvbyiAudoYpqnXVKTcaO/rSisr9aa7IOfMqFHEOS61nIlceJja2txCJfUNanS89aghMcvqHLcJ5m0YKN4cimbUUYPELMVI1PsCJyJUNKFiYorQq1/TWWqbvaKRaDRk8NHJgHsKtZFlyhbnm73NsP69NOdKvjQvTlLfpFZE+l8I61sy8Cz4V2E67bwJ2r9/G4f0FVt+F/U4YVegax4w8+w5Y55veazDJrm2gJ4abNk0EETZwyBLPgsbFR2/y3hAgMBAAECggEAe9iVQ7UUuaxZc3wyJvYsaAmtxGBvRYw8qAgJFZY5ujlWJcPAoyQSLAri62OCrsQRRPRf25MdU1h+hcG2jTfpiLdjAT4NPylbjsE0C0oa7E4dMX4K1kW0TMFHtMZDR93o9TWbqXSO4roIjOPIczImL4iTeYf5g8Z2VbtwSZ71FzNgmuAKLy7eobLfewXo1daqBe0ZDGk75RkQ2NR6cJmbLLMbBlTqAbQyBA8KIdue3gR5rruecgsdoMEEBnBPTrZ07U26l1+m3k2lHjE3EsztsQBMDpUwdFKuq4kk7gRBXolj8zqYoKRiOVWy88bvPz8Vx8Vo6XGa0X0S/yBnM5t3kQKBgQDopuLxmxS/3MWcJsIKRYLKUlnWe/So0HbFsV6ij/So/8rfcWkzh9k+qnnSBHtvkQFsnv0+GLd1Hy8Wad9vCyFDessvhuzQRY2Imk9FotaTkYbrchrz0reJUYY6nUklyCTxEAz2UxyLLEIQO0JAhXH8S18YNuh6hVEphmKT2NxpbQKBgQDRAY8vGqJzvznIcCdXXoJx4Ic65dnPRwoEB1abQHnpYqmf8VH/Styk7By1Ap+luP7X3T6QX4gSR4RqC5krJBoz9nAsucgo+t9aUXrhLV83hOoKWD7znX/C1+0b/osRPoexlBmaaf+n+RNIQ3hxU9uIl6WHT01daBL4GVBanxchxQKBgES/e/R1JS6E6If6FADBBaMPrqhovKVd5JsKjLJw45VE8QgSFUo67IFOEu1ykZ8oNEmKub6twxiC/IEdC/9eRJgSIxSKRFRPGUGyh5ZGRi4ZJMtSTpCaRc34HzgW3lShzfjGC26GpLqje2oceLlkNYieJR2crBn4Z0FkCqExxgAJAoGBAMdW/2NjudE/bzMWlM8lmrBV/2RTWPvyu0DAZv/H7P6FVVbw6M3ebrb1YyPZDr8WxCjKISO9maAlictCqKGW2074GmDuCFPdgi04TUR667eeE0IujEv5yaLiIolyqtyVkQHzSMAXnPht/NANWdBstJOAXyXAov8VhhIOwq7L0VopAoGAeZBY2TXgCvvD/7sDQ8hQjxEI0IVORT35bGoNtSce2QDa9lG5cwXOn775TcAhCiWAmLexRKo+1Q/aYVAYTTB6ImEvE5DVb+3vz7a+1Wgw8yz+pijwabVRT0oggBYRWFyNRVjIrrwg3Tg5Me+P65/p64ztOhDotbQl6mtF0CshC44=";

    $title = "支付测试";
    $desc = "SmartPay支付测试";
    $order_number = time();
    $timestamp = date("Y-m-d h:m:s", time());

    $params = array(
        "app_id" => $test_appid,
        'biz_content' => "{\"timeout_express\":\"30m\",\"product_code\":\"QUICK_MSECURITY_PAY\",\"total_amount\":\"0.01\",\"subject\":\"" . $title . "\",\"body\":\"" . $desc . "\",\"out_trade_no\":\"" . $order_number . "\"}",
        "charset" => "utf-8",
        "method" => "alipay.trade.app.pay",
        "sign_type" => "RSA2",
        "timestamp" => $timestamp,
        "version" => "1.0"
    );

    $alipay = new AopClient;
    $alipay->rsaPrivateKey = $test_rsa2_private;
    $sign = $alipay->rsaSign($params, "RSA2");

    $params['sign'] = $sign;

    $order_str = "";
    foreach ($params as $key => $value) {
        $order_str .= $key . "=" . urlencode($value) . "&";
    }
    $order_str = rtrim($order_str, "&");
    return $order_str;
}

class PaymentInfo
{
    public $success;
    public $message;
    public $params;
}

?>


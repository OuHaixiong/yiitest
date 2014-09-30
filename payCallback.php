<?php
$data = array_merge($_GET, $_POST);
$boolean = file_put_contents('temp/abc', json_encode($data));

$request = file_get_contents('php://input');
if( strlen($request) > 0 ) {
    file_put_contents('temp/xml', $request);
}

/**
 * 通过curl发送HTTP请求
 *
 * @param string $url 请求地址
 * @param array $data 发送数据
 * @param string $method 请求方式: GET/POST
 * @param integer $timeout 链接超时秒数
 * @param string $refererUrl 请求来源地址
 * @param boolean $proxy 是否启用代理
 * @param string $contentType     application/x-www-form-urlencoded     multipart/form-data    application/json
 * @return boolean | mixed
 */
function sendRequest($url, $data = null, $method = 'GET', $timeout = 30, $refererUrl = '', $proxy = false)
{
    $method = strtoupper($method);
    if (!in_array($method, array('GET', 'POST'))) {
        return false;
    }
    if ('GET' === $method) {
        if (!empty($data)) {
            if (is_string($data)) {
                $url .= (strpos($url, '?') === false ? '?' : '') . $data;
            } else {
                $url .= (strpos($url, '?') === false ? '?' : '') . http_build_query($data);
            }
        }
    }
    $ch = curl_init($url); // curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    if ('POST' === $method) {
        curl_setopt($ch, CURLOPT_POST, 1); // curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        if (!empty($data)) {
            if (is_string($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // http_build_query对应application/x-www-form-urlencoded
            }
        }
    }
    if ($refererUrl) {
        curl_setopt($ch, CURLOPT_REFERER, $refererUrl);
    }
    if ($proxy) {
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
    }
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

// $url = 'http://113.108.113.193:8885/thirdPay/notify.html';
$url = 'http://pay.csc86.com/thirdPay/notify.html';

$string = sendRequest($url, $data);
//echo $string;exit;

echo 'success';
<?php
//phpinfo();
// attach

$data = array(
//     '', 
    'memberId' => 'dcfab3e4-8d72-4795-a64c-2c51e3664a99',
    'ip' => '123.456.789.683',
    'module' => 'wslm',
    'payWay' => 'wxpay',
    'action' => 'pay',
);
$json = json_encode($data);
var_dump($json);

// {memberId:'',ip:'',balance:'0',action:'pay',module:'wslm',payWay:'wxpay',shirdShowId:''}
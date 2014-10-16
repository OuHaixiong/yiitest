<?php

$worker = new GearmanWorker();
$worker->addServer('192.168.17.130', 4730);
$worker->addFunction('title', 'title_function');
while($worker->work()) {
    sleep(1); // 无限回圈，并让 CPU 休息一下，其实这里是不需要些的 。里work里面已经实现
}

function title_function($job) {
    $str = $job->workload(); // 貌似只可以传一个参数 ，$data = unserialize($job->workload());
    return strlen($str);
}

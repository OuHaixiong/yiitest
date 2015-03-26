
<?php
//ignore_user_abort(true); // 后台运行
//  #!/usr/bin/php -q
set_time_limit(0); // 运行不超时(取消脚本运行时间的超时上限)
$worker = new GearmanWorker();
$boolean = $worker->addServer('192.168.17.130', 4730);
$worker->addFunction('title', 'title_function');
while($worker->work());
// {
 //   sleep(1); // 无限回圈，并让 CPU 休息一下，其实这里是不需要些的 。里work里面已经实现
//}

function title_function($job) {
    $str = $job->workload(); // 貌似只可以传一个参数 ，$data = unserialize($job->workload());
    return strlen($str);
}

// 可以通过如下命令让php在后端运行
//nohup /usr/bin/php /home/u32/www/yiitest/workers/test.php &
//通过 jobs -l 可以查看在后端运行的程序
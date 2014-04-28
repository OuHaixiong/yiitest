<?php
foreach ($data as $k=>$v) : ?>
<?php echo $v->username . '<br />'; ?>
<?php endforeach; ?>

<?php 
$params = array(
    'pages'           => $pagination,
    'firstOffset'     => 4,                  //在该页后用...***...显示页码
    'maxButtonCount'  => 7,                  //每页显示页码
    'showInput'       =>false,               //显示输入框，默认显示，当总页数小于每页显示页码时，打开这个也可以显示跳转
    'showTotal'       =>false,               //不显示总数，默认显示
    'showGoto'        =>false,               //不显示跳转，默认显示
    'pageName'        =>$pagination->pageVar //默认翻页地址参数名称,必需要和$P->pageVar值保持一致
);
$this->widget('CCscBasePagerWidget', $params);
?>

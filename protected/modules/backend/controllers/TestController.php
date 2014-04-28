<?php

/**
 * 测试
 * @author Bear
 *
 */
class TestController extends CController
{
    public function init() {
    }
    
    public function actionIndex() {
//         $this->module->postPerPage //<!-- 获取模块初始化值
        var_dump(Yii::app()->controller->module->postPerPage . 'wo kao'); //<!-- 这个也和上面是一样的意思
    }
}

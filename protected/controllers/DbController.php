<?php

/**
 * 数据库练习
 * @author Bear
 * @version 1.0.0
 * @copyright http://xiqiyanyan.com
 * @created 2014-3-10 09:37
 */
class DbController extends Controller
{
    public function actionTest() {
        var_dump(Yii::app()->db);exit;
    }
}

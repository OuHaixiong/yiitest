<?php

/**
 * 二维码生成练习
 * @author Bear
 * @version 1.0.0
 * @copyright http://maimengmei.com
 * @created 2014-12-09 16:37
 */
class QrcodeController extends Controller
{
    /**
     * 在浏览器中预览二维码
     */
    public function actionView() { // Yii::app()->BasePath    /home/u32/www/yiitest/protected
        $str = 'http://maimengmei.com';
//         require_once Yii::getPathOfAlias('ext') . '/phpqrcode.php';
        require_once Yii::getPathOfAlias('system') . '/../phpqrcode/phpqrcode.php';
        QRcode::png($str, false, 0, 11); // 
    }
    
    /**
     * 生成二维码保存进文件
     */
    public function actionSave() {
        $text = 'http://www.maimengmei.com';
        require_once Yii::getPathOfAlias('system') . '/../phpqrcode/phpqrcode.php';
        $filePath = ROOT_PATH . '/img/qrcode.png';
        QRcode::png($text, $filePath, 3, 12, 1);
    }
    
    /**
     * 查看二维码
     */
    public function actionShow() {
        require_once Yii::getPathOfAlias('system') . '/../phpqrcode/qrlib.php';
        QRcode::png('some othertext 123', false, 2, 8, 0);
    }

}

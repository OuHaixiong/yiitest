<?php

/**
 * 后台模块
 * 新建的模块必须在main.php中配置 'modules' => array('backend' ... 
 * @author Bear
 * @version 1.0.0
 * @copyright http://xiqiyanyan.com
 * @created 2014-2-20 14:38 
 */
class BackendModule extends CWebModule
{
    public $postPerPage;
    
    public function init() {
    	$this->setImport(array('backend.models.*'));
    }
}

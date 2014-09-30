<?php

/**
 * Cache练习
 * @author Bear
 * @version 1.0
 * @copyright http://maimengmei.com
 * @created 2014-04-30 11:09
 */
class CacheController extends Controller
{
    /**
     * 片段缓存
     */
    public function actionFragment() {
    	if ($this->beginCache('abc', array('duration'=>3600))) :
    	echo 'ABCdsaf我冻死了122333尼玛发';
    	echo '<br />我也懂呀ko《br/>';
    	$this->endCache();
    	endif;
    	echo 'wo che';
    }
    

}

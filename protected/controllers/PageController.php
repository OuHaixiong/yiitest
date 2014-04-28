<?php

/**
 * 翻页练习
 * @author Bear
 * @version 1.0.0
 * @copyright http://xiqiyanyan.com
 * @created 2014-3-10 09:37
 */
class PageController extends Controller
{
    public function init() {

    }
    
    /**
     * 查找一批用户
     */
    public function actionSearch() {
        if (!Yii::app()->request->isAjaxRequest) {
            $pageSize = 3;
            $page = Yii::app()->request->getParam('page', 3);
            $admin = new Admin();
            $criteria = $admin->search($pageSize);
            $count = Admin::model()->count($criteria);
            $pagination = new CPagination($count); //总花商品条数
            $pagination->pageSize = $pageSize; //每页显示20条
            $pagination->applyLimit($criteria); // 对查询 应用 limit
            // $pagination->getItemCount() //总记录数
            // $pagination->getCurrentPage() 当前页,第一页为0
            // $pagination->getLimit() // 每页多少条
            // $pagination->getOffset() // 从第几个开始取
            // $pagination->getPageCount() // 总共多少页
            // $pagination->getPageSize()  // 每页多少条
            // $pagination->createPageUrl($this, $page) //生成每页链接
            // $P->pageVar	= 'p';					//翻页参数名称
    
            $data = Admin::model()->findAll($criteria);
            $this->setParam('data', $data);
            $this->setParam('pagination', $pagination);
            $this->render('search');
        }
    }
    public function onEndRequest() {}
    
    /**
     * 练习
     */
    public function actionTest() {
//         $this->attachEventHandler('onAfter', array('Events', 'getB')); //<!-- 类中的方法，不管静态或非静态都可以
//         $this->attachEventHandler('onAfter', 'Events2::abc'); //<!--  类中的静态方法或非静态方法也可以 
//         $this->attachEventHandler('onAfter', 'wokao'); //<!--  字符串，直接有的函数
        
        if ($this->hasEventHandler('onAfter')) {
        	$this->onAfter(new CEvent($this)); //<!-- 这里可以传CEvent对象，也可以传$this对象
        }
        
        //Common_Tool::prePrint(Events::getB());
//         $this->attachEventHandler('OnAddAfter', array(array('PushWeixin','memberMenu')),array($this->userID));


    }
    
    public function onAfter($event) { //<!--  这里的参数是传到方法的参数，参数最好能就这样写
        $this->raiseEvent('onAfter',$event);
    }

    
    
    
}

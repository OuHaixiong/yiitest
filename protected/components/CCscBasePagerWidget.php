<?php
/**
 * 使用方法：
 	private function getPagetag($tatal){
		$P = new CPagination();
		$P->setPageSize( $this->limit ); 	//每页显示20条
		$P->setItemCount( $tatal ); 		//总花商品条数
		$P->pageVar	= 'p';					//翻页参数名称
		$params = array(
				'pages'=>$P, 
				'firstOffset'=>4, 			//在该页后用...***...显示页码
				'maxButtonCount'=>7, 		//每页显示页码
				'showInput'=>false 			//显示输入框，默认显示，当总页数小于每页显示页码时，打开这个也可以显示跳转
 				'showTotal'=>false, 		//不显示总数，默认显示
 				'showGoto'=>false, 			//不显示跳转，默认显示
 				'pageName'=>'p', 			//默认翻页地址参数名称,必需要和$P->pageVar值保持一致
		);		
		return $this->widget('CCscBasePagerWidget',$params ,true);
 	}
 */
class CCscBasePagerWidget extends CLinkPager {
	public $firstOffset=5;
    public $OffsetHtml="<li>...</li>";
    public $showTotal = true;	//是否显示总数
    public $showGoto  = true;	//是否显示跳转功能
    public $showInput = true;	//显示输入框，默认显示，当总页数小于每页显示页码时，打开这个也可以显示跳转
    public $pageName  = 'page';	//默认翻页地址参数名称
    
    
	/**
	 * @var string the CSS class for the first page button. Defaults to 'first'.
	 * @since 1.1.11
	 */
	public $firstPageCssClass=self::CSS_FIRST_PAGE;
	/**
	 * @var string the CSS class for the last page button. Defaults to 'last'.
	 * @since 1.1.11
	 */
	public $lastPageCssClass=self::CSS_LAST_PAGE;
	/**
	 * @var string the CSS class for the previous page button. Defaults to 'previous'.
	 * @since 1.1.11
	 */
	public $previousPageCssClass=self::CSS_PREVIOUS_PAGE;
	/**
	 * @var string the CSS class for the next page button. Defaults to 'next'.
	 * @since 1.1.11
	 */
	public $nextPageCssClass=self::CSS_NEXT_PAGE;
	/**
	 * @var string the CSS class for the internal page buttons. Defaults to 'page'.
	 * @since 1.1.11
	 */
	public $internalPageCssClass=self::CSS_INTERNAL_PAGE;
	/**
	 * @var string the CSS class for the hidden page buttons. Defaults to 'hidden'.
	 * @since 1.1.11
	 */
	public $hiddenPageCssClass=self::CSS_HIDDEN_PAGE;
	/**
	 * @var string the CSS class for the selected page buttons. Defaults to 'selected'.
	 * @since 1.1.11
	 */
	public $selectedPageCssClass=self::CSS_SELECTED_PAGE;
    
    /*
     * 重构 CLinkPager::init()方法
     */
    public function init() {
    	if($this->firstPageLabel===null)
			$this->firstPageLabel="1";//自定义首页
		if($this->lastPageLabel===null)
			$this->lastPageLabel=$this->getPageCount();//自定义未页
						
        if($this->nextPageLabel===null)
			$this->nextPageLabel="下一页";
		if($this->prevPageLabel===null)
			$this->prevPageLabel="上一页";

        $this->header = '';
        $pageName = $this->pageName? $this->pageName:'page';
        $this->footer = ($this->getPageCount()>1)? '<script>$PHP_pageto_submit=function(pgname,o,max){var pagename=pgname||"page",obj=o||document,pageNo="",pageUri=window.location.search,Reg=new RegExp("([?&]"+pagename+"=)([^&]*)");for(var inputs=o.getElementsByTagName("input"),i=inputs.length;i--;){if(inputs[i].name==pagename){pageNo=inputs[i].value}}if(!/^\d+$/.test(pageNo)){return false;}
var thispage=Reg.test(pageUri)?Reg.exec(pageUri)[2]:1;if((thispage-0)>=max&&(pageNo-0)>max){return false;}if((pageNo-0)>max){pageNo=max}if(Reg.test(pageUri)==true){pageUri=pageUri.replace(Reg,"$1"+pageNo)}else{var s=/^\?/.test(pageUri)?"&":"?";pageUri+=s+pagename+"="+pageNo;};window.location=window.location.href.split("?")[0]+pageUri;return false;}</script>':'';
    }
    
    /*
     * 重构 CLinkPager::run()方法
     */
    public function run() {
        $buttons=$this->createPageButtons();
        
        echo $this->header;
		echo CHtml::tag('ul',$this->htmlOptions,implode("\n",$buttons));
		echo $this->footer;

    }
    

    
	protected function createPageButtons()
	{
		if(($pageCount=$this->getPageCount())<=1)
			return array();

		
		
		$currentPage=$this->getCurrentPage(false); // currentPage is calculated in getPageRange()

		$buttons=array();
		
		// prev page
		if(($page=$currentPage-1)<0)
			$page=0;
		if($currentPage>=0)
			$buttons[]=$this->createPageButton($this->prevPageLabel,$page,$this->previousPageCssClass, $currentPage<1,false);

		// first page
		$buttons[]=$this->createPageButton($this->firstPageLabel,0,$this->firstPageCssClass,false,$currentPage==0);
		
		if($pageCount > $this->maxButtonCount){	
			list($beginPage,$endPage)=$this->getPageRange();
			if($currentPage >= $this->firstOffset){
				// ...
				if($beginPage >= 2 )
					$buttons[]=$this->OffsetHtml;
				
				// internal pages
				for($i=$beginPage;$i<=$endPage;++$i)
					$buttons[]=$this->createPageButton($i+1,$i,$this->internalPageCssClass,false,$i==$currentPage);
					
				// ...
				if($endPage+1<$pageCount-1 )
					$buttons[]=$this->OffsetHtml;
					
			}else{
				// internal pages
				for($i=1;$i<$this->firstOffset;++$i)
					$buttons[]=$this->createPageButton($i+1,$i,$this->internalPageCssClass,false,$i==$currentPage);
				$buttons[]=$this->OffsetHtml;
			}
		}else{
				// internal pages
				for($i=1;$i<=$pageCount-2;++$i)
					$buttons[]=$this->createPageButton($i+1,$i,$this->internalPageCssClass,false,$i==$currentPage);
			
		}

		// last page
		$buttons[]=$this->createPageButton($this->lastPageLabel,$pageCount-1,$this->lastPageCssClass,false,$currentPage==$pageCount-1);

		// next page
		if(($page=$currentPage+1)>=$pageCount-1)
			$page=$pageCount-1;
		if($currentPage!=$this->getPageCount())
			$buttons[]=$this->createPageButton($this->nextPageLabel,$page,$this->nextPageCssClass,$currentPage>=$pageCount-1,false);

		if($pageCount > $this->maxButtonCount || $this->showInput){
			if($this->showTotal){
				$buttons[]="<li><span>共</span>{$this->pageCount}<span>页</span></li>";
			}
			if($this->showGoto){
				$buttons[]='<li><form action="" method="get" onsubmit="return $PHP_pageto_submit(\''.$this->pageName.'\',this,'.$this->pageCount.');"><span>到</span><input type="text" name="'.$this->pageName.'" class="pageNo" onKeyUp="this.value=this.value.replace(/[^0-9]/g,\'\')"><span>页</span><input type="submit" class="submit_go" value="确定" /></form></li>';
			}		
		}
		
		return $buttons;
	}
	
	/**
	 * @return array the begin and end pages that need to be displayed.
	 */
	protected function getPageRange()
	{
		$currentPage=$this->getCurrentPage();
		$pageCount=$this->getPageCount();

		$beginPage=max(0, $currentPage-(int)(($this->maxButtonCount-2)/2));
		if(($endPage=$beginPage+($this->maxButtonCount-2)-1)>=$pageCount-1)
		{
			$endPage=$pageCount-2;
			$beginPage=max(0,$endPage-($this->maxButtonCount-2)+1);
		}
		return array($beginPage,$endPage);
	}

	protected function createPageButton($label,$page,$class,$hidden,$selected)
	{
		$SHtml=CHtml::link($label,$this->createPageUrl($page));
		if($hidden or $selected){
			$class.=' '.($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);
			$SHtml =' '.($hidden ? '<span>'.$label.'</span>' : $SHtml);	
		}
		return '<li class="'.$class.'">'.$SHtml.'</li>';
	}
	    
}
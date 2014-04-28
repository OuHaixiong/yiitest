<?php
class MyFilter extends CFilter
{
    public function init() {
    	echo '<br /> 这里是初始化(init) MyFilter <br />';
    }
    
    protected function preFilter ($filterChain)
    {
        // logic being applied before the action is executed
        echo "-->MyFilter-->pre 执行控制器之前";
        return true; // false if the action should not be executed
    }
    
    protected function postFilter ($filterChain)
    {
        echo "-->MyFilter-->post 执行控制器之后";
    }
    
}
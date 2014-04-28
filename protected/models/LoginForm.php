<?php

/**
 * 表单练习
 * @author Bear
 * @version 1.0.0
 * @copyright http://xiqiyanyan.com
 * @created 2014-2-21 09:17
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe = false;
	
    private $_identity;
    
    /**
     * //<!-- 这里定义验证规则
     * @see CModel::rules()
     */
    public function rules() {
        return array(
        	array('username, password', 'required'),
            array('username', 'length', 'min'=>3, 'max'=>20,
                    'tooShort'=>'用户名太短了，至少三个字符', 'tooLong'=>'用户名不能超过20个字符'
                ), // username 必须大于 3 小于 20 字节
            array('rememberMe', 'boolean'),
            array('password', 'authenticate', 'ni'=>'hao'),
                // 在注册场景中, password 必须和 password2 一样
//                 array('password', 'compare', 'compareAttribute'=>'password2', 'on'=>'register'),
            // 在登录场景中, password 必须被校验 array('password', 'authenticate', 'on'=>'login'),
        );
    }
    
    /**
     * 
     * //<!-- (校验方法必须是以下结构)
     * @param string $attribute 被验证的属性的名字 
     * @param array $params 指定了校验规则 (指配置中的第三个以后的参数)
     */
    public function authenticate($attribute, $params) {
    	if (!$this->hasErrors()) { // 我们只想在没有输入错误时执行校验
    	    $this->_identity = new UserIdentity($this->username, $this->password);
    	    if (!$this->_identity->authenticate()) {
    	    	$duration = $this->rememberMe ? 3600*24*30 : 0; // 30天
    	    	Yii::app()->user->login($this->_identity, $duration);
    	    } else {
    	    	$this->addError('password', 'Incorrect password.');
    	    }
    	}
    }
    
    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login()
    {
        if($this->_identity===null)
        {
            $this->_identity=new UserIdentity($this->username,$this->password);
            $this->_identity->authenticate();
        }
        if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
        {
            $duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
            Yii::app()->user->login($this->_identity,$duration);
            return true;
        }
        else
            return false;
    }

}

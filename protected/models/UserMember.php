<?php

/**
 * tb_user_member 实体类
 * @author Bear
 * @version 1.0
 * @copyright http://maimengmei.com
 * @created 2014-12-18 11:15
 */
class UserMember extends CActiveRecord
{
 
    public function tableName(){
        return 'tb_user_member';
    }

    /**
     * 保存一条记录
     * <!-- ActiveRecord 的save方法有防止sql注入
     * @param unknown_type $data
     * @return boolean
     */
    public function create($data) {
        $this->account_num = $data['accountNum'];
        $this->nickname = $data['nickname'];
        $this->account_email = $data['email'];
        $this->phone_num = $data['phoneNum'];
        $this->sign = $data['sign']; // `sign` varchar(51) NOT NULL DEFAULT '' COMMENT '签名',
        return $this->save();
    }
	
}

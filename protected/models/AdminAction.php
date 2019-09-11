<?php


/**
 * 测试
 * @author OuHaixiong
 * @version 1.0.0
 * @copyright gyt
 * @datetime 2019年9月11日 上午10:57:29
 */
class AdminAction extends CActiveRecord
{

/*     CREATE TABLE "public"."admin_action" (
        "id" int8 NOT NULL DEFAULT nextval('admin_action_id_seq'::regclass),
        "controller_id" int8 DEFAULT 0,
        "action" varchar(80) COLLATE "pg_catalog"."default" DEFAULT ''::character varying,
        "url_path" varchar(100) COLLATE "pg_catalog"."default" DEFAULT ''::character varying,
        "name" varchar(200) COLLATE "pg_catalog"."default" DEFAULT ''::character varying,
        "create_time" int4 DEFAULT 0,
        "update_time" int4 DEFAULT 0,
        CONSTRAINT "admin_action_pkey" PRIMARY KEY ("id")
        )
        ;
    
    ALTER TABLE "public"."admin_action"
        OWNER TO "root";
    
        CREATE UNIQUE INDEX "url_path_index" ON "public"."admin_action" USING btree (
            "url_path" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST
            );
    
        COMMENT ON COLUMN "public"."admin_action"."controller_id" IS '大功能id';
    
        COMMENT ON COLUMN "public"."admin_action"."action" IS '行为名';
    
        COMMENT ON COLUMN "public"."admin_action"."url_path" IS '权限（访问路径）';
    
        COMMENT ON COLUMN "public"."admin_action"."name" IS '权限名';
    
        COMMENT ON COLUMN "public"."admin_action"."create_time" IS '创建时间';
    
        COMMENT ON COLUMN "public"."admin_action"."update_time" IS '更新时间';
    
        COMMENT ON TABLE "public"."admin_action" IS '权限表';
 */    


	/**
	 * @return string the associated database table name
	 */
    public function tableName() {
        return 'admin_action';
    }
    
    /**
     * Returns the static model of the specified AR class.
     * The model returned is a static instance of the AR class.
     * It is provided for invoking class-level methods (something similar to static class methods.)
     *
     * EVERY derived AR class must override this method as follows,
     * <pre>
     * public static function model($className=__CLASS__)
     * {
     *     return parent::model($className);
     * }
     * </pre>
     *
     * @param string $className active record class name.
     * @return CActiveRecord active record model instance.
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
// 			'id' => 'Id',
// 			'username' => 'Username',
// 			'password' => 'Password',
// 			'email' => 'Email',
// 			'profile' => 'Profile',
		);
	}

}

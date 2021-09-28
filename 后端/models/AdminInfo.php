<?php

namespace app\models;

use Yii;
use app\models\AccountUserGroup;

/**
 * This is the model class for table "{{%admin_info}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $we7_user_id 微擎用户ID
 * @property int $app_max_count 创建小程序最大数量
 * @property string $permissions 账户权限
 * @property string $remark 备注
 * @property string $is_delete 是否删除
 * @property string $expired_at 账户过期时间
 * @property int $is_default 是否使用默认权限
 * @property string $secondary_permissions 二级权限
 * @property User $user
 * @property UserIdentity $identity
 * @property int $user_group_id 用户组ID
 * @property string $subjoin_permissions 附加权限
 */
class AdminInfo extends ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin_info}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'permissions', 'we7_user_id'], 'required'],
            [['user_id', 'app_max_count', 'is_delete', 'we7_user_id', 'is_default', 'user_group_id'], 'integer'],
            [['permissions', 'secondary_permissions', 'subjoin_permissions'], 'string'],
            [['expired_at'], 'safe'],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户 ID',
            'we7_user_id' => 'We7 User ID',
            'app_max_count' => 'App Max Count',
            'permissions' => '用户权限',
            'remark' => 'Remark',
            'is_delete' => 'Is Delete',
            'expired_at' => 'Expired At',
            'is_default' => '是否使用默认权限',
            'secondary_permissions' => '二级权限',
            'user_group_id' => '用户组ID',
            'subjoin_permissions' => '附加权限',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getIdentity()
    {
        return $this->hasOne(UserIdentity::class, ['user_id' => 'user_id']);
    }

    public function getUserGroup()
    {
        return $this->hasOne(AccountUserGroup::class, ['id' => 'user_group_id']);
    }
}

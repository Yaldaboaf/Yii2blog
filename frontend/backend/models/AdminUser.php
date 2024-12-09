<?php

namespace app\backend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $passwordHash
 * @property int|null $isAdmin
 * @property string $createdAt
 * @property string $updatedAt
 * @property string $authKey
 *
 * @property Accesstoken[] $accesstokens
 * @property Blogpost[] $blogposts
 */
class AdminUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'passwordHash', 'authKey'], 'required'],
            [['isAdmin'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['username', 'email', 'passwordHash', 'authKey'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['authKey'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'passwordHash' => 'Password Hash',
            'isAdmin' => 'Is Admin',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'authKey' => 'Auth Key',
        ];
    }

    /**
     * Gets query for [[Accesstokens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAccesstokens()
    {
        return $this->hasMany(Accesstoken::class, ['userId' => 'id']);
    }

    /**
     * Gets query for [[Blogposts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBlogposts()
    {
        return $this->hasMany(Blogpost::class, ['userId' => 'id']);
    }
}

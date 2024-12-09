<?php

namespace backend\models;
use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;

class User extends ActiveRecord implements IdentityInterface
{

    public static function tableName()
    {
        return '{{%user}}';
    }

    
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

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'passwordHash' => 'Password Hash',
            'isAdmin' => 'Is Admin',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'authKey' => 'Auth Key'
        ];
    }
    

    public static function findIdentityByAccessToken($token, $type = null)
    {
        $accessTokenObj = AccessToken::findOne(['token' => $token]);

        if ($token) {
            return static::findOne($accessTokenObj->userId);
        }
    
        return null;
    }

    public function getId()
    {
        return $this->id;
    }


    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->passwordHash);
    }

    public function fields()
    {
        return [
            'id',
            'email',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function getAccesstokens()
    {
        return $this->hasMany(Accesstoken::class, ['userId' => 'id']);
    }

    public function getBlogposts()
    {
        return $this->hasMany(Blogpost::class, ['userId' => 'id']);
    }
}

<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class BlogPost extends ActiveRecord
{
    public static function tableName()
    {
        return 'blogPosts';
    }

    public function fields()
    {
        return [
            'id',
            'text',
            'createdAt',
            'user' => function ($model) {
                return [
                    'email' => $model->user->email
                ];
            }
        ];
    }

    public function rules()
    {
        return [
            [['userId', 'text'], 'required'],
            [['userId'], 'integer'],
            [['text'], 'string'],
            [['createdAt'], 'safe'],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'text' => 'Text',
            'createdAt' => 'Created At',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }
} 
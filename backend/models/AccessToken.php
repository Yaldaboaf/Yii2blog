<?php

namespace backend\models;

use yii\db\ActiveRecord;

class AccessToken extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%accessTokens}}';
    }

    public function rules()
    {
        return [
            [['userId', 'token'], 'required'],
            ['userId', 'integer'],
            ['token', 'string', 'length' => 32],
        ];
    }
} 
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

    public static function getPosts($limit, $offset)
    {
        return self::find()
            ->with('user')
            ->orderBy(['createdAt' => SORT_DESC])
            ->limit($limit)
            ->offset($offset)
            ->asArray()
            ->all();
    }

    public static function getTotalCount()
    {
        return self::find()->count();
    }

    public static function getUserPosts($username, $limit, $offset)
    {
        $user = User::findOne(['username' => $username]);
        if (!$user) {
            return null; // или выбросить исключение
        }

        return self::find()
            ->with('user')
            ->where(['user_id' => $user->id])
            ->orderBy(['createdAt' => SORT_DESC])
            ->limit($limit)
            ->offset($offset)
            ->asArray()
            ->all();
    }

    public static function createPost($accessToken, $text)
    {
        $token = AccessToken::findOne(['token' => $accessToken]);
        if (!$token) {
            return [
                'success' => false,
                'message' => 'Недействительный токен доступа'
            ];
        }

        $post = new self();
        $post->userId = $token->userId;
        $post->text = $text;

        if ($post->save()) {
            return [
                'success' => true,
                'message' => 'Пост успешно опубликован',
                'post_id' => $post->id
            ];
        }

        return [
            'success' => false,
            'message' => 'Ошибка при публикации поста',
        ];
    }
} 
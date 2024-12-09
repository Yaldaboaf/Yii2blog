<?php

use yii\db\Migration;

Class m130524_201442_init extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'passwordHash' => $this->string()->notNull(),
            'isAdmin' => $this->boolean()->defaultValue(false),
            'createdAt' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updatedAt' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'authKey' => $this->string()->notNull()->unique(),
        ]);

        $this->createTable('{{%accessTokens}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'token' => $this->string(32)->notNull(),
            'createdAt' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'FOREIGN KEY (userId) REFERENCES {{%user}}(id) ON DELETE CASCADE',
        ]);

        $this->createTable('{{%blogPosts}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'text' => $this->text()->notNull(),
            'createdAt' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'FOREIGN KEY (userId) REFERENCES {{%user}}(id) ON DELETE CASCADE',
        ]);
        $this->insert('user', [
            'username' => 'user',
            'email'=> 'user@example.com',
            'isAdmin'=> 0,
            'passwordHash' => Yii::$app->security->generatePasswordHash('123'), // Хэширование пароля
            'authKey' => Yii::$app->security->generateRandomString(), // Генерация auth_key
            // Добавьте другие необходимые поля
        ]);
        $this->insert('user', [
            'username' => 'admin',
            'email'=> 'admin@example.com',
            'passwordHash' => Yii::$app->security->generatePasswordHash('123'), // Хэширование пароля
            'authKey' => Yii::$app->security->generateRandomString(), // Генерация auth_key
            'isAdmin' => 1, 
        ]);
        $this->insert('blogposts', [
            'userId' => '1',
            'text' => 'SomeUserText'
        ]);
        $this->insert('blogposts', [
            'userId' => '2',
            'text' => 'SomeAdminText'
        ]);
    }

    public function safeDown()
    {   
        $this->dropTable('{{%blogPosts}}');
        $this->dropTable('{{%accessTokens}}');
        $this->dropTable('{{%user}}');
    }
}
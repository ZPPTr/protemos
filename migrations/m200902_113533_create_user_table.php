<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m200902_113533_create_user_table extends Migration
{
    const USER_TABLE_NAME = '{{%user}}';
    const USER_PROFILE_TABLE_NAME = '{{%user_profile}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::USER_TABLE_NAME, [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'authKey' => $this->string(32)->notNull(),
            'passwordHash' => $this->string()->notNull(),
            'passwordResetToken' => $this->string()->unique()->null(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'createdAt' => $this->integer()->notNull(),
            'updatedAt' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable(self::USER_PROFILE_TABLE_NAME, [
            'user_id' => $this->primaryKey(),
            'firstname' => $this->string(64),
            'country_id' => $this->integer(11)->notNull(),
            'company_name' => $this->string(64)->null()
        ], $tableOptions);

        $this->createIndex('idx-user_name', self::USER_TABLE_NAME, 'username', true);
        $this->createIndex('idx-email', self::USER_TABLE_NAME, 'email', true);
        $this->addForeignKey('fk-user', self::USER_PROFILE_TABLE_NAME, 'user_id', self::USER_TABLE_NAME, 'id', 'cascade', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-user', self::USER_PROFILE_TABLE_NAME);
        $this->dropTable(self::USER_PROFILE_TABLE_NAME);
        $this->dropTable(self::USER_TABLE_NAME);
    }
}

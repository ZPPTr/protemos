<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client}}`.
 */
class m200902_131110_create_client_table extends Migration
{
    const CLIENT_TABLE_NAME = '{{%client}}';
    const PAYMENT_METHOD_TABLE_NAME = '{{%payment_method}}';
    const CLIENT_PAYMENT_METHODS_TABLE_NAME = '{{%client_payment_methods}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::CLIENT_TABLE_NAME, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'name' => $this->string(64)->notNull(),
            'country_id' => $this->integer(11)->notNull(),
            'status' => 'enum("Active", "Inactive", "Blacklisted") DEFAULT "Active"',
            'note' => $this->string(1024)->null()
        ], $tableOptions);

        $this->createTable(self::PAYMENT_METHOD_TABLE_NAME, [
            'id' => $this->primaryKey(),
            'name' => $this->string(64),
            'type' => 'enum("PayPal", "Skrill", "Bank transfer") DEFAULT NULL',
            'description' => $this->string(1024)->null()
        ], $tableOptions);

        $this->createTable(self::CLIENT_PAYMENT_METHODS_TABLE_NAME, [
            'client_id' => $this->integer(11),
            'payment_method_id' => $this->integer(11),
        ], $tableOptions);

        $this->createIndex('idx-name', self::CLIENT_TABLE_NAME, 'name', true);
        $this->createIndex('idx-client-payment_method', self::CLIENT_PAYMENT_METHODS_TABLE_NAME, ['client_id', 'payment_method_id']);

        $this->addForeignKey('fk-client-user', self::CLIENT_TABLE_NAME, 'user_id', '{{%user}}', 'id', 'cascade', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-client-user', self::CLIENT_TABLE_NAME);

        $this->dropTable(self::CLIENT_PAYMENT_METHODS_TABLE_NAME);
        $this->dropTable(self::PAYMENT_METHOD_TABLE_NAME);
        $this->dropTable(self::CLIENT_TABLE_NAME);
    }
}

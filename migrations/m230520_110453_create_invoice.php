<?php

use yii\db\Migration;

/**
 * Class m230520_110453_create_invoice
 */
class m230520_110453_create_invoice extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invoice}}',[
            "id" => $this->primaryKey(),
            "title" => $this->string(),
            "price" => $this->integer(),
            "created_at" => $this->timestamp(),
            "updated_at" => $this->timestamp(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230520_110453_create_invoice cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230520_110453_create_invoice cannot be reverted.\n";

        return false;
    }
    */
}

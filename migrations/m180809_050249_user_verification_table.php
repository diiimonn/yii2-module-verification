<?php

use yii\db\Migration;

/**
 * Class m180809_050249_user_verification_table
 */
class m180809_050249_user_verification_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('verification', [
            'email' => $this->primaryKey(),
            'token' => $this->string()->notNull()->unique(),
            'invalidate_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->alterColumn('verification', 'email', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180809_050249_user_verification_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180809_050249_user_verification_table cannot be reverted.\n";

        return false;
    }
    */
}

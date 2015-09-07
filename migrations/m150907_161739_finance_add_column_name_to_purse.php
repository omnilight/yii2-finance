<?php

use yii\db\Migration;

class m150907_161739_finance_add_column_name_to_purse extends Migration
{
    public function up()
    {
        $this->addColumn('{{%finance_purses}}', 'name', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%finance_purses}}', 'name');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}

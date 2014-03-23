<?php

use yii\db\Schema;

class m140105_125949_finance_init extends \yii\db\Migration
{
	public function up()
	{
		$this->createTable('{{%finance_purses}}',[
			'id' => 'pk',
			'owner_type' => 'string',
			'owner_id' => 'integer',
			'title' => 'string(128)',
			'balance' => 'bigint DEFAULT 0',
			'created_at' => 'datetime',
			'updated_at' => 'datetime',
		], 'ENGINE=InnoDB CHARSET=utf8');

		$this->createIndex('finance_purses__owner','{{%finance_purses}}','owner_type, owner_id');

		$this->createTable('{{%finance_transactions}}',[
			'id' => 'pk',
			'purse_id' => 'integer',
			'type' => 'enum("in","out")',
			'balance_before' => 'bigint',
			'amount' => 'bigint',
			'balance_after' => 'bigint',
			'partner_type' => 'string',
			'partner_id' => 'integer',
			'title' => 'string(128)',
			'comment' => 'string',
			'created_at' => 'datetime',
		], 'ENGINE=InnoDB CHARSET=utf8');

		$this->createIndex('finance_transactions__partner','{{%finance_transactions}}','partner_type, partner_id');

		$this->createIndex('finance_transactions__purse_id','{{%finance_transactions}}','purse_id');
		$this->addForeignKey('finance_transactions__purse_id','{{%finance_transactions}}','purse_id',
			'{{%finance_purses}}','id','CASCADE','CASCADE'
		);

	}

	public function down()
	{
		$this->dropTable('{{%finance_transactions}}');
		$this->dropTable('{{%finance_purses}}');
	}
}

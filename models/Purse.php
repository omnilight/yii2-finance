<?php

namespace yz\finance\models;

use yii\base\InvalidCallException;
use yz\db\ActiveRecord;


/**
 * Class Purse
 * @package \ms\finance\models
 */
class Purse extends ActiveRecord implements TransactionPartnerInterface
{
	/**
	 * Returns partner for some transaction by partner's id
	 * @param int $id
	 * @return $this
	 */
	public static function findById($id)
	{
		return static::find($id);
	}

	/**
	 * @param Transaction $transaction
	 * @throws \yii\base\InvalidCallException
	 * @return $this
	 */
	public function ($transaction)
	{
		if ($this->isNewRecord)
			throw new InvalidCallException('Purse must be saved before adding transactions');

		if ($transaction->isNewRecord == false)
			throw new InvalidCallException('Passed transaction is not new');

		$newBalance = $this->balance + ($transaction->type == 'in'?1:-1) * $transaction->amount;

		if ($newBalance < 0)
			throw new

		$transaction->purse_id = $this->id;

		if ($transaction->save()) {
			$this->balance +=
		}

		return $this;
	}
}
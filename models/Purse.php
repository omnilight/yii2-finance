<?php

namespace yz\finance\models;

use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "yz_finance_purses".
 *
 * @property integer $id
 * @property string $owner_type
 * @property integer $owner_id
 * @property string $title
 * @property integer $balance
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Transaction[] $purseTransactions
 */
class Purse extends \yz\db\ActiveRecord implements ModelInfoInterface, TransactionPartnerInterface
{
	const EVENT_BEFORE_BALANCE_CHANGE = 'beforeBalanceChange';
	const EVENT_AFTER_BALANCE_CHANGE = 'afterBalanceChange';

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%finance_purses}}';
	}

	/**
	 * Returns model title, ex.: 'Person', 'Book'
	 * @return string
	 */
	public static function modelTitle()
	{
		return \Yii::t('yz/finance', 'Purse');
	}

	/**
	 * Returns plural form of the model title, ex.: 'Persons', 'Books'
	 * @return string
	 */
	public static function modelTitlePlural()
	{
		return \Yii::t('yz/finance', 'Purses');
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['owner_id'], 'integer'],
			[['balance'], 'number'],
			[['created_at', 'updated_at'], 'safe'],
			[['owner_type'], 'string', 'max' => 255],
			[['title'], 'string', 'max' => 128]
		];
	}

	public function scenarios()
	{
		return array_merge(parent::scenarios(), [

		]);
	}


	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => \Yii::t('yz/finance', 'ID'),
			'owner_type' => \Yii::t('yz/finance', 'Owner Type'),
			'owner_id' => \Yii::t('yz/finance', 'Owner ID'),
			'title' => \Yii::t('yz/finance', 'Title'),
			'balance' => \Yii::t('yz/finance', 'Balance'),
			'created_at' => \Yii::t('yz/finance', 'Created On'),
			'updated_at' => \Yii::t('yz/finance', 'Updated On'),
			'purseTransactions' => \Yii::t('yz/finance', 'Transactions'),
		];
	}

	public function behaviors()
	{
		return [
			'timestamp' => [
				'class' => TimestampBehavior::className(),
				'value' => new Expression('NOW()'),
			]
		];
	}


	/**
	 * @return \yii\db\ActiveQueryInterface
	 */
	public function getPurseTransactions()
	{
		return $this->hasMany(Transaction::className(), ['purse_id' => 'id']);
	}

	/**
	 * Returns partner for some transaction by partner's id
	 * @param int $id
	 * @return $this
	 */
	public static function findById($id)
	{
		return self::find($id);
	}

	public function beforeSave($insert)
	{
		if ($this->balance != $this->getOldAttribute('balance')) {
			$this->trigger(self::EVENT_BEFORE_BALANCE_CHANGE);
		}

		return parent::beforeSave($insert);
	}


	public function afterSave($insert)
	{
		parent::afterSave($insert);

		if ($this->balance != $this->getOldAttribute('balance')) {
			$this->trigger(self::EVENT_AFTER_BALANCE_CHANGE);
		}
	}


	/**
	 * Adds transaction to the purse. Note: purse balance is changed via transaction save() method.
	 * @param Transaction $transaction
	 * @param bool $save Whether to save transaction
	 * @throws \yii\base\Exception
	 * @return $this
	 */
	public function addTransaction($transaction, $save = true)
	{
		if ($this->isNewRecord)
			throw new Exception('Purse must be saved before it can have transactions');

		if ($transaction->isNewRecord == false)
			throw new Exception('Passed transaction is not new and can not be added to the purse');

		$transaction->purse_id = $this->id;

		if ($save) {
			return $transaction->save();
		}

		return true;
	}

	/**
	 * Returns title for partner
	 * @return string
	 */
	public function getTitleForTransaction()
	{
		return $this->title;
	}

	/**
	 * Returns type of the partner
	 * @return string
	 */
	public function getTypeForTransaction()
	{
		return \Yii::t('yz/finance', 'Purse');
	}
}

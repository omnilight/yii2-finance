<?php

namespace yz\finance\models;

use yii\base\ModelEvent;
use yii\behaviors\AutoTimestamp;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yz\admin\models\AdminableInterface;

/**
 * This is the model class for table "yz_finance_transactions".
 *
 * @property integer $id
 * @property integer $purse_id
 * @property string $type
 * @property string $balance_before
 * @property integer $amount
 * @property integer $balance_after
 * @property integer $partner_type
 * @property integer $partner_id
 * @property string $title
 * @property string $comment
 * @property string $created_on
 *
 * @property Purse $purse
 */
class Transaction extends \yz\db\ActiveRecord implements AdminableInterface
{
	const INCOMING = 'in';
	const OUTBOUND = 'out';

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%finance_transactions}}';
	}

	/**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return \Yii::t('yz/finance', 'Transaction');
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return \Yii::t('yz/finance', 'Transactions');
    }

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['purse_id','type','amount','partner_type'], 'required'],
			[['purse_id', 'partner_id'], 'integer'],
			[['type'], 'string'],
			[['amount', 'balance_before', 'balance_after'], 'integer'],
			[['created_on'], 'safe'],
			[['partner_type', 'comment'], 'string', 'max' => 255],
			[['title'], 'string', 'max' => 128]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => \Yii::t('yz/finance','ID'),
			'purse_id' => \Yii::t('yz/finance','Purse ID'),
			'type' => \Yii::t('yz/finance','Type'),
			'amount' => \Yii::t('yz/finance','Amount'),
			'partner_type' => \Yii::t('yz/finance','Partner Type'),
			'partner_id' => \Yii::t('yz/finance','Partner ID'),
			'title' => \Yii::t('yz/finance','Title'),
			'comment' => \Yii::t('yz/finance','Comment'),
			'created_on' => \Yii::t('yz/finance','Created On'),
			'purse' => \Yii::t('yz/finance','Purse'),
		];
	}

	public function behaviors()
	{
		return [
			'timestamp' => [
				'class' => AutoTimestamp::className(),
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => 'created_on',
				],
				'timestamp' => new Expression('NOW()'),
			]
		];
	}

	/**
	 * @return \yii\db\ActiveRelation
	 */
	public function getPurse()
	{
		return $this->hasOne(Purse::className(), ['id' => 'purse_id']);
	}

	public function afterValidate()
	{
		parent::afterValidate();

		if ($this->isNewRecord)
			$this->validatePurse();
	}


	public function beforeSave($insert)
	{
		if ($insert) {
			$this->balance_before = $this->purse->balance;
			$this->balance_after = $this->getPurseNewBalance();
		}

		return parent::beforeSave($insert);
	}

	public function afterSave($insert)
	{
		parent::afterSave($insert);

		if ($insert) {
			$this->purse->balance = $this->getPurseNewBalance();
			$this->purse->update();
		}
	}


	protected function validatePurse()
	{
		if ($this->type == self::OUTBOUND) {
			$newBalance = $this->getPurseNewBalance();
			if ($newBalance < 0)
				$this->addError('amount', \Yii::t('yz/finance','Balance of the purse is not enough'));
		}
	}

	protected function getPurseNewBalance()
	{
		return $this->purse->balance + (($this->type == self::INCOMING)?1:-1) * $this->amount;
	}
}

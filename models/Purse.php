<?php

namespace omnilight\finance\models;

use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;
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
 * @property PurseOwnerInterface $owner
 */
class Purse extends \yz\db\ActiveRecord implements ModelInfoInterface, TransactionPartnerInterface, PurseInterface
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
        return \Yii::t('omnilight/finance', 'Purse');
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return \Yii::t('omnilight/finance', 'Purses');
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

    /**
     * @param string $ownerType
     * @param int $ownerId
     * @param string $title
     * @return Purse
     */
    public static function create($ownerType, $ownerId, $title)
    {
        $purse = new self;
        $purse->title = $title;
        $purse->owner_type = $ownerType;
        $purse->owner_id = $ownerId;
        $purse->balance = 0;
        $purse->save();
        return $purse;
    }

    public static function remove($ownerType, $ownerId)
    {
        self::deleteAll(['owner_type' => $ownerType, 'owner_id' => $ownerId]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_id'], 'integer'],
            [['balance'], 'number'],
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
            'id' => \Yii::t('omnilight/finance', 'ID'),
            'owner_type' => \Yii::t('omnilight/finance', 'Owner Type'),
            'owner_id' => \Yii::t('omnilight/finance', 'Owner ID'),
            'title' => \Yii::t('omnilight/finance', 'Title'),
            'balance' => \Yii::t('omnilight/finance', 'Balance'),
            'created_at' => \Yii::t('omnilight/finance', 'Created On'),
            'updated_at' => \Yii::t('omnilight/finance', 'Updated On'),
            'purseTransactions' => \Yii::t('omnilight/finance', 'Transactions'),
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

    public function beforeSave($insert)
    {
        if ($this->balance != $this->getOldAttribute('balance')) {
            $this->trigger(self::EVENT_BEFORE_BALANCE_CHANGE);
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

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
        return \Yii::t('omnilight/finance', 'Purse');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        /** @var ActiveRecordInterface $ownerClass */
        $ownerClass = $this->owner_type;
        return $this->hasOne($ownerClass, [$ownerClass::primaryKey()[0] => 'owner_id']);
    }

    /**
     * @return int
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Returns id of the partner. Could NULL if partner does not support id
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }
}

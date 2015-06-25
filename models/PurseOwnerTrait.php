<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 08.04.14
 * Time: 14:17
 */

namespace omnilight\finance\models;


use yii\db\ActiveRecord;

/**
 * Class PurseOwnerTrait
 * @property Purse $purse
 * @package yz\finance\models
 */
trait PurseOwnerTrait
{
    protected function deletePurse()
    {
        Purse::deleteAll("owner_id = :id and owner_type = :type", [
            ':id' => $this->getPrimaryKey(false),
            ':type' => self::className(),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurse()
    {
        /**
         * @var ActiveRecord $this
         */
        return $this->hasOne(Purse::className(), ['owner_id' => 'id'])
            ->onCondition('owner_type = :type', [':type' => self::className()]);
    }
}
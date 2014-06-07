<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 08.04.14
 * Time: 14:17
 */

namespace yz\finance\models;


use yii\db\ActiveRecord;

/**
 * Class PurseOwnerTrait
 * @property Purse $purse
 * @method string className()
 * @method array|string getPrimaryKey(bool $asArray)
 * @package yz\finance\models
 */
trait PurseOwnerTrait
{
    protected function createPurse($title)
    {
        /**
         * @var ActiveRecord $this
         */
        $purse = new Purse();
        $purse->title = $title;
        $purse->owner_type = self::className();
        $purse->owner_id = $this->getPrimaryKey(false);

        $purse->save();
    }

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
            ->where('owner_type = :type', [':type' => self::className()]);
    }
} 
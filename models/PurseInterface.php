<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 07.06.14
 * Time: 11:20
 */

namespace yz\finance\models;

/**
 * Interface PurseInterface
 * @property integer $balance Current balance of the purse
 * @property PurseOwnerInterface $owner Owner of the purse
 * @property Transaction[] $purseTransactions Transactions of the purse
 * @package yz\finance\models
 */
interface PurseInterface
{
    /**
     * @param $transaction
     * @param bool $save Whether to save transaction
     * @return bool
     */
    public function addTransaction($transaction, $save = true);
} 
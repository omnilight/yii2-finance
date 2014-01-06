<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.01.14
 * Time: 17:27
 */

namespace yz\finance\models;

/**
 * Interface TransactionPartnerInterface
 * @package ms\finance\models
 */
interface TransactionPartnerInterface
{
	/**
	 * Returns partner for some transaction by partner's id
	 * @param int $id
	 * @return $this
	 */
	public static function findById($id);
} 
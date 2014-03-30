<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.01.14
 * Time: 17:25
 */

namespace yz\finance\models;

/**
 * Interface PurseOwnerInterface
 * @property Purse $purse Purse of the owner
 * @package ms\finance\models
 */
interface PurseOwnerInterface
{
	/**
	 * Returns purse's owner by owner's id
	 * @param int $id
	 * @return $this
	 */
	public static function findById($id);
} 
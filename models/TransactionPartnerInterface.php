<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.01.14
 * Time: 17:27
 */

namespace omnilight\finance\models;

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

    /**
     * Returns id of the partner. Could NULL if partner does not support id
     * @return int|string
     */
    public function getId();

	/**
	 * Returns title for partner
	 * @return string
	 */
	public function getTitleForTransaction();

	/**
	 * Returns type of the partner
	 * @return string
	 */
	public function getTypeForTransaction();
} 
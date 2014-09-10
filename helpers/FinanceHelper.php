<?php

namespace omnilight\finance\helpers;


/**
 * Class FinanceHelper
 * @package \yz\finance\helpers
 */
class FinanceHelper
{
    /**
     * @param string $amount
     * @param int $divisor
     * @return float
     */
    public static function fromPence($amount, $divisor = 100)
    {
        return $amount / $divisor;
    }

    /**
     * @param float $amount
     * @param int $divisor
     * @return string
     */
    public static function toPence($amount, $divisor = 100)
    {
        return (int)($amount * $divisor);
    }
} 
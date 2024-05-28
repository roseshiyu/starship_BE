<?php

namespace App\Traits;

trait GeneralTrait
{
    protected function setDecimal($amount, $decimal)
    {
        $floor = pow(10, $decimal); // floor for extra decimal

        $amount = number_format((ceil(strval($amount * $floor)) / $floor), $decimal, '.', '');

        return $amount;
    }
}

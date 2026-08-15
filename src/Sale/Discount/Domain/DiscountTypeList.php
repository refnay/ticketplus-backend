<?php

namespace App\Sale\Discount\Domain;

enum DiscountTypeList: int
{
    case PERCENTAGE = 0;
    case AMOUNT = 1;
}

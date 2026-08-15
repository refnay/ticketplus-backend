<?php

namespace App\Sale\Order\Domain;

enum OrderPaymentMethodList: int
{
    case UNDEFINED = -1;
    case CREDIT_CARD = 0;
    case DEBIT_CARD = 1;
    case YAPE = 2;
    case PLIN = 3;
    case TRANSFER = 4;
    case CASH = 5;
}

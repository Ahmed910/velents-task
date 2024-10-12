<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum OrderStatus: int
{
   use EnumTrait;

   case PENDING = 0;
   case PAID = 1;
   case CANCELED = 2;
}
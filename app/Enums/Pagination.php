<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum Pagination: int
{
   use EnumTrait;

   case PAGINATION_COUNT = 10;
}
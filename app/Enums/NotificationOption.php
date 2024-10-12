<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum NotificationOption: string
{
   use EnumTrait;

   case EML = 'EML';
   case SMS = 'SMS';
   case LNK = 'LNK';
   case ALL = 'ALL';
}
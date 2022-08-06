<?php

namespace App\Enums;

enum AccountType: string
{
    case SavingsAccount = 'savings account';

    case CurrentAccount = 'current account';
}

<?php

namespace App\Enums;

enum UserTypes: string
{
    case ADMIN = 'admin';
    case NORMAL_USER = 'normal_user';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Admin',
            default => 'Normal User',
        };
    }
}

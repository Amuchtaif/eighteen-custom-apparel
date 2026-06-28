<?php

namespace App\Services;

/**
 * Helper class to get administrative user ID for seeding/automated processes
 */
class UserSeederHelper
{
    public static function getFirstAdminId(): int
    {
        return \App\Models\User::first()?->id ?? 1;
    }
}

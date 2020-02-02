<?php

use Silentz\Charge\Models\User;

if (!function_exists('current_user')) {
    function current_user(): ?User
    {
        return auth()->user();
    }
}
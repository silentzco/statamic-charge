<?php

use Illuminate\Support\Facades\Auth;

/**
 * @return \Silentz\Charge\Chargeable
 */
function current_user()
{
    return Auth::user();
}

<?php

namespace Silentz\Charge\Models;

use Laravel\Cashier\Billable;
use Illuminate\Foundation\Auth\User as Model;

class User extends Model
{
    use Billable;
}

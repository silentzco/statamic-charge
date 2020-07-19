<?php

namespace Silentz\Charge\Models;

use Illuminate\Foundation\Auth\User as Model;
use Silentz\Charge\Traits\Charge;

class User extends Model
{
    use Charge;
}

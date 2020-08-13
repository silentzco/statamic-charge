<?php

namespace Silentz\Charge\Models;

use Illuminate\Foundation\Auth\User as Model;
use Silentz\Charge\Chargeable;

class User extends Model
{
    use Chargeable;
}

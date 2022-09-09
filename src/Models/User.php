<?php

namespace Silentz\Charge\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Model;
use Silentz\Charge\Chargeable;
use Silentz\Charge\Database\Factories\UserFactory;

class User extends Model
{
    use Chargeable;
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }
}

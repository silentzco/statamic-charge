<?php

namespace Silentz\Charge\Fieldtypes;

use Statamic\Facades\Role;
use Statamic\Fieldtypes\Relationship;

class Roles extends Relationship
{
    protected $canCreate = false;
    protected $canSearch = false;

    public function getIndexItems($request)
    {
        return Role::all()->map(function ($role) {
            return [
                'id' => $role->handle(),
                'title' => $role->title(),
            ];
        })->values();
    }

    protected function toItemArray($id)
    {
        if (! $role = Role::find($id)) {
            return;
        }

        return [
            'id' => $role->handle(),
            'title' => $role->title(),
        ];
    }
}

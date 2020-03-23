<?php

namespace Silentz\Charge\Http\Controllers\Web;

use Illuminate\Http\Request;

class OneTimeController
{
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'currency' => 'required',
            'source' => 'required',
        ]);

        return 'ok';
    }
}

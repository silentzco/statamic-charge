<?php

namespace Silentz\Charge\Fieldtypes;

use Statamic\Fieldtypes\Relationship;
use Statamic\Support\Arr;
use Stripe\Plan;
use Stripe\Stripe;

class Plans extends Relationship
{
    protected $canCreate = false;
    protected $canSearch = false;

    public function __construct()
    {
        Stripe::setApiKey(config('cashier.secret'));
    }

    public function getIndexItems($request)
    {
        return collect(Arr::get(Plan::all(), 'data', []))->map(function ($plan, $ignored) {
            return [
                'id' => $plan->id,
                'title' => $plan->nickname,
            ];
        })->all();
    }

    protected function toItemArray($id)
    {
        if (! $id) {
            return [];
        }
        $plan = Plan::retrieve($id)->toArray();
        $plan['title'] = $plan['nickname'];

        return $plan;
    }
}

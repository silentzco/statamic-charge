<?php

namespace Silentz\Charge\Fieldtypes;

use Illuminate\Support\Facades\Cache;
use Statamic\Fieldtypes\Relationship;
use Stripe\Price as StripePrice;
use Stripe\StripeClient;

class Price extends Relationship
{
    protected static $handle = 'stripe_price';

    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('cashier.secret'));
    }

    public function getIndexItems($request)
    {
        $options = [
            'active' => true,
            'expand'=> [
                'data.product',
            ],
            'limit' => 100,
        ];

        return collect($this->prices($options))
            ->map(fn (StripePrice $price) => ['id' => $price->id, 'title' => $price->product->name.' - $'.$price->unit_amount / 100])
            ->sortBy('title');
    }

    protected function toItemArray($id)
    {
        if (! $id) {
            return [];
        }

        $price = $this->price($id);

        return [
            'id' => $price->id,
            'title' => $price->product->name.' - $'.$price->unit_amount / 100,
        ];
    }

    private function price(string $id)
    {
        $options = [
            'expand' => ['product'],
        ];

        return Cache::rememberForever(
            'stripe-price-'.$id,
            fn () => $this->stripe->prices->retrieve($id, $options)
        );
    }

    private function prices(array $options)
    {
        return $this->stripe->prices->all($options)->autoPagingIterator();
    }
}

<?php

namespace Silentz\Charge\Http\Controllers\Cp;

use Illuminate\Http\Request;
use Silentz\Charge\Configurator\Configurator;
use Statamic\Facades\Blueprint as BlueprintAPI;
use Statamic\Facades\File;
use Statamic\Facades\YAML;
use Statamic\Fields\Blueprint;
use Statamic\Http\Controllers\CP\CpController;
use Statamic\Support\Arr;
use Stripe\Stripe;

class SettingsController extends CpController
{
    private Configurator $configurator;

    public function __construct()
    {
        Stripe::setApiKey(config('cashier.secret'));
        $this->configurator = Configurator::file('charge.php');
    }

    public function edit()
    {
        $blueprint = $this->blueprint();

        // $values = $this->mapToBlueprint(config('charge'));
        $values = config('charge');

        $fields = $blueprint->fields()->addValues($values)->preProcess();

        return view('charge::cp.settings', [
            'blueprint' => $blueprint->toPublishArray(),
            'values'    => $fields->values(),
            'meta'      => $fields->meta(),
        ]);
    }

    public function update(Request $request)
    {
        $blueprint = $this->blueprint();

        $fields = $blueprint->fields()->addValues($request->all());

        $fields->validate();

        // $this->configurator->setAll($this->mapToConfig($this->stripSections($fields->process()->values())));
        $this->configurator->setAll($this->stripSections($fields->process()->values()));

        return back();
    }

    private function blueprint(): Blueprint
    {
        $blueprintPath = __DIR__.'/../../../../resources/blueprints/settings.yaml';

        return BlueprintAPI::make('charge-settings')
            ->setContents(YAML::parse(File::get($blueprintPath)));
    }

    private function mapToBlueprint($config)
    {
        return array_merge(
            Arr::get($config, 'subscription', []),
            Arr::get($config, 'email', [])
        );
    }

    private function mapToConfig($blueprint)
    {
        $config = [];

        Arr::set($config, 'email', Arr::except($blueprint, 'roles_and_plans'));
        Arr::set($config, 'subscription.roles_and_plans', Arr::get($blueprint, 'roles_and_plans'));

        return $config;
    }

    private function stripSections($data)
    {
        $currentSections = [
            'subscription',
            'email',
            'one_time_emails',
            'subscription_emails',
            'customer_emails',
        ];

        return Arr::except($data, $currentSections);
    }
}

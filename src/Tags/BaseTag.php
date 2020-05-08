<?php

namespace Silentz\Charge\Tags;

use Illuminate\Support\Facades\Crypt;
use Statamic\Tags\Concerns\RendersForms;
use Statamic\Tags\Tags;

abstract class BaseTag extends Tags
{
    use RendersForms;

    private static $knownParams = ['redirect', 'error_redirect', 'action_needed_redirect', 'name'];

    protected function createForm(string $action, array $data = [], string $method = 'POST'): string
    {
        $html = $this->formOpen($action, $method, static::$knownParams);

        $html .= $this->hideParams();

        $html .= $this->parse($this->sessionData($data));

        $html .= $this->formClose();

        return $html;
    }

    protected function sessionData($data = [])
    {
        if ($errors = $this->errors()) {
            $data['errors'] = $errors;
        }

        if ($this->requiresAction()) {
            $data['requires_action'] = true;
            $data['action'] = session('action');
            $data['payment_method'] = session('payment_method');
        }

        if ($success = $this->success()) {
            $data['success'] = $success;
            $data['subscription'] = session('subscription');
        }

        return $data;
    }

    private function hideParams(): string
    {
        return '<input type="hidden" name="_params" value="'.Crypt::encrypt($this->params()).'" />';
    }

    private function params(): array
    {
        return collect(static::$knownParams)->map(function ($param, $ignore) {
            if ($redirect = $this->get($param)) {
                return $params[$param] = $redirect;
            }
        })->filter()
        ->values()
        ->all();
    }

    /**
     * Maps to {{ charge:success }}.
     *
     **/
    public function success(): bool
    {
        return session()->has('charge.success');
    }

    public function requiresAction(): bool
    {
        return session()->has('charge.requires_action');
    }

    /**
     * Maps to {{ charge:details }}.
     *
     **/
    public function details(): ?array
    {
        return $this->success() ? session('charge.details') : [];
    }

    public function paymentIntent(): ?array
    {
        return session('charge.payment_intent');
    }

    /**
     * @return bool|string
     */
    public function errors()
    {
        if (! $this->hasErrors()) {
            return false;
        }

        $errors = [];

        foreach (session('errors')->getBag('charge')->all() as $error) {
            $errors[]['value'] = $error;
        }

        return ($this->content === '')    // If this is a single tag...
            ? ! empty($errors)             // just output a boolean.
            : $errors;  // Otherwise, parse the content loop.
    }

    /**
     * Does this form have errors?
     */
    private function hasErrors(): bool
    {
        return (session()->has('errors'))
            ? session('errors')->hasBag('charge')
            : false;
    }

    /**
     * Get the errorBag from session.
     *
     * @return object
     */
    private function getErrorBag()
    {
        if ($this->hasErrors()) {
            return session('errors')->getBag('charge');
        }
    }
}

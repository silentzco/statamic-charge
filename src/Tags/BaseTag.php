<?php

namespace Silentz\Charge\Tags;

use Statamic\Tags\Tags;
use Illuminate\Support\Facades\Crypt;

abstract class BaseTag extends Tags
{
    protected function createForm(string $action, array $data = [], string $method = 'POST'): string
    {
        $html = $this->formOpen($action, $method);

        if ($this->success()) {
            $data['success'] = true;
            $data['details'] = $this->flash->get('details');
        }

        if ($this->requiresAction()) {
            $data['requires_action'] = true;
            $data['client_secret'] = $this->flash->get('client_secret');
        }

        if ($this->hasErrors()) {
            $data['errors'] = $this->getErrorBag()->all();
        }

        if ($redirect = $this->get('redirect')) {
            $html .= '<input type="hidden" name="redirect" value="' . $redirect . '" />';
        }

        $params = [];
        if ($redirect = $this->get('redirect')) {
            $params['redirect'] = $redirect;
        }

        if ($error_redirect = $this->get('error_redirect')) {
            $params['error_redirect'] = $error_redirect;
        }

        if ($action_needed_redirect = $this->get('action_needed_redirect')) {
            $params['action_needed_redirect'] = $action_needed_redirect;
        }

        $html .= '<input type="hidden" name="_params" value="' . Crypt::encrypt($params) . '" />';

        return $html . $this->parse($data) . '</form>';
    }

    /**
     * Maps to {{ charge:success }}
     *
     **/
    public function success(): bool
    {
        return session()->has('success');
    }

    public function requiresAction(): bool
    {
        return session()->has('requires_action');
    }

    /**
     * Maps to {{ charge:details }}
     *
     **/
    public function details(): ?array
    {
        return $this->success() ? session('details') : [];
    }

    /**
     * @return bool|string
     */
    public function errors()
    {
        if (!$this->hasErrors()) {
            return false;
        }

        $errors = [];

        foreach (session('errors')->getBag('charge')->all() as $error) {
            $errors[]['value'] = $error;
        }

        return ($this->content === '')    // If this is a single tag...
            ? !empty($errors)             // just output a boolean.
            : $this->parseLoop($errors);  // Otherwise, parse the content loop.
    }

    /**
     * Does this form have errors?
     *
     */
    private function hasErrors(): bool
    {
        return (session()->has('errors'))
            ? session('errors')->hasBag('charge')
            : false;
    }

    /**
     * Get the errorBag from session
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

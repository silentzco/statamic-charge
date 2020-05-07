<?php

namespace Silentz\Charge\Tags;

use Illuminate\Support\Facades\Crypt;
use Statamic\Tags\Concerns\RendersForms;
use Statamic\Tags\Tags;

abstract class BaseTag extends Tags
{
    use RendersForms;

    protected function createForm(string $action, array $data = [], string $method = 'POST'): string
    {
        $data = $this->setSessionData($data);

        $knownParams = ['redirect', 'error_redirect', 'action_needed_redirect', 'name'];

        $html = $this->formOpen($action, $method, $knownParams);

        $params = [];
        if ($redirect = $this->get('redirect')) {
            $params['redirect'] = $redirect;
        }

        if ($error_redirect = $this->get('error_redirect')) {
            $params['error_redirect'] = $error_redirect;
        }

        $html .= '<input type="hidden" name="_params" value="'.Crypt::encrypt($params).'" />';

        $html .= $this->parse($data);

        $html .= $this->formClose();

        return $html;
    }

    protected function setSessionData($data)
    {
        if ($errors = $this->errors()) {
            $data['errors'] = $errors;
        }

        if ($this->requiresAction()) {
            $data['requires_action'] = true;
            $data['client_secret'] = $this->flash->get('client_secret');
        }

        if ($success = $this->success()) {
            $data['success'] = $success;
            $data['details'] = $this->flash->get('details');
        }

        return $data;
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

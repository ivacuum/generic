<?php

namespace Ivacuum\Generic\Http;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

abstract class FormRequest extends BaseFormRequest
{
    public function validationData(): array
    {
        if (!method_exists($this, 'sanitize')) {
            return parent::validationData();
        }

        $data = $this->container->call([$this, 'sanitize'], ['data' => parent::validationData()]);

        $this->replace($data);

        return $data;
    }
}

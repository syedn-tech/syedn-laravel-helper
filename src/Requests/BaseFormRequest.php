<?php

namespace Syedn\Helper\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseFormRequest extends FormRequest
{
    public function getValidator()
    {
        return parent::getValidatorInstance();
    }

    protected function generateConvertedParamsArray($params, $covertToInt = true): array
    {
        $data = [];

        foreach ($params as $param) {
            if (isset($this->{$param})) {
                $data[$param] = $this->changeParamStringToArray($this->{$param}, $covertToInt);
            }
        }

        return $data;
    }

    protected function changeArrayValueToInteger(array $values): array
    {
        return array_map('intval', $values);
    }

    protected function changeParamStringToArray(string $data, $covertToInt = true)
    {
        if ($covertToInt) {
            return $this->changeArrayValueToInteger(explode(',', $data));
        }

        return explode(',', $data);
    }
}

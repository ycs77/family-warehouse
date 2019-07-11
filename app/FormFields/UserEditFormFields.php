<?php

namespace App\FormFields;

use Ycs77\LaravelFormFieldType\FormFields;

class UserEditFormFields extends FormFields
{
    /**
     * Return form fields array.
     *
     * @param  boolean  $isCantDeprivation
     * @return array
     */
    public function fields(bool $isCantDeprivation = false)
    {
        $formFields = app(UserFormFields::class)->fields();

        unset($formFields['password']);
        unset($formFields['password_confirmation']);

        if ($isCantDeprivation) {
            unset($formFields['role']);
        }

        return $formFields;
    }
}

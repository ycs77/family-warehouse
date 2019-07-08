<?php

namespace App\FormFields;

use Ycs77\LaravelFormFieldType\FormFields;

class UserEditFormFields extends FormFields
{
    /**
     * Return form fields array.
     *
     * @return array
     */
    public function fields()
    {
        $formFields = app(UserFormFields::class)->fields();
        unset($formFields['password']);
        unset($formFields['password_confirmation']);
        return $formFields;
    }
}

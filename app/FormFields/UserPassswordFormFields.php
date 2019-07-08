<?php

namespace App\FormFields;

use Ycs77\LaravelFormFieldType\FormFields;

class UserPassswordFormFields extends FormFields
{
    /**
     * Return form fields array.
     *
     * @return array
     */
    public function fields()
    {
        return [
            'password' => [
                'type' => 'password',
                'rules' => 'required|min:8|confirmed',
            ],
            'password_confirmation' => [
                'type' => 'password',
            ],
            'submit',
        ];
    }
}

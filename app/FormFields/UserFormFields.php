<?php

namespace App\FormFields;

use Ycs77\LaravelFormFieldType\FormFields;

class UserFormFields extends FormFields
{
    /**
     * Return form fields array.
     *
     * @return array
     */
    public function fields()
    {
        return [
            'name' => [
                'rules' => 'required|max:50',
            ],
            'username' => [
                'type' => 'text',
                'rules' => 'required|max:50',
            ],
            'password' => [
                'type' => 'password',
                'rules' => 'required|min:8|confirmed',
            ],
            'password_confirmation' => [
                'type' => 'password',
            ],
            'role' => [
                'type' => 'checkable_group',
                'choices' => [
                    'user',
                    'child',
                    'admin',
                ],
                'is_checkbox' => false,
                'back_rules' => 'required',
            ],
            'submit',
        ];
    }
}

<?php

namespace App\FormFields;

use Ycs77\LaravelFormFieldType\FormFields;

class CategoryFormFields extends FormFields
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
                'type' => 'text',
                'rules' => 'required|max:50',
            ],
            'description' => [
                'type' => 'textarea',
                'rules' => 'max:200',
                'attr' => [
                    'rows' => 2,
                ],
            ],
            'submit',
        ];
    }
}

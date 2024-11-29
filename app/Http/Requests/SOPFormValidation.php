<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class SOPFormValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Set to true if you want to allow this request
        return true;
    }

    public function rules()
    {
        $rules = [
            'proposed_guarantor_details_title' => 'required|alpha',
            'proposed_guarantor_details_first_name' => 'required|alpha',
            'proposed_guarantor_details_middle_name' => 'nullable|alpha',
            'proposed_guarantor_details_surname' => 'required|alpha',
            'proposed_guarantor_details_email' => 'nullable|email',
            'proposed_guarantor_details_tel_home' => 'nullable|numeric',
            'proposed_guarantor_details_tel_business' => 'nullable|numeric',
            'proposed_guarantor_details_mobile' => 'nullable|numeric',
        ];

        $fieldMappings = [
            'has_properties' => [
                'key' => 'otherAssets.*.property_address_and_assets',
                'rule' => 'required',
            ],
            'has_contingentLib' => [
                'key' => 'contingentLib.*.creditor',
                'rule' => 'required',
            ],
            'has_householdIncome' => [
                'key' => 'householdIncome.*.type_and_source',
                'rule' => 'required',
            ],
            'has_unlistedShares' => [
                'key' => 'unlistedShares.*.company_name',
                'rule' => 'required',
            ],
        ];

        foreach ($fieldMappings as $inputKey => $mapping) {
            $inputValue = $this->input($inputKey);
            if ($inputValue === 'y') {
                $rules[$mapping['key']] = $mapping['rule'];
            }
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'proposed_guarantor_details_title' => 'Title',
            'proposed_guarantor_details_first_name' => 'First Name',
            'proposed_guarantor_details_middle_name' => 'Middle Name',
            'proposed_guarantor_details_surname' => 'Last Name',
            'proposed_guarantor_details_email' => 'Email',
            'proposed_guarantor_details_tel_home' => 'Tel Home',
            'proposed_guarantor_details_tel_business' => 'Tel Business',
            'proposed_guarantor_details_mobile' => 'Mobile',
        ];
    }

    
}

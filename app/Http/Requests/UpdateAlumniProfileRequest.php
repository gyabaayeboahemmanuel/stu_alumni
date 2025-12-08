<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAlumniProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Authorization handled by policy
    }

    public function rules()
    {
        $alumniId = $this->route('alumni')?->id;

        return [
            'phone' => 'required|string|max:15',
            'current_employer' => 'nullable|string|max:200',
            'job_title' => 'nullable|string|max:200',
            'industry' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'postal_address' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:200',
            'linkedin' => 'nullable|url|max:200',
            'twitter' => 'nullable|url|max:200',
            'facebook' => 'nullable|url|max:200',
            'is_visible_in_directory' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Phone number is required',
            'phone.max' => 'Phone number must not exceed 15 characters',
            'website.url' => 'Please enter a valid website URL',
            'linkedin.url' => 'Please enter a valid LinkedIn URL',
            'twitter.url' => 'Please enter a valid Twitter URL',
            'facebook.url' => 'Please enter a valid Facebook URL',
        ];
    }
}

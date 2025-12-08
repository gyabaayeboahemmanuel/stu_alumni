<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBusinessRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', $this->route('business'));
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:200',
            'description' => 'required|string|max:1000',
            'industry' => 'required|string|max:100',
            'website' => 'nullable|url|max:200',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Business name is required',
            'description.required' => 'Business description is required',
            'industry.required' => 'Please select an industry',
            'website.url' => 'Please enter a valid website URL',
            'email.email' => 'Please enter a valid email address',
            'logo.image' => 'The logo must be an image file',
            'logo.mimes' => 'The logo must be a JPEG, PNG, or JPG file',
            'logo.max' => 'The logo must not exceed 2MB',
        ];
    }
}

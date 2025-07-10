<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    
    protected function prepareForValidation(): void
    {
        if (
            $this->filled(['birth_day', 'birth_month', 'birth_year']) &&
            checkdate((int) $this->birth_month, (int) $this->birth_day, (int) $this->birth_year)
        ) {
            $birthdate = sprintf(
                '%04d-%02d-%02d',
                $this->birth_year,
                $this->birth_month,
                $this->birth_day
            );
    
            $this->merge(['birthdate' => $birthdate]);
        }
    }     

    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'birthdate' => [
                'required',
                'date',
                'before:today',
                'after:1900-01-01',
            ],
            'middlename' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s\-\.]*$/',
            ], 
            'nickname' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9\s\-\.]*$/',
            ],
        ];
    }
}

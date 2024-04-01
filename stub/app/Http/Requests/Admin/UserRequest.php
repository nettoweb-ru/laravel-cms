<?php
namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', Rule::unique(User::class, 'email')->ignore($this->get('id'))],
            'password' => ['nullable', Password::defaults(), 'confirmed', Rule::requiredIf(empty($this->get('id')))],
            'email_verified_at' => ['date:Y-m-d\TH:i:s', 'nullable'],
        ];
    }
}

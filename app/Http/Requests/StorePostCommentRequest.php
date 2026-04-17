<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePostCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:80'],
            'body' => ['required', 'string', 'min:3', 'max:1200'],
            'website' => ['prohibited'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'body.required' => 'Komentar wajib diisi.',
            'body.min' => 'Komentar minimal :min karakter.',
            'body.max' => 'Komentar maksimal :max karakter.',
            'name.max' => 'Nama maksimal :max karakter.',
            'website.prohibited' => 'Komentar tidak dapat diproses.',
        ];
    }
}

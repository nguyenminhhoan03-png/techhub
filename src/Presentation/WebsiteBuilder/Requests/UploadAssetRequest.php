<?php

declare(strict_types=1);

namespace Presentation\WebsiteBuilder\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'max:10240'], // Tối đa 10MB
            'website_id' => ['nullable', 'integer', 'exists:websites,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'File tải lên là bắt buộc.',
            'file.max' => 'Kích thước file không được vượt quá 10MB.',
        ];
    }
}

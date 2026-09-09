<?php

declare(strict_types=1);

namespace Presentation\WebsiteBuilder\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutosavePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content_json' => ['required', 'array'],
            'base_version' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'content_json.required' => 'Dữ liệu trang (JSON AST) là bắt buộc.',
            'base_version.required' => 'Version number hiện tại là bắt buộc để kiểm soát xung đột.',
        ];
    }
}

<?php

declare(strict_types=1);

namespace Presentation\WebsiteBuilder\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateWebsiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'subdomain' => ['nullable', 'string', 'min:3', 'max:63', 'regex:/^[a-z0-9\-]+$/i'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên website là bắt buộc.',
            'subdomain.regex' => 'Subdomain chỉ được chứa chữ cái, số và dấu gạch ngang (-).',
        ];
    }
}

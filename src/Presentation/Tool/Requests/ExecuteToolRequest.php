<?php

declare(strict_types=1);

namespace Presentation\Tool\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExecuteToolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'input' => ['required', 'array'],
        ];
    }
}

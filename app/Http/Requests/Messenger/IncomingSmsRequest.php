<?php

namespace App\Http\Requests\Messenger;

use App\Services\Helpers\PhoneCleanerService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IncomingSmsRequest extends FormRequest
{
    protected PhoneCleanerService $phoneCleanerService;

    public function __construct(PhoneCleanerService $phoneCleanerService)
    {
        parent::__construct();
        $this->phoneCleanerService = $phoneCleanerService;
    }

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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'min:2', 'max:200'],
            'phone' => ['required', 'numeric'],
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge(
            [
                'phone' => $this->input('phone') ? $this->phoneCleanerService->handle($this->input('phone')) : '',
            ]
        );
    }
}

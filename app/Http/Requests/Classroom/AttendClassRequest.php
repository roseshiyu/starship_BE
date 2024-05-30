<?php

namespace App\Http\Requests\Classroom;

use App\Enums\Course\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class AttendClassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->tokenCan('create_package');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'description' => ['required', 'string', 'min:1', 'max:1000'],
            'code' => ['required', 'string', 'min:1', 'max:50', 'unique:courses,code'],
            'weeks' => ['required', 'integer', 'gt:0', 'lt:100'],
            'credits' => ['required', 'integer', 'gt:0', 'lt:100'],
            'status' => ['required', 'integer', new Enum(Status::class)],
        ];
    }
}

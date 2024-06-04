<?php

namespace App\Http\Requests\Course;

use App\Enums\Course\Category;
use App\Enums\Course\Status;
use App\Enums\Course\SubjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateSubjectRequest extends FormRequest
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
            'code' => ['required', 'string', 'min:1', 'max:50', 'unique:subjects,code'],
            'fee' => ['required', 'numeric', 'gt:0', 'lt:999999'],
            'weeks' => ['required', 'integer', 'gt:0'],
            'credits' => ['required', 'integer', 'gt:0'],
            'category_ids' => ['required', 'array'],
            'category_ids.*' => ['required', 'integer', new Enum(Category::class)],
            'subject_ids' => ['required', 'array'],
            'subject_ids.*' => ['exists:subjects,id,status,'.SubjectStatus::active->value.',deleted_at,NULL'],
            'status' => ['required', 'integer', new Enum(Status::class)],
        ];
    }
}

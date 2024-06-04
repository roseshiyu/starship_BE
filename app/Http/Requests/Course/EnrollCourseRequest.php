<?php

namespace App\Http\Requests\Course;

use App\Enums\Course\Status as CourseStatus;
use App\Enums\Student\Status as StudentStatus;
use Illuminate\Foundation\Http\FormRequest;

class EnrollCourseRequest extends FormRequest
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
        if (request()->user()->isAdmin()) {
            return [
                'status' => ['required', 'exists:students,id,status,'.StudentStatus::active->value],
                'course_id' => ['required', 'exists:course,id,status,'.CourseStatus::active->value],
                'entry_requirement_proof' => ['required', 'string', 'min:1', 'max:2048'],
            ];
        } else {
            return [
                'course_id' => ['required', 'exists:course,id,status,'.CourseStatus::active->value],
                'entry_requirement_proof' => ['required', 'string', 'min:1', 'max:2048'],
            ];
        }
    }
}

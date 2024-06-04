<?php

namespace App\Http\Controllers;

use App\Actions\Course\CreateCourse;
use App\Actions\Course\CreateSubject;
use App\Actions\Course\EnrollCourse;
use App\Actions\Course\EnrollSubject;
use App\Actions\Course\GetCourses;
use App\Actions\Course\VerifyEnrollCourse;
use App\Enums\Course\Category;
use App\Http\Requests\Course\CreateCourseRequest;
use App\Http\Requests\Course\CreateSubjectRequest;
use App\Http\Requests\Course\EnrollCourseRequest;
use App\Http\Resources\Course\CourseResource;
use App\Models\Course;
use App\Models\StudentCourseEnrollment;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $params = request()->all();

        return response()->data(CourseResource::collection(GetCourses::run($params)));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreateCourseRequest $request)
    {
        return response()->data(CreateCourse::run($request->validated()));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createSubject(CreateSubjectRequest $request)
    {
        return response()->data(CreateSubject::run($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function enrollSubject(Course $course)
    {
        return response()->data(EnrollSubject::run($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function enrollCourse(EnrollCourseRequest $request)
    {
        $params = $request->validated();
        if (request()->user()->isAdmin()) {
            $params['admin_id'] = request()->user()->id;
        } else {
            $params['admin_id'] = null;
            $params['student_id'] = request()->user()->id;
        }

        return response()->data(EnrollCourse::run($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function verifyEnrollCourse(EnrollCourseRequest $request, StudentCourseEnrollment $id)
    {
        return response()->data(VerifyEnrollCourse::run($request->validated(), $id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function category_index()
    {
        $categories = Category::cases();
        $data = [];
        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->value,
                'name' => $category->name,
            ];
        }

        return response()->data($data);
    }
}

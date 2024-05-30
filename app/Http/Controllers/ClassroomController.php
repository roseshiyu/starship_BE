<?php

namespace App\Http\Controllers;

use App\Actions\Course\CreateCourse;
use App\Actions\Course\GetCourses;
use App\Enums\Course\Status;
use App\Http\Requests\Course\CreateCourseRequest;
use App\Http\Resources\Course\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function attendClass(CreateCourseRequest $request)
    {
        $params = request()->all();

        ClassroomAttendence::create([
            'student_id' => $params['student_id'],
            'class_id' => $params['class_id'],
            'status' => Status::ontime,
        ]);

        return response()->data(CourseResource::collection(GetCourses::run($params)));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createClassQr(CreateCourseRequest $request)
    {
        Classroom::create([

        ]);

        return response()->data(CreateCourse::run($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //
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
}

<?php

namespace App\Http\Controllers;

use App\Actions\Course\CreateCourse;
use App\Actions\Course\GetCourses;
use App\Enums\Course\Category;
use App\Http\Requests\Course\CreateCourseRequest;
use App\Http\Resources\Course\CourseResource;
use App\Models\Course;
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

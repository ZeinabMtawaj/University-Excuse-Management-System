<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Auth\Database\Administrator;


class CourseController extends Controller
{

public function getCoursesForStudent(Request $request)
    {
        $studentId = $request->input('query'); // assuming the input name is 'query'
        $student = Administrator::find($studentId);
        if (!$student) {
            return response()->json([]);
        }
    
        $courses = \App\Models\Course::where('faculty_id', $student->faculty_id)->get(['id', 'name as text']);
        // dd($courses);
        return response()->json($courses);
    }
}
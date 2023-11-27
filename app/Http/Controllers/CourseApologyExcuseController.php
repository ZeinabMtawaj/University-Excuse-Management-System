<?php


namespace App\Http\Controllers;
use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CourseApologyExcuse;
use Illuminate\Support\Facades\Storage;
use OpenAdmin\Admin\Auth\Database\Administrator;


class CourseApologyExcuseController extends Controller
{

    public function get()
    {
        $user = auth()->user(); // Get the authenticated user
        // dd($user->roles);
        if ($user->roles->where('slug', 'student')->isNotEmpty()){
            $courseApologyExcuses = CourseApologyExcuse::with('course')
                ->where('student_id', $user->id) 
                ->get();
        }
        if ($user->roles->where('slug', 'faculty-member')->isNotEmpty()){
            $courseApologyExcuses = CourseApologyExcuse::with('course')
            ->whereHas('course', function ($query) use ($user) {
                // This assumes that the 'courses' table has a 'faculty_id' column
                $query->where('faculty_id', $user->faculty_id);
            })
            ->get();
        }


        $cols = ["Course", "File", "Date", "Status", "Created at"];

        return view('courseApologyExcuses.read', [
            'data' => $courseApologyExcuses,
            'cols' => $cols
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        $courses = Course::with('faculty')
                ->where('faculty_id', $user->faculty_id) 
                ->get();
        return view('courseApologyExcuses.create', [
                'courses' => $courses,
                'data' => 1
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'file' => 'required|file',
            'date' => 'required|date',
        ]);

        $courseApologyExcuse = new CourseApologyExcuse();
        $courseApologyExcuse->student_id = auth()->id();
        $courseApologyExcuse->course_id = $request->course_id;
        $courseApologyExcuse->date = $request->date;

        if ($request->hasFile('file')) {
           
            $originalName = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
    
            // Sanitize the file name
            $safeName = Str::slug($originalName);
    
            // Make the filename unique by appending a number if it already exists
            $counter = 1;
            $filename = $safeName . '.' . $extension;
    
            // Check if file exists and append counter number to filename
            while (Storage::disk('private')->exists('course abologies/' . $filename)) {
                $filename = $safeName . '-' . $counter . '.' . $extension;
                $counter++;
            }
    
            // Store the file using the 'private' disk
            $filePath = $request->file('file')->storeAs('course abologies', $filename, 'private');
    
            // Set the file_path on the model
            $courseApologyExcuse->file_path = $filePath;
    
    
        }
        $courseApologyExcuse->save();

        return redirect()->route('courseApologyExcuses.read')->with('success', 'course apology excuse added successfully.');
    }


    
    public function updateStatus(Request $request){
        
        // Validate the request
        $validatedData = $request->validate([
            'id' => 'required|integer|exists:course_apology_excuses,id',
            'status' => 'required',
        ]);
        

        // Find the AbsenceExcuse by ID
        $absenceExcuse = CourseApologyExcuse::find($validatedData['id']);
        $user = auth()->user(); // Get the authenticated user


        // // Check if the user is authorized to update the status
        if ($user->roles->where('slug', 'faculty-member')->isEmpty()){
            abort(403, 'Unauthorized action.');
        }

        // // Update the status
        $absenceExcuse->status = $validatedData['status'];
        $absenceExcuse->save();

        // Return a JSON response
        return response()->json(['message' => 'Course Apology status updated successfully.']);
    }



}
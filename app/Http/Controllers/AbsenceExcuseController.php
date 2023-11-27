<?php


namespace App\Http\Controllers;
use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AbsenceExcuse;
use Illuminate\Support\Facades\Storage;
use OpenAdmin\Admin\Auth\Database\Administrator;


class AbsenceExcuseController extends Controller
{

    public function get()
    {
        $user = auth()->user(); // Get the authenticated user
        // dd($user->roles);
        if ($user->roles->where('slug', 'student')->isNotEmpty()){
            $absenceExcuses = AbsenceExcuse::with('course')
                ->where('student_id', $user->id) 
                ->get();
        }
        if ($user->roles->where('slug', 'faculty-member')->isNotEmpty()){
            $absenceExcuses = AbsenceExcuse::with('course','student')
            ->whereHas('course', function ($query) use ($user) {
                // This assumes that the 'courses' table has a 'faculty_id' column
                $query->where('faculty_id', $user->faculty_id);
            })
            ->get();
        }

        $cols = ["Course", "File", "Date", "Status", "Created at"];

        return view('absenceExcuses.read', [
            'data' => $absenceExcuses,
            'cols' => $cols
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        $courses = Course::with('faculty')
                ->where('faculty_id', $user->faculty_id) 
                ->get();
        return view('absenceExcuses.create', [
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

        $absenceExcuse = new AbsenceExcuse();
        $absenceExcuse->student_id = auth()->id();
        $absenceExcuse->course_id = $request->course_id;
        $absenceExcuse->date = $request->date;

        if ($request->hasFile('file')) {
           
            $originalName = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
    
            // Sanitize the file name
            $safeName = Str::slug($originalName);
    
            // Make the filename unique by appending a number if it already exists
            $counter = 1;
            $filename = $safeName . '.' . $extension;
    
            // Check if file exists and append counter number to filename
            while (Storage::disk('private')->exists('absences/' . $filename)) {
                $filename = $safeName . '-' . $counter . '.' . $extension;
                $counter++;
            }
    
            // Store the file using the 'private' disk
            $filePath = $request->file('file')->storeAs('absences', $filename, 'private');
    
            // Set the file_path on the model
            $absenceExcuse->file_path = $filePath;
    
    
        }
        $absenceExcuse->save();

        return redirect()->route('absenceExcuses.read')->with('success', 'Absence excuse added successfully.');
    }

    public function updateStatus(Request $request){
        
        // Validate the request
        $validatedData = $request->validate([
            'id' => 'required|integer|exists:absence_excuses,id',
            'status' => 'required',
        ]);
        

        // Find the AbsenceExcuse by ID
        $absenceExcuse = AbsenceExcuse::find($validatedData['id']);
        $user = auth()->user(); // Get the authenticated user


        // // Check if the user is authorized to update the status
        if ($user->roles->where('slug', 'faculty-member')->isEmpty()){
            abort(403, 'Unauthorized action.');
        }

        // // Update the status
        $absenceExcuse->status = $validatedData['status'];
        $absenceExcuse->save();

        // Return a JSON response
        return response()->json(['message' => 'Absence status updated successfully.']);
    }



}



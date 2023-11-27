<?php


namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SemesterApologyExcuse;
use Illuminate\Support\Facades\Storage;
use OpenAdmin\Admin\Auth\Database\Administrator;


class SemesterApologyExcuseController extends Controller
{

    public function get()
    {

        $user = auth()->user(); // Get the authenticated user
        // dd($user->roles);
        if ($user->roles->where('slug', 'student')->isNotEmpty()){
            $semesterApologyExcuses = SemesterApologyExcuse::where('student_id', $user->id)->get();
        }

        if ($user->roles->where('slug', 'faculty-member')->isNotEmpty()){
            $semesterApologyExcuses = SemesterApologyExcuse::with(['student' => function ($query) {
                // Preload only students
                $query->whereHas('roles', function ($subQuery) {
                    $subQuery->where('slug', 'student');
                });
            }])
            ->whereHas('student', function ($query) use ($user) {
                // Filter by the faculty id
                $query->whereHas('roles', function ($subQuery) {
                    $subQuery->where('slug', 'student');
                })
                ->where('faculty_id', $user->faculty_id);
            })
            ->get();
        }
        
  


        $cols = [ "File", "Date", "Status", "Created at"];

 

        return view('semesterApologyExcuses.read', [
            'data' => $semesterApologyExcuses,
            'cols' => $cols
        ]);
    }

    public function create()
    {
        $user = auth()->user();
     
        return view('semesterApologyExcuses.create', [
                'data' => 1
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'date' => 'required|date',
        ]);

        $semesterApologyExcuse = new SemesterApologyExcuse();
        $semesterApologyExcuse->student_id = auth()->id();
        $semesterApologyExcuse->date = $request->date;

        if ($request->hasFile('file')) {
           
            $originalName = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
    
            // Sanitize the file name
            $safeName = Str::slug($originalName);
    
            // Make the filename unique by appending a number if it already exists
            $counter = 1;
            $filename = $safeName . '.' . $extension;
    
            // Check if file exists and append counter number to filename
            while (Storage::disk('private')->exists('semester abologies/' . $filename)) {
                $filename = $safeName . '-' . $counter . '.' . $extension;
                $counter++;
            }
    
            // Store the file using the 'private' disk
            $filePath = $request->file('file')->storeAs('semester abologies', $filename, 'private');
    
            // Set the file_path on the model
            $semesterApologyExcuse->file_path = $filePath;
    
    
        }
        $semesterApologyExcuse->save();

        return redirect()->route('semesterApologyExcuses.read')->with('success', 'semester apology excuse added successfully.');
    }

    public function updateStatus(Request $request){
        
        // Validate the request
        $validatedData = $request->validate([
            'id' => 'required|integer|exists:semester_apology_excuses,id',
            'status' => 'required',
        ]);
        

        // Find the AbsenceExcuse by ID
        $absenceExcuse = SemesterApologyExcuse::find($validatedData['id']);
        $user = auth()->user(); // Get the authenticated user


        // // Check if the user is authorized to update the status
        if ($user->roles->where('slug', 'faculty-member')->isEmpty()){
            abort(403, 'Unauthorized action.');
        }

        // // Update the status
        $absenceExcuse->status = $validatedData['status'];
        $absenceExcuse->save();

        // Return a JSON response
        return response()->json(['message' => 'Semester Apology status updated successfully.']);
    }


}
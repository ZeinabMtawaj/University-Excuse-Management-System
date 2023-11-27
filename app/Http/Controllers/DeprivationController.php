<?php


namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Deprivation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class DeprivationController extends Controller
{

    public function get()
    {
        $user = auth()->user(); // Get the authenticated user
        if ($user->roles->where('slug', 'student')->isNotEmpty()){

            $deprivations = Deprivation::with('teacher')
                    ->where('student_id', $user->id) 
                    ->get();
        }
        if ($user->roles->where('slug', 'faculty-member')->isNotEmpty()){

            $deprivations = Deprivation::with('student')
                    ->whereHas('student', function ($query) use ($user) {
                        // This assumes that the 'courses' table has a 'faculty_id' column
                        $query->where('faculty_id', $user->faculty_id);
                    })
                    ->get();

        }
        $cols = [  "File", "Date of deprivation", "Created at"];

        return view('deprivations.read', [
            'data' => $deprivations,
            'cols' => $cols
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        $students = User::with('faculty')
                ->where('faculty_id', $user->faculty_id) 
                ->get();
     
        return view('deprivations.create', [
                'data' => 1,
                'students' => $students,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'date' => 'required|date',
            'student_id' => 'required|exists:admin_users,id',

        ]);

        $deprivation = new Deprivation();
        $deprivation->lifted_by = auth()->id();
        $deprivation->date_of_deprivation= $request->date;
        $deprivation->student_id = $request->student_id;

        if ($request->hasFile('file')) {
           
            $originalName = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
    
            // Sanitize the file name
            $safeName = Str::slug($originalName);
    
            // Make the filename unique by appending a number if it already exists
            $counter = 1;
            $filename = $safeName . '.' . $extension;
    
            // Check if file exists and append counter number to filename
            while (Storage::disk('private')->exists('deprivations/' . $filename)) {
                $filename = $safeName . '-' . $counter . '.' . $extension;
                $counter++;
            }
    
            // Store the file using the 'private' disk
            $filePath = $request->file('file')->storeAs('deprivations', $filename, 'private');
    
            // Set the file_path on the model
            $deprivation->file_path = $filePath;
    
    
        }
        $deprivation->save();

        return redirect()->route('deprivations.read')->with('success', 'deprivation  added successfully.');
    }


}
<?php


namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MedicalExcuse;
use Illuminate\Support\Facades\Storage;
use OpenAdmin\Admin\Auth\Database\Administrator;


class MedicalExcuseController extends Controller
{

    public function get()
    {
        $user = auth()->user(); // Get the authenticated user
        if ($user->roles->where('slug', 'student')->isNotEmpty()){

            $medicalExcuses = MedicalExcuse::with('teacher')
                    ->where('student_id', $user->id) 
                    ->get();
        }
        if ($user->roles->where('slug', 'faculty-member')->isNotEmpty()){

            $medicalExcuses = MedicalExcuse::with('student')
                    ->whereHas('student', function ($query) use ($user) {
                        // This assumes that the 'courses' table has a 'faculty_id' column
                        $query->where('faculty_id', $user->faculty_id);
                    })
                    ->get();

        }


        $cols = [  "File", "Date", "Created at"];

        return view('medicalExcuses.read', [
            'data' => $medicalExcuses,
            'cols' => $cols
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        $students = User::with('faculty')
                ->where('faculty_id', $user->faculty_id) 
                ->get();
     
        return view('medicalExcuses.create', [
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

        $medicalExcuse = new MedicalExcuse();
        $medicalExcuse->teacher_id = auth()->id();
        $medicalExcuse->date = $request->date;
        $medicalExcuse->student_id = $request->student_id;

        if ($request->hasFile('file')) {
           
            $originalName = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
    
            // Sanitize the file name
            $safeName = Str::slug($originalName);
    
            // Make the filename unique by appending a number if it already exists
            $counter = 1;
            $filename = $safeName . '.' . $extension;
    
            // Check if file exists and append counter number to filename
            while (Storage::disk('private')->exists('medical excuses/' . $filename)) {
                $filename = $safeName . '-' . $counter . '.' . $extension;
                $counter++;
            }
    
            // Store the file using the 'private' disk
            $filePath = $request->file('file')->storeAs('medical excuses', $filename, 'private');
    
            // Set the file_path on the model
            $medicalExcuse->file_path = $filePath;
    
    
        }
        $medicalExcuse->save();

        return redirect()->route('medicalExcuses.read')->with('success', 'medical excuse excuse added successfully.');
    }


}
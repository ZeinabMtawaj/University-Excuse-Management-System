<?php


namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use OpenAdmin\Admin\Auth\Database\Administrator;


class UserController extends Controller
{


    public function create(){
        $faculties = Faculty::all();
        return view('users.sign', [
        'activeTab' => 'signup',
        'faculties' => $faculties
        ]);
    }


    public function dashboard(){
        
       return view('users.dashboard',[
        'data' => null,
        'cols' => null
       ]);
    }

    public function store(Request $request){
        $formFields = $request->validate(
            [
                'name' => ['required', 'min:3'],
                'username' => ['required', Rule::unique('admin_users', 'username')],
                'academic_number' => ['required', Rule::unique('admin_users', 'academic_number')],
                'password' => ['required', 'confirmed', 'min:6'],
                'faculty_id' => []
            ]);
            $formFields['password'] = bcrypt($formFields['password']);
            $user = User::create($formFields);

            auth()->login($user);
            return redirect('/');
            
    }

    public function logout(Request $request){
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
        
    }


    public function signin(){
        $faculties = Faculty::all();
        return view('users.sign', [
        'activeTab' => 'signin',
        'faculties' => $faculties
        ]);
    }

    public function authenticate(Request $request){
        $formFields = $request->validate(
            [
                'academic_number' => ['required'],
                'password' => ['required'],
            ]);
        if (auth()->attempt($formFields)){
            $request->session()->regenerate();
            return redirect('/');
        }

        return back()->withErrors(
            [
                'invalidCred' => 'invalid credentials'

            ])->withInput();

       



    }


    public function edit(){
        $faculties = Faculty::all();
        return view('users.update', [
        'faculties' => $faculties,
        'user' => Auth::user()
        ]);

    }



    public function update(Request $request)
    {
        // Validate the request data
        $request->validate([

            'name' => ['required', 'min:3'],
            'username' => 'required|unique:admin_users,username,' . Auth::id(),
            'academic_number' => 'required|unique:admin_users,academic_number,' . Auth::id(),
            'password' => ['confirmed']

        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Update the user's profile with the validated data
        $user->name = $request->name;
        $user->username = $request->username;
        $user->academic_number = $request->academic_number;
        $user->faculty_id = $request->faculty_id;

       
    // Update the password if provided
        if ($request->filled('password')) {
            $validatedData = $request->validate([
                'password' => 'required|confirmed|min:8',
            ]);

            $user->password = Hash::make($validatedData['password']);
        }

        // Save the changes
        $user->save();

        // Redirect back with a success message
        return redirect()->back();
    }



    public function getTeachersForStudent(Request $request)
    {
        $studentId = $request->input('query'); // assuming the input name is 'query'
        $student = Administrator::find($studentId);
        if (!$student) {
            return response()->json([]);
        }

        $roleModel = config('admin.database.roles_model');
        $role2 = $roleModel::where('slug', 'faculty-member')->firstOrFail(); 
        $facultyId = $student->faculty_id;

        $teachers = $role2->teacherUsers()->where('faculty_id', $facultyId)->get();

        $formattedTeachers = $teachers->map(function ($teacher) {
            return ['id' => $teacher->id, 'text' => $teacher->name];
        });
    
        return response()->json($formattedTeachers);
    
        // return response()->json($teachers);
    }

}
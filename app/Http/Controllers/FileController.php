<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsenceExcuse;
use App\Models\CourseApologyExcuse;
use App\Models\Deprivation;
use App\Models\MedicalExcuse;
use App\Models\SemesterApologyExcuse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;


class FileController extends Controller
{
    public function download($filename)
    {
        $path = public_path('uploads\files\\' . $filename);
        // dd($path);



        if (!file_exists($path)) {
            abort(404);
        }

        $headers = [
            'Content-Description' => 'File Transfer',
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename={$filename}",
            'filename' => $filename
        ];
    
        return Response::download($path, $filename, $headers);
    }


    // public function downloadPrivate($filename)
    // {
    //     // Check user permissions
    //     // if (!auth()->user()->can('download', CourseApologyExcuse::class)) {
    //     //     abort(403);
    //     // }

    //     // Define the path to the file in the storage/app/private directory
    //     $path = storage_path('app/private/' . $filename);

    //     // Check if file exists
    //     if (!file_exists($path)) {
    //         abort(404);
    //     }

    //     // Serve the file
    //     return response()->download($path, $filename);
    // }

    public function downloadPrivate(Request $request, $model, $folder, $id)
    {
        
        // Resolve the model class from the provided identifier
        $modelClass = $this->getModelClass($model);
    
        // Find the file record in the model by ID
        $fileRecord = $modelClass::findOrFail($id);

        // If you need to verify the folder as well, you can add additional checks here.
        // For example, if the folder is part of the file path stored in the model, verify it matches.
        if (strpos($fileRecord->file_path, "{$folder}/") !== 0) {

            abort(404, 'File not found in the specified folder.');
        }
        // Now you have the file path from the file record
        $filePath = $fileRecord->file_path;
        // $adminUser = Admin::user();


        // // Check if the authenticated user is the student associated with the file or an admin
        // $isOwner = $adminUser->id == $fileRecord->student_id;
        // $isAdmin =$adminUser->roles()->where('name', 'super-admin')->exists(); // Assuming you have a method to check if the user is an admin
    
        // if ($isOwner || $isAdmin){
        // // Instead of checking permissions with policies, we directly compare the user IDs
        //     if (!Storage::disk('private')->exists($filePath)) {
        //         abort(404, 'File not found.');
        //     }
    
            $fileContent = Storage::disk('private')->get($filePath);
            $mimeType = Storage::disk('private')->mimeType($filePath);
            $fileName = basename($filePath);
    
            return response($fileContent, 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            ]);
        // } else {
        //     abort(403, 'You do not have permission to download this file.');
        // }
    }
    

protected function getModelClass($modelIdentifier)
{
    // Map model identifiers to their respective model class namespaces
    $models = [
        'absence-excuse' => AbsenceExcuse::class,
        'course-abology-excuse' => CourseApologyExcuse::class,
        'semester-abology-excuse' => SemesterApologyExcuse::class,
        'medical-excuse' => MedicalExcuse::class,
        'deprivation' => Deprivation::class,
        // Add other model identifier mappings here
    ];

    return $models[$modelIdentifier] ?? abort(404, 'Model not found.');
}






}

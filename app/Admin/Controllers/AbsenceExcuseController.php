<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use \App\Models\AbsenceExcuse;
use OpenAdmin\Admin\Controllers\AdminController;

class AbsenceExcuseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Absence Excuse';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    



    protected function grid()
    {
        $grid = new Grid(new AbsenceExcuse());

        $grid->column('id', __('admin.id'));
        $grid->column('student.username', __('admin.student'));
        $grid->column('course.name', __('admin.course'));
       

        // $grid->column('file_path', __('File'))->display(function ($file_path) {
        //     if ($file_path) {
        //         $name_of_file = basename($file_path); // Extracts file name from file path
        //         $url = route('download', ['filename' => $name_of_file]); // Use the route to generate the URL
        //         // Use JavaScript to download the file without changing the page state
        //         return "<a href='#' onclick='event.preventDefault(); window.location.href=\"{$url}\";'>{$name_of_file}</a>";
        //     }
        //     return 'No file';
        // });


        // $grid->column('file_path', __('File'))->display(function ($file_path) {
        //     if ($file_path) {
        //         $name_of_file = basename($file_path);
        //         $url = route('secure_download', ['filename' => $name_of_file]); // Change to use a secure download route
        //         return "<a href='#' onclick='event.preventDefault(); window.location.href=\"{$url}\";'>{$name_of_file}</a>";
        //     }
        //     return 'No file';
        // });

        // In AbsenceExcuseController
        $grid->column('file_path', __('admin.file'))->display(function ($file_path) {
            $currentId = $this->getAttribute('id');

            if ($file_path) {
                $name_of_file = basename($file_path);

                // Generate the URL to the FileController's download method including the model identifier and file path
                $url = route('file.download', [
                    'model' => 'absence-excuse', // This is a unique identifier for the AbsenceExcuse model
                    'folder' => 'absences', // The folder where files are stored
                    'id' => $currentId, 
                ]);
                return "<a href='#' onclick='event.preventDefault(); window.location.href=\"{$url}\";'>{$name_of_file}</a>";            }
            return 'No file';
        });

        

        $grid->column('date', __('admin.date'));
        $grid->column('status', __('admin.status'));
        $grid->column('created_at', __('admin.created_at'));
        $grid->column('updated_at', __('admin.updated_at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(AbsenceExcuse::findOrFail($id));

        $show->field('id', __('admin.id'));
        $show->field('student.username', __('admin.student'));
        $show->field('course.name', __('admin.course'));
        $show->field('file_path', __('admin.file'));
        $show->field('date', __('admin.date'));
        $show->field('status', __('admin.status'));
        $show->field('created_at', __('admin.created_at'));
        $show->field('updated_at', __('admin.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new AbsenceExcuse());

           // Fetch the student users
        $roleModel = config('admin.database.roles_model');
        $role = $roleModel::where('slug', 'student')->firstOrFail(); // Ensure the 'student' role exists
        $students = $role->studentUsers()->get()->pluck('name', 'id'); // Get students as an array [id => name]

        // Replace the student_id field with a select dropdown
        $form->select('student_id', __('admin.student'))
            ->options($students)
            ->required()
            ->load('course_id', '/api/courses', 'id', 'text'); // This is the endpoint that will return courses for the selected student

   // Initially, the course dropdown is empty
        $form->select('course_id', __('admin.course'))
                ->options(function ($id) {
                    // This will prepopulate the course dropdown when editing a form
                    if ($id) {
                        return \App\Models\Course::where('id', $id)->pluck('name', 'id');
                    }
                })
                ->rules('required');



        // $form->file('file_path', __('File'))->rules('required');

      $form->file('file_path', __('admin.file'))
            ->rules('required')
            ->move('absences/', date('YmdHis') . '-' . Str::random(7))
            ->disk('private');



        $form->date('date', __('admin.date'))->default(date('Y-m-d')) ->rules('required');
        // $form->text('status', __('Status'));

        return $form;
    }
}

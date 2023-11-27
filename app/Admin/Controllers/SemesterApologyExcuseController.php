<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Illuminate\Support\Str;
use \App\Models\SemesterApologyExcuse;
use OpenAdmin\Admin\Controllers\AdminController;

class SemesterApologyExcuseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Semester Apology Excuse';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SemesterApologyExcuse());

        $grid->column('id', __('admin.id'));
        $grid->column('student.username', __('admin.student'));

        $grid->column('file_path', __('admin.file'))->display(function ($file_path) {
            $currentId = $this->getAttribute('id');

            if ($file_path) {
                $name_of_file = basename($file_path);

                // Generate the URL to the FileController's download method including the model identifier and file path
                $url = route('file.download', [
                    'model' => 'semester-abology-excuse', // This is a unique identifier for the AbsenceExcuse model
                    'folder' => 'semester abologies', // The folder where files are stored
                    'id' => $currentId, // The file name to download
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
        $show = new Show(SemesterApologyExcuse::findOrFail($id));

        $show->field('id', __('admin.id'));
        $show->field('student.username', __('admin.student'));
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
        $form = new Form(new SemesterApologyExcuse());

        $roleModel = config('admin.database.roles_model');
        $role = $roleModel::where('slug', 'student')->firstOrFail(); // Ensure the 'student' role exists
        $students = $role->studentUsers()->get()->pluck('name', 'id'); // Get students as an array [id => name]

        // Replace the student_id field with a select dropdown
        $form->select('student_id', __('admin.student'))
            ->options($students)
            ->required();
        
        $form->file('file_path', __('admin.file'))
            ->rules('required')
            ->move('semester abologies/', date('YmdHis') . '-' . Str::random(7))
            ->disk('private');
            
        $form->date('date', __('admin.date'))->default(date('Y-m-d'));

        return $form;
    }
}

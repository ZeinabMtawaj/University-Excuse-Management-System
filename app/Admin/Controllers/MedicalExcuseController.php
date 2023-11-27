<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Illuminate\Support\Str;
use \App\Models\MedicalExcuse;
use OpenAdmin\Admin\Auth\Database\Administrator;
use OpenAdmin\Admin\Controllers\AdminController;

class MedicalExcuseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Medical Excuse';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MedicalExcuse());

        $grid->column('id', __('admin.id'));
        $grid->column('student.username', __('admin.student'));
        $grid->column('teacher.username', __('admin.teacher'));

        $grid->column('file_path', __('admin.file'))->display(function ($file_path) {
            $currentId = $this->getAttribute('id');

            if ($file_path) {
                $name_of_file = basename($file_path);

                // Generate the URL to the FileController's download method including the model identifier and file path
                $url = route('file.download', [
                    'model' => 'medical-excuse', // This is a unique identifier for the AbsenceExcuse model
                    'folder' => 'medical excuses', // The folder where files are stored
                    'id' => $currentId, // The file name to download
                ]);
                return "<a href='#' onclick='event.preventDefault(); window.location.href=\"{$url}\";'>{$name_of_file}</a>";            }
            return 'No file';
        });

        $grid->column('date', __('admin.date'));
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
        $show = new Show(MedicalExcuse::findOrFail($id));

        $show->field('id', __('admin.id'));
        $show->field('student.username', __('admin.student'));
        $show->field('teacher.username', __('admin.teacher'));
        $show->field('file_path', __('admin.file'));
        $show->field('date', __('admin.date'));
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
        $form = new Form(new MedicalExcuse());

       
        $roleModel = config('admin.database.roles_model');
        $role = $roleModel::where('slug', 'student')->firstOrFail(); 
        $students = $role->studentUsers()->get()->pluck('name', 'id'); 

       


        $form->select('student_id', __('admin.student'))
            ->options($students)
            ->required()
            ->load('teacher_id', '/api/teachers', 'id', 'text');

        $form->select('teacher_id', __('admin.teacher'))
             ->options(function ($id) {
                if ($id) {
                    return Administrator::where('id', $id)->pluck('name', 'id');
                }
            })
            ->rules('required');
            
        $form->file('file_path', __('admin.file'))
            ->rules('required')
            ->move('medical excuses/', date('YmdHis') . '-' . Str::random(7))
            ->disk('private');
            
        $form->date('date', __('admin.date'))->default(date('Y-m-d'));

        return $form;
    }
}

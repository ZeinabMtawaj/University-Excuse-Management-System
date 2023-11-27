<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Illuminate\Support\Str;
use \App\Models\Deprivation;
use OpenAdmin\Admin\Auth\Database\Administrator;
use OpenAdmin\Admin\Controllers\AdminController;

class DeprivationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Deprivation';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Deprivation());

        $grid->column('id', __('admin.id'));
        $grid->column('student.username', __('admin.student'));
        $grid->column('file_path', __('admin.file'))->display(function ($file_path) {
            $currentId = $this->getAttribute('id');

            if ($file_path) {
                $name_of_file = basename($file_path);

                // Generate the URL to the FileController's download method including the model identifier and file path
                $url = route('file.download', [
                    'model' => 'deprivation', // This is a unique identifier for the AbsenceExcuse model
                    'folder' => 'deprivations', // The folder where files are stored
                    'id' => $currentId, // The file name to download
                ]);
                return "<a href='#' onclick='event.preventDefault(); window.location.href=\"{$url}\";'>{$name_of_file}</a>";            }
            return 'No file';
        });
        $grid->column('date_of_deprivation', __('admin.date_of_deprivation'));
        $grid->column('teacher.username', __('admin.lifted_by'));

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
        $show = new Show(Deprivation::findOrFail($id));

        $show->field('id', __('admin.id'));
        $show->field('student.username', __('admin.student'));
        $show->field('file_path', __('File path'));
        $show->field('date_of_deprivation', __('admin.date_of_deprivation'));
        $show->field('teacher.username', __('admin.lifted_by'));

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
        $form = new Form(new Deprivation());

        $roleModel = config('admin.database.roles_model');
        $role = $roleModel::where('slug', 'student')->firstOrFail(); 
        $students = $role->studentUsers()->get()->pluck('name', 'id'); 

       


        $form->select('student_id', __('admin.student'))
            ->options($students)
            ->required()
            ->load('lifted_by', '/api/teachers', 'id', 'text');

        $form->select('lifted_by', __('admin.lifted_by'))
            ->options(function ($id) {
            if ($id) {
                return Administrator::where('id', $id)->pluck('name', 'id');
            }
        })
        ->rules('required');
        
        $form->file('file_path', __('admin.file'))
            ->rules('required')
            ->move('deprivations/', date('YmdHis') . '-' . Str::random(7))
            ->disk('private');
            
        $form->date('date_of_deprivation', __('admin.date_of_deprivation'))->default(date('Y-m-d'));


        return $form;
    }
}

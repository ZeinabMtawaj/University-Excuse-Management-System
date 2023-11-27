<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Faculty;

class FacultyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Faculty';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Faculty());

        $grid->column('id', __('admin.id'));
        $grid->column('name', __('admin.name'));
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
        $show = new Show(Faculty::findOrFail($id));

        $show->field('id', __('admin.id'));
        $show->field('name', __('admin.name'));
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
        $form = new Form(new Faculty());

        $form->text('name', __('admin.name'))
            ->rules(function ($form) {
                // If $form->model()->id is not null, we're in edit mode
                if ($id = $form->model()->id) {
                    return 'required|unique:faculties,name,' . $id;
                } else {
                    return 'required|unique:faculties,name';
                }
            });

        return $form;
    }

}

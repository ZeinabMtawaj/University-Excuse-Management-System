<?php

namespace App\Admin\Controllers;

use \App\Models\Course;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Auth\Database\Administrator;
use OpenAdmin\Admin\Controllers\AdminController;

class CourseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Course';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Course());

        $grid->column('id', __('admin.id'));
        $grid->column('name', __('admin.name'));
        $grid->column('faculty.name', __('admin.faculty'));
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
        $show = new Show(Course::findOrFail($id));

        $show->field('id', __('admin.id'));
        $show->field('name', __('admin.name'));
        $show->field('faculty.name', __('admin.faculty'));
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
        $form = new Form(new Course());

        $form->text('name', __('admin.name'))->rules('required');;
        // $form->number('faculty_id', __('Faculty id'));
        $form->select('faculty_id', __('admin.faculty'))
        ->options(\App\Models\Faculty::all()->pluck('name', 'id'))
        ->rules('required');

        return $form;
    }


    public function getCoursesForStudent(Request $request)
    {
        dd(1);
        $studentId = $request->input('query'); // assuming the input name is 'query'
        $student = Administrator::find($studentId);
        dd( $student);
        if (!$student) {
            return response()->json([]);
        }
    
        $courses = \App\Models\Course::where('faculty_id', $student->faculty_id)->get(['id', 'name as text']);
        return response()->json($courses);
    }

    
}

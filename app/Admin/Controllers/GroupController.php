<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\ConstantHelper;
use App\Http\Models\Group;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class GroupController extends AdminController{
 /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Nhóm';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Group());

        $grid->column('name', __('Tên nhóm'));
        $grid->column('gender', __('Giới tính'))->display(function ($gender) {
            return UtilsCommonHelper::commonCodeGridFormatter("Gender", "description_vi", $gender);
        }); 
        $grid->column('from_age', __('Tuổi nhỏ nhất'))->label('default');
        $grid->column('to_age', __('Tên lớn nhất'))->label('default');
        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "grid");
        });
        $grid->column('created_at', __('Ngày tạo'))->display(function ($createdAt) {
            return ConstantHelper::dateFormatter($createdAt);
        });        
        $grid->column('updated_at', __('Ngày cập nhật'))->display(function ($updatedAt) {
            return ConstantHelper::dateFormatter($updatedAt);
        });  
        $grid->model()->orderBy('id', 'desc');
        $grid->fixColumns(0, 0);
        $grid->disableExport();
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
        $show = new Show(Group::findOrFail($id));

        $show->field('name', __('Tên nhóm'));
        $show->field('gender', __('Giới tính'))->as(function($gender){
            return UtilsCommonHelper::commonCodeGridFormatter("Gender", "description_vi", $gender);
        });
        $show->field('from_age', __('Tuổi nhỏ nhất'));
        $show->field('to_age', __('Tên lớn nhất'));
        $show->field('status', __('Trạng thái'))->as(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core");
        });
        $show->field('created_at', __('Ngày tạo'));
        $show->field('updated_at', __('Ngày cập nhật'));

        return $show;
    }

     /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $statusOptions = (new UtilsCommonHelper)->statusFormFormatter();
        $statusDefault = $statusOptions->keys()->first();
        $genderOptions = (new UtilsCommonHelper)->commonCode("Gender", "description_vi", "value");

        $form = new Form(new Group());
        $form->text('name', __('Tên nhóm'))->required()->help("Ví dụ: U18");
        $form->select('gender', __('Giới tính'))->options($genderOptions)->required();
        $form->number('from_age', __('Tuổi nhỏ nhất'));
        $form->number('to_age', __('Tên lớn nhất'));
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault)->required();
        return $form;
    }
}
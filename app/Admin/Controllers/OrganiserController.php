<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\ConstantHelper;
use App\Http\Models\Organiser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrganiserController extends AdminController{
 /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Ban tổ chức';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Organiser());

        $grid->column('name', __('Tên ban tổ chức'));
        $grid->column('email', __('Email'));
        $grid->column('phone_number', __('Số điện thoại'));
        $grid->column('contact_person', __('Người liên hệ'));
        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", "grid");
        });  
        $grid->column('created_at', __('Ngày tạo'))->display(function ($createdAt) {
            return ConstantHelper::dateFormatter($createdAt);
        });        
        $grid->column('updated_at', __('Ngày cập nhật'))->display(function ($updatedAt) {
            return ConstantHelper::dateFormatter($updatedAt);
        });  
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
        $show = new Show(Organiser::findOrFail($id));

        $show->field('name', __('Tên ban tổ chức'));
        $show->field('email', __('Email'));
        $show->field('phone_number', __('Số điện thoại'));
        $show->field('contact_person', __('Người liên hệ'));
        $show->field('status', __('Trạng thái'))->as(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", "detail");
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

        $form = new Form(new Organiser());
        $form->text('name', __('Tên ban tổ chức'));
        $form->text('email', __('Email'));
        $form->mobile('phone_number', __('Số điện thoại'))->options(['mask' => '999 999 9999'])->required();
        $form->text('contact_person', __('Người liên hệ'));
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault)->required();
        return $form;
    }
}
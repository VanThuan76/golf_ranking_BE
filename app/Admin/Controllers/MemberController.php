<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\ConstantHelper;
use App\Http\Models\Member;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MemberController extends AdminController{
 /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Thành viên';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Member());

        $grid->column('group.name', __('Nhóm'));
        $grid->column('vjgr_code', __('Mã thành viên'));
        $grid->column('name', __('Họ và tên'));
        $grid->column('gender', __('Giới tính'))->display(function ($gender) {
            return UtilsCommonHelper::commonCodeGridFormatter("Gender", "description_vi", $gender);
        });
        $grid->column('date_of_birth', __('Ngày sinh'))->display(function ($dateOfBirth) {
            return ConstantHelper::dayFormatter($dateOfBirth);
        }); 
        $grid->column('nationality', __('Quốc gia'));
        $grid->column('email', __('Email'));
        $grid->column('phone_number', __('Số điện thoại'));
        $grid->column('handicap_vga', __('handicap_vga'));
        $grid->column('points', __('Điểm thưởng'));
        $grid->column('counting_tournament', __('Số lần tham dự giải'));
        $grid->column('number_of_wins', __('Số lần vô địch'));
        $grid->column('best_rank', __('Xếp hạng cao nhất'));
        $grid->column('relationship', __('Mối quan hệ'));
        $grid->column('guardian_name', __('Tên người bảo trợ'));
        $grid->column('guardian_phone', __('Số điện thoại người bảo trợ'));
        $grid->column('guardian_email', __('Email người bảo trợ'));
        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "grid");
        });  
        $grid->column('created_at', __('Ngày tạo'))->display(function ($createdAt) {
            return ConstantHelper::dateFormatter($createdAt);
        });        
        $grid->column('updated_at', __('Ngày cập nhật'))->display(function ($updatedAt) {
            return ConstantHelper::dateFormatter($updatedAt);
        });  
        $grid->model()->orderBy('id');
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
        $show = new Show(Member::findOrFail($id));

        $show->field('vjgr_code', __('Mã thành viên'));
        $show->field('name', __('Họ và tên'));
        $show->field('gender', __('Giới tính'))->as(function($gender){
            return UtilsCommonHelper::commonCodeGridFormatter("Gender", "description_vi", $gender);
        });
        $show->field('date_of_birth', __('Ngày sinh'))->as(function ($dateOfBirth) {
            return ConstantHelper::dateFormatter($dateOfBirth);
        }); 
        $show->field('nationality', __('Quốc gia'));
        $show->field('email', __('Email'));
        $show->field('phone_number', __('Số điện thoại'));
        $show->field('handicap_vga', __('handicap_vga'));
        $show->field('points', __('Điểm'));
        $show->field('counting_tournament', __('Số lần tham dự giải'));
        $show->field('number_of_wins', __('Số lần vô địch'));
        $show->field('best_rank', __('Vô địch hạng'));
        $show->field('relationship', __('Mối quan hệ'));
        $show->field('guardian_name', __('Tên người bảo trợ'));
        $show->field('guardian_phone', __('Số điện thoại người bảo trợ'));
        $show->field('guardian_email', __('Email người bảo trợ'));
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
        $genderOptions = (new UtilsCommonHelper)->commonCode("Gender", "description_vi", "value");

        $form = new Form(new Member());
        $form->text('name', __('Họ và tên'))->required();
        $form->select('gender', __('Giới tính'))->options($genderOptions)->required();
        $form->date('date_of_birth', __('Ngày sinh'))->required();
        $form->text('nationality', __('Quốc gia'));
        $form->text('email', __('Email'));
        $form->mobile('phone_number', __('Số điện thoại'))->options(['mask' => '999 999 9999'])->required();
        $form->text('handicap_vga', __('handicap_vga'));
        $form->number('points', __('Điểm'));
        $form->number('counting_tournament', __('Số lần tham dự giải'));
        $form->number('number_of_wins', __('Số lần vô địch'));
        $form->text('best_rank', __('Vô địch hạng'));
        $form->text('relationship', __('Mối quan hệ'));
        $form->text('guardian_name', __('Tên người bảo trợ'));
        $form->mobile('guardian_phone', __('Số điện thoại người bảo trợ'))->options(['mask' => '999 999 9999'])->required();
        $form->text('guardian_email', __('Email người bảo trợ'));
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault)->required();
        return $form;
    }
}
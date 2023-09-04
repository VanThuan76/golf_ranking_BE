<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\ConstantHelper;
use App\Http\Models\TournamentGroup;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TournamentGroupController extends AdminController{
 /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Nhóm giải đấu';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TournamentGroup());

        $grid->column('name', __('Nhóm giải'));
        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "grid");
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
        $show = new Show(TournamentGroup::findOrFail($id));

        $show->field('name', __('Nhóm giải'));
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

        $form = new Form(new TournamentGroup());
        $form->text('name', __('Nhóm giải'));
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault)->required();
        return $form;
    }
}
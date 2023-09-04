<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\ConstantHelper;
use App\Http\Models\TournamentSummary;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TournamentSummaryController extends AdminController{
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
        $grid = new Grid(new TournamentSummary());

        $grid->column('tournament.name', __('Tên giải'));
        $grid->column('member.name', __('Tên thành viên'));
        $grid->column('finish', __('Hoàn thành'));
        $grid->column('to_par', __('to_par'));
        $grid->column('total_score', __('Toàn bộ điểm'));
        $grid->column('point', __('Điểm'));
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
        $show = new Show(TournamentSummary::findOrFail($id));

        $show->field('tournament.name', __('Tên giải'));
        $show->field('member.name', __('Tên thành viên'));
        $show->field('finish', __('Hoàn thành'));
        $show->field('to_par', __('to_par'));
        $show->field('total_score', __('Toàn bộ điểm'));
        $show->field('point', __('Điểm'));
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

        $form = new Form(new TournamentSummary());
        $form->text('name', __('Nhóm giải'));
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault)->required();
        return $form;
    }
}
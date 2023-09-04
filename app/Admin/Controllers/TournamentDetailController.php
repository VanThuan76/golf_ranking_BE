<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\ConstantHelper;
use App\Http\Models\TournamentDetail;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TournamentDetailController extends AdminController{
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
        $grid = new Grid(new TournamentDetail());

        $grid->column('tournament.name', __('Tên giải'));
        $grid->column('member.name', __('Tên thành viên'));
        $grid->column('round_number', __('Số vòng'));
        $grid->column('score', __('Điểm'));
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
        $show = new Show(TournamentDetail::findOrFail($id));

        $show->field('tournament.name', __('Tên giải'));
        $show->field('member.name', __('Tên thành viên'));
        $show->field('round_number', __('Số vòng'));
        $show->field('score', __('Điểm'));
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

        $form = new Form(new TournamentDetail());
        $form->text('name', __('Nhóm giải'));
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault)->required();
        return $form;
    }
}
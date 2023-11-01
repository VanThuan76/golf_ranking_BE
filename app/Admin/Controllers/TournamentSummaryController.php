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
    protected $title = 'Tổng hợp giải đấu';

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
            return UtilsCommonHelper::commonCodeGridFormatter('TournamentStatus', 'description_vi', $status);
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
            return UtilsCommonHelper::commonCodeGridFormatter('TournamentStatus', 'description_vi', $status);
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
        $tournaments = (new UtilsCommonHelper)->optionsTournament();
        $members = (new UtilsCommonHelper)->optionsMember();
        $statusOptions = (new UtilsCommonHelper)->statusCustomizeFormFormatter("TournamentStatus");
        $statusDefault = $statusOptions->keys()->first();

        $form = new Form(new TournamentSummary());
        if($form->isEditing()){
            $id = request()->route()->parameter('tournament_summary');
            $tournamentId = $form->model()->find($id)->getOriginal("tournament_id");
            $memberId = $form->model()->find($id)->getOriginal("member_id");
            $form->select('tournament_id', __('Giải đấu'))->options($tournaments)->default($tournamentId)->required();
            $form->select('member_id', __('Tên thành viên'))->options($members)->default($memberId)->required();
            $form->number('finish', __('Hoàn thành'));
            $form->number('to_par', __('to_par'));
            $form->number('total_score', __('Toàn bộ điểm'));
            $form->number('point', __('Điểm'));
        }else{
            $form->select('tournament_id', __('Giải đấu'))->options($tournaments)->required();
        }
        
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault)->required();
        return $form;
    }
}
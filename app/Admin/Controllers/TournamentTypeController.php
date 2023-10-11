<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\ConstantHelper;
use App\Http\Models\TournamentType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TournamentTypeController extends AdminController{
 /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Loại giải đấu';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TournamentType());

        $grid->column('tournamentGroup.name', __('Nhóm giải'));
        $grid->column('name', __('Loại giải'));
        $grid->column('coefficient', __('Hệ số'));
        $grid->column('total_point', __('Tổng điểm'));
        $grid->column('pointed_position', __('Vị trí nhọn'));
        $grid->column('bonus_champion_point', __('Điểm thưởng'));
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
        $show = new Show(TournamentType::findOrFail($id));

        $show->field('tournamentGroup.name', __('Nhóm giải'));
        $show->field('name', __('Loại giải'));
        $show->field('coefficient', __('Hệ số'));
        $show->field('total_point', __('Tổng điểm'));
        $show->field('pointed_position', __('Vị trí nhọn'));
        $show->field('bonus_champion_point', __('Điểm thưởng'));
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
        $tournamentGroups = (new UtilsCommonHelper)->optionsTournamentType();
        $statusOptions = (new UtilsCommonHelper)->statusFormFormatter();
        $statusDefault = $statusOptions->keys()->first();

        $form = new Form(new TournamentType());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('tournament_type');
            $tournamentGroupId = $form->model()->find($id)->getOriginal("tournament_group_id");
            $form->select('tournament_group_id', __('Nhóm giải'))->options($tournamentGroups)->default($tournamentGroupId)->required();
        }else{
            $form->select('tournament_group_id', __('Nhóm giải'))->options($tournamentGroups)->required();
        }
        $form->text('name', __('Loại giải'));
        $form->number('coefficient', __('Hệ số'));
        $form->number('total_point', __('Tổng điểm'));
        $form->number('pointed_position', __('Vị trí nhọn'));
        $form->number('bonus_champion_point', __('Điểm thưởng'));
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault)->required();
        return $form;
    }
}
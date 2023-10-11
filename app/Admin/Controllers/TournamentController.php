<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\ConstantHelper;
use App\Http\Models\Tournament;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TournamentController extends AdminController{
 /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Giải đấu';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Tournament());

        $grid->column('tournamentType.name', __('Loại giải'));
        $grid->column('tournamentGroup.name', __('Nhóm giải'));
        $grid->column('organiser.name', __('Tên ban tổ chức'));
        $grid->column('name', __('Tên giải'));
        $grid->column('region', __('Khu vực'))->display(function ($region) {
            return UtilsCommonHelper::commonCodeGridFormatter("Region", "description_vi", $region);
        });
        $grid->column('country', __('Quốc gia'));
        $grid->column('city', __('Thành phố'));
        $grid->column('from_date', __('Ngày bắt đầu'))->display(function ($fromDate) {
            return ConstantHelper::dayFormatter($fromDate);
        });
        $grid->column('to_date', __('Ngày kết thúc'))->display(function ($toDate) {
            return ConstantHelper::dayFormatter($toDate);
        }); 
        $grid->column('number_round', __('Số vòng đấu'));
        $grid->column('format', __('Hình thức thi đấu'))->display(function ($format) {
            return UtilsCommonHelper::commonCodeGridFormatter("Format", "description_vi", $format);
        });
        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::commonCodeGridFormatter("TournamentStatus", "description_vi", $status);
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
        $show = new Show(Tournament::findOrFail($id));

        $show->field('tournamentType.name', __('Loại giải'));
        $show->field('tournamentGroup.name', __('Nhóm giải'));
        $show->field('organiser.name', __('Tên ban tổ chức'));
        $show->field('name', __('Tên giải'));
        $show->field('region', __('Khu vực'))->as(function ($region) {
            return UtilsCommonHelper::commonCodeGridFormatter("Region", "description_vi", $region);
        });
        $show->field('country', __('Quốc gia'));
        $show->field('city', __('Thành phố'));
        $show->field('from_date', __('Ngày bắt đầu'))->as(function ($fromDate) {
            return ConstantHelper::dayFormatter($fromDate);
        }); 
        $show->field('to_date', __('Ngày kết thúc'))->as(function ($toDate) {
            return ConstantHelper::dayFormatter($toDate);
        }); 
        $show->field('number_round', __('Số vòng đấu'));
        $show->field('format', __('Hình thức thi đấu'))->as(function ($format) {
            return UtilsCommonHelper::commonCodeGridFormatter("Format", "description_vi", $format);
        });
        $show->field('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::commonCodeGridFormatter("TournamentStatus", "description_vi", $status);
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
        $tournamentTypes = (new UtilsCommonHelper)->optionsTournamentType();
        $tournamentGroups = (new UtilsCommonHelper)->optionsTournamentGroup();
        $organisers = (new UtilsCommonHelper)->optionsOrganiser();

        $statusOptions = (new UtilsCommonHelper)->commonCode("TournamentStatus", "description_vi", "value");
        $regionOptions = (new UtilsCommonHelper)->commonCode("Region", "description_vi", "value");
        $formatOptions = (new UtilsCommonHelper)->commonCode("Format", "description_vi", "value");

        $form = new Form(new Tournament());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('tournament');
            $tournamentTypeId = $form->model()->find($id)->getOriginal("tournament_type_id");
            $tournamentGroupId = $form->model()->find($id)->getOriginal("tournament_group_id");
            $organiserId = $form->model()->find($id)->getOriginal("organiser_id");

            $form->select('tournament_type_id', __('Loại giải'))->options($tournamentTypes)->default($tournamentTypeId)->required();
            $form->select('tournament_group_id', __('Nhóm giải'))->options($tournamentGroups)->default($tournamentGroupId)->required();
            $form->select('organiser_id', __('Tên ban tổ chức'))->options($organisers)->default($organiserId)->required();
        }else{
            $form->select('tournament_type_id', __('Loại giải'))->options($tournamentTypes)->required();
            $form->select('tournament_group_id', __('Nhóm giải'))->options($tournamentGroups)->required();
            $form->select('organiser_id', __('Tên ban tổ chức'))->options($organisers)->required();
        }
        $form->text('name', __('Tên giải'));
        $form->select('region', __('Khu vực'))->options($regionOptions)->required();
        $form->text('country', __('Quốc gia'));
        $form->text('city', __('Thành phố'));
        $form->date('from_date', __('Ngày bắt đầu'));
        $form->date('to_date', __('Ngày kết thúc'));
        $form->number('number_round', __('Số vòng đấu'));
        $form->select('format', __('Hình thức thi đấu'))->options($formatOptions)->required();
        $form->select('status', __('Trạng thái'))->options($statusOptions)->required();
        return $form;
    }
}
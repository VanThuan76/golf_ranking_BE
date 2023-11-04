<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\ConstantHelper;
use App\Http\Models\TournamentDetailDraft;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Excel;

class TournamentDetailDraftController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Chi tiết giải đấu sơ bộ';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TournamentDetailDraft());

        $grid->column('tournament.name', __('Tên giải'));
        $grid->column('member.name', __('Tên thành viên'));
        $grid->column('round_number', __('Số vòng'));
        $grid->column('score', __('Điểm'));
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
        $show = new Show(TournamentDetailDraft::findOrFail($id));

        $show->field('tournament.name', __('Tên giải'));
        $show->field('member.name', __('Tên thành viên'));
        $show->field('round_number', __('Số vòng'));
        $show->field('score', __('Điểm'));
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
        $form = new Form(new TournamentDetailDraft());
        if ($form->isCreating()) {
            $form->select('tournament_id', __('Giải đấu'))->options($tournaments)->required();
            $form->file('csv_file', __('File CSV'))->rules('required|mimes:csv,txt');
        } else {
            $id = request()->route()->parameter('pre_tournament_detail');
            $tournamentId = $form->model()->find($id)->getOriginal("tournament_id");
            $memberId = $form->model()->find($id)->getOriginal("member_id");
            $form->select('tournament_id', __('Giải đấu'))->options($tournaments)->default($tournamentId)->required();
            $form->select('member_id', __('Tên thành viên'))->options($members)->default($memberId)->required();
            $form->number('round_number', __('Số vòng'));
            $form->number('score', __('Điểm'));
        }
        $form->saving(function ($form) {
            $this->importCsv(request());
        });
        return $form;
    }
    public function importCsv(Request $request)
    {
        $file = $request->file('csv_file');
        if ($file && $file->isValid()) {
            Excel::filter('chunk')->load($file)->chunk(250, function ($results) {
                dd($results);
                $tournamentDetailDraft = TournamentDetailDraft::get();
                foreach ($results as $row) {
                    $tournamentDetailDraft['vjgr_code'] = $row[0];
                    $tournamentDetailDraft['round_number'] = $row[1];
                    $tournamentDetailDraft['score'] = $row[2];
                    $tournamentDetailDraft['to_par'] = $row[3];
                }
                $tournamentDetailDraft->save();
            });
        } else {
            return redirect()->back()->withErrors(['csv_file' => 'File không hợp lệ']);
        }
    }
}

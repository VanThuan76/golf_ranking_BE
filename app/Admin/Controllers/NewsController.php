<?php

namespace App\Admin\Controllers;

use App\Http\Models\Category;
use App\Http\Models\News;
use App\Http\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;

class NewsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'News';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new News());

        $grid->column('title', __('Tiêu đề'))->style('max-width:200px;')->display(function ($title) {
            return "<span>". UtilsCommonHelper::extractContent($title) . "</span>";
        });
        $grid->column('description', __('Mô tả'))->style('max-width:300px;')->display(function ($title) {
            return "<span>". UtilsCommonHelper::extractContent($title) . "</span>";
        });
        $grid->column('image', __('Ảnh'))->image(url(env("AWS_URL")), 50, 50);
        $grid->column('author.name', __('Tác giả'));
        $grid->column('category.title', __('Thể loại'));
        $grid->column('status', __('Trạng thái'))->using(Constant::PAGE_STATUS)->sortable();
        $grid->column('slug', __('Link'))->display(function ($slug) {
            return "<a href='".url('/page/'.$slug)."' target='_blank'>Link</span>";
        });
        $grid->column('created_at', __('Ngày tạo'));
        $grid->column('updated_at', __('Ngày cập nhật'));
        $grid->model()->orderBy('id', 'DESC');
        $grid->fixColumns(0, 0);
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
        $show = new Show(News::findOrFail($id));

        $show->field('title', __('Tiêu đề'));
        $show->field('description', __('Mô tả'));
        $show->field('content', __('Nội dung'));
        $show->field('image', __('Ảnh'));
        $show->field('created_at', __('Ngày tạo'));
        $show->field('updated_at', __('Ngày tạo'));
        $show->field('author_id', __('Tác giả'));
        $show->field('category_id', __('Thể loại'));
        $show->field('published_at', __('Công khai'));
        $show->field('status', __('Trạng thái'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new News());

        $form->text('title', __('Tiêu đề'))->required();
        $form->ckeditor('description', __('Mô tả'))->required();
        $form->ckeditor('content', __('Nội dung'))->required();
        $form->image('image', __('Ảnh'))->insert(public_path('resources/watermark.png'), 'bottom-right', 30, 10);
        $form->select('author_id', __('Tác giả'))->options(User::all()->pluck('name', 'id'))->default(Admin::user()->id)->setWidth(3, 2);
        $form->hidden('slug');
        $form->select('category_id', __('Danh mục'))->options(Category::all()->pluck('title', 'id'))->setWidth(3, 2)->required();
        $form->datetime('published_at', __('Published at'))->default(date('Y-m-d H:i:s'));
        if (Admin::user()->isRole('manager')) {
            $form->select('status', __('Status'))->options(Constant::PAGE_STATUS)->setWidth(3, 2)->default(0);
        } else {
            $form->select('status', __('Status'))->options(Constant::PAGE_STATUS)->setWidth(3, 2)->default(0)->readonly();
        }
        $form->saving(function ($form) {
            if (!($form->model()->id && $form->model()->title == $form->title)){
                $form->slug = UtilsCommonHelper::createSlug($form->title, News::get());
            }
        });

        return $form;
    }
}

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
    protected $title = 'Bài viết';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new News());

        $grid->column('title', __('Tiêu đề'));
        $grid->column('description', __('Mô tả'))->display(function ($title) {
            return UtilsCommonHelper::extractContent($title);
        });
        $grid->column('image', __('Ảnh'))->image(url(env("APP_URL") . '/storage'), 50, 50);
        $grid->column('author.name', __('Tác giả'));
        $grid->column('category.title', __('Thể loại'));
        $grid->column('status', __('Trạng thái'))->using(Constant::PAGE_STATUS)->sortable();
        $grid->column('slug', __('Link'))->display(function ($slug) {
            return "<a href='https://vjgr.com.vn/news/" . $slug. "' target='_blank'>Link</span>";
        });
        $grid->column('created_at', __('Ngày tạo'))->display(function ($date){
            return UtilsCommonHelper::dateFormatter($date);
        });
        $grid->column('updated_at', __('Ngày cập nhật'))->display(function ($date){
            return UtilsCommonHelper::dateFormatter($date);
        });
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

        $show->field('id', __('Id'));
        $show->field('title', __('Tiêu đề'));
        $show->field('description', __('Mô tả'));
        $show->field('content', __('Nội dung'));
        $show->field('image', __('Ảnh'));
        $show->field('created_at', __('Ngày tạo'));
        $show->field('updated_at', __('Ngày tạo'));
        $show->field('author.name', __('Tác giả'));
        $show->field('category.title', __('Thể loại'));
        $show->field('published_at', __('Công khai'));
        $show->field('status', __('Trạng thái'))->as(function ($status) {
            return UtilsCommonHelper::commonCodeGridFormatter("Status", "description_vi", $status);
        });

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

        $form->hidden('slug');
        $form->text('title', __('Tiêu đề'))->required();
        $form->ckeditor('description', __('Mô tả'))->required();
        $form->ckeditor('content', __('Nội dung'))->required();
        $form->image('image', __('Ảnh'))->insert(public_path('resources/watermark.png'), 'bottom-right', 30, 10)->move('/images');
        if($form->isEditing()){
            $id = request()->route()->parameter('news');
            $authorId = $form->model()->find($id)->getOriginal("author_id");
            $categoryId = $form->model()->find($id)->getOriginal("category_id");

            $form->select('author_id', __('Tác giả'))->options(User::all()->pluck('name', 'id'))->default($authorId)->setWidth(3, 2);
            $form->select('category_id', __('Danh mục'))->options(Category::all()->pluck('title', 'id'))->default($categoryId)->setWidth(3, 2)->required();
        }else{
            $form->select('author_id', __('Tác giả'))->options(User::all()->pluck('name', 'id'))->default(Admin::user()->id)->setWidth(3, 2);
            $form->select('category_id', __('Danh mục'))->options(Category::all()->pluck('title', 'id'))->setWidth(3, 2)->required();
        }
        
        $form->datetime('published_at', __('Công khai'))->default(date('Y-m-d H:i:s'));
        if (Admin::user()->isRole('manager')) {
            $form->select('status', __('Trạng thái'))->options(Constant::PAGE_STATUS)->setWidth(3, 2)->default(0);
        } else {
            $form->select('status', __('Trạng thái'))->options(Constant::PAGE_STATUS)->setWidth(3, 2)->default(0)->readonly();
        }
        $form->saving(function ($form) {
            if (!($form->model()->id && $form->model()->title == $form->title)) {
                $form->slug = UtilsCommonHelper::createSlug($form->title, News::get());
            }
        });

        return $form;
    }
}

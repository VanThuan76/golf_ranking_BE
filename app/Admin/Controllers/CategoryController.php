<?php

namespace App\Admin\Controllers;

use App\Http\Models\Category;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;


class CategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Loại tin tức';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category());

        $grid->column('title', __('Tiêu đề'));
        $grid->column('created_at', __('Ngày tạo'))->display(function ($createdAt){
            return UtilsCommonHelper::dateFormatter($createdAt);
        });
        $grid->column('updated_at', __('Ngày cập nhật'))->display(function ($createdAt){
            return UtilsCommonHelper::dateFormatter($createdAt);
        });

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
        $show = new Show(Category::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Tiêu đề'));
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
        $form = new Form(new Category());

        $form->text('title', __('Tiêu đề'));
        $form->hidden('slug');
        $form->image('image', __('Image'))->move('category');
        $form->saving(function ($form) {
            if (!($form->model()->id && $form->model()->title == $form->title)) {
                $form->slug = UtilsCommonHelper::createSlug($form->title, Category::get());
            }
        });
        return $form;
    }
}

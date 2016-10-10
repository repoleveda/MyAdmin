<?php

namespace App\Admin\Controllers;

use App\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\AdminController;

class MyUserController extends Controller
{
    use AdminController;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('header');
            $content->description('description');
//            dd($content);
//            dd($id);
//            dd($this->form());
            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {

        return Admin::content(function (Content $content) {
            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(User::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('用户名');
            $grid->email('邮箱');
            $grid->password('密码');
            $grid->created_at('创建时间');
            $grid->updated_at('更新时间');

            $grid->paginate(10);

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(User::class, function (Form $form) {
            $form->display('id', 'ID');

            $form->text('name', trans('admin::lang.username'))->rules('required');
//            $form->text('name', trans('admin::lang.name'))->rules('required');
            $form->password('password', trans('admin::lang.password'))->rules('required');
            $form->text('email', '邮箱')->rules('required');
//            $form->multipleSelect('roles', trans('admin::lang.roles'))->options(Role::all()->pluck('name', 'id'));

            $form->display('created_at', trans('admin::lang.created_at'));
            $form->display('updated_at', trans('admin::lang.updated_at'));

            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
            });
        });
    }


}

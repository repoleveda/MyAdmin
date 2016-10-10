<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Zofe\Rapyd\Facades\DataSet;
//use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
//use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\AdminController;


use App\Http\Controllers\Controller;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Chart\Bar;
use Encore\Admin\Widgets\Chart\Doughnut;
use Encore\Admin\Widgets\Chart\Line;
use Encore\Admin\Widgets\Chart\Pie;
use Encore\Admin\Widgets\Chart\PolarArea;
use Encore\Admin\Widgets\Chart\Radar;
use Encore\Admin\Widgets\Collapse;
use Encore\Admin\Widgets\InfoBox;
use Encore\Admin\Widgets\Tab;
use Encore\Admin\Widgets\Table;

class DashboardController extends Controller
{

    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->grid());
        });
    }

    use AdminController;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index1()
    {
//        Admin::content(function (Content $content) {
//            $content->header(trans('admin::lang.administrator'));
//            $content->description(trans('admin::lang.list'));
//            $content->body($this->grid()->render());
//        });






        $users = User::all();

        $grid = \DataGrid::source(User::with('user'));  //same source types of DataSet

//        $grid->add('title','title', true); //field name, label, sortable
//        $grid->add('author.fullname','author'); //relation.fieldname
        $grid->add('name','name', true);
        $grid->add('email','email');
//        $grid->add('{{ substr($body,0,20) }}...','Body'); //blade syntax with main field
//        $grid->add('{{ $author->firstname }}','Author'); //blade syntax with related field
//        $grid->add('body|strip_tags|substr[0,20]','Body'); //filter (similar to twig syntax)
//        $grid->add('body','Body')->filter('strip_tags|substr[0,20]'); //another way to filter
        $grid->edit('/dashboard/edit', 'Edit','modify|delete'); //shortcut to link DataEdit actions

        //cell closure
        $grid->add('revision','Revision')->cell( function( $value, $row) {
            return ($value != '') ? "rev.{$value}" : "no revisions for art. {$row->id}";
        });

        //row closure
        $grid->row(function ($row) {
            if ($row->cell('public')->value < 1) {
                $row->cell('title')->style("color:Gray");
                $row->style("background-color:#CCFF66");
            }
        });

        $grid->link('/dashboard/edit',"Add New", "TR");  //add button
//        $grid->orderBy('article_id','desc'); //default orderby
        $grid->paginate(10); //pagination

        return view('admin.login.dashboard', compact('grid'));




        dd($users);
        dd('后台首页，当前用户名：'.auth('admin')->user()->name);
    }


    /**
     * Index interface.
     *
     * @return Content
     */
//    public function index()
//    {
//        return Admin::content(function (Content $content) {
//
//            $content->header('header');
//            $content->description('description');
//
//            $content->body($this->grid());
//        });
//    }

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
//            $grid->actions('edit|delete');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Admin::form(User::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->password('password', '密码')->rules('required');;
            $form->text('name', '用户名');
            $form->email('email', '邮箱');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }

    public function send()
    {
        $name = '学院君';
        $flag = Mail::send('emails.test',['name'=>$name],function($message){
            $to = 'xianbian1@qq.com';
            $message ->to($to)->subject('测试邮件');
        });
        if($flag){
            echo '发送邮件成功，请查收！';
        }else{
            echo '发送邮件失败，请重试！';
        }
    }
}
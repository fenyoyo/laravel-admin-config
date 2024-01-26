<?php

namespace Intop\Admin\Config\Http\Controllers;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Intop\Admin\Config\AppConfigServiceProvider;
use Intop\Admin\Config\Http\Actions\RefreshConfigAction;
use Intop\Admin\Config\Models\AppConfig;

class AppConfigController extends AdminController
{
    public $type = [
        'string' => '字符串',
        'number' => '数字',
        'rich' => '富文本',
        'text' => '文本',
        'image' => '图片',
        'file' => '文件',
        'list' => '列表',
    ];


    public function grid()
    {
        $group = AppConfigServiceProvider::setting('group');
        return Grid::make(new AppConfig(), function (Grid $grid) use ($group) {
            $grid->column('id')->sortable();
            $grid->column('name', '配置名称');
            $grid->column('key', '配置键');
            $grid->column('type', '配置类型')->using($this->type);
            $grid->column('group', '配置组')->using($group);
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
            });
            $grid->selector(function (Grid\Tools\Selector $selector) use ($group) {
                $selector->selectOne('group', '分组', $group);
            });
            $grid->tools(new RefreshConfigAction());
        });
    }

    public function form()
    {
        $group = AppConfigServiceProvider::setting('group');
        return Form::make(new AppConfig(), function (Form $form) use ($group) {
            $form->display('id');
            if ($form->isCreating()) {
                $form->text('name');
                $form->text('key');
                $form->select('type')->options($this->type);
                $form->select('group')->options($group);
            }
            if ($form->isEditing()) {
                $form->display('name');
                $form->display('key');
                $form->select('type')->options($this->type)->disable();
                $form->select('group')->options($group)->disable();
                switch ($form->model()->getAttribute('type')) {
                    case 'image':
                        $form->image('content')->autoUpload()->uniqueName();
                        break;
                    case 'rich':
                        $form->editor('content');
                        break;
                    case 'text':
                        $form->textarea('content');
                        break;
                    case 'list':
                        $form->list('content');
                        break;
                    default:
                        $form->text('content');
                }
            }
            $form->saved(function (Form $form) {
                //判断key是否重复
                $key = $form->input('key');
                $exists = AppConfig::query()->where('key', $key)->exists();
                if ($exists) {
                    $form->response()->error('配置键已存在');
                }
            });
            $form->saved(function (Form $form) {
                if ($form->isCreating()) {
                    return $form->response()->redirect(admin_route('app-config.edit', ['app_config' => $form->getKey()]));
                }
            });
        });
    }


}

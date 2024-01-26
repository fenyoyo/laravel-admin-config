<?php

namespace Intop\Admin\Config;

//use Illuminate\Support\ServiceProvider;
use Dcat\Admin\Extend\ServiceProvider;
use Intop\Admin\Config\Models\AppConfig;

class AppConfigServiceProvider extends ServiceProvider
{

    protected $menu = [
        [
            'title' => '系统配置',
            'uri' => 'app-config',
            'icon' => '', // 图标可以留空
        ],
    ];

    public function settingForm()
    {
        return new Setting($this);
    }

    public function init()
    {
        parent::init();
        if ($this->enabled()) {
            AppConfig::initConfig();
        }
    }


}

<?php

namespace Intop\Admin\Config;

use Dcat\Admin\Extend\Setting as Form;
use Dcat\Admin\Support\Helper;

class Setting extends Form
{

    public function title()
    {
        return '系统配置';
    }

    protected function formatInput(array $input)
    {
        $input['group'] = Helper::array($input['group']);
        return $input;
    }


    public function form()
    {
        $this->list('group', '配置组');
        $this->text('prefix', '配置前缀')->default('')->help('config($prefix.$key)');
    }
}
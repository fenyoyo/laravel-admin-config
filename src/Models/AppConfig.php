<?php

namespace Intop\Admin\Config\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Intop\Admin\Config\AppConfigServiceProvider;

/**
 * @property string|array $value
 */
class AppConfig extends Model
{
    public function value(): Attribute
    {
        $result = match ($this->getAttribute('type')) {
            'image' => $this->getAttribute('content') ? asset('/storage/' . $this->getAttribute('content')) : '',
            'list' => json_decode($this->getAttribute('content')) ?? [],
            default => $this->getAttribute('content'),
        };
        return new Attribute(get: fn() => $result);
    }

    public static function initConfig(): void
    {
        $configs = Cache::rememberForever('laravel-admin-config', function () {
            $arr = self::query()->select(['key', 'type', 'content'])->get()->append(['value'])->toArray();
            $prefix = AppConfigServiceProvider::setting('prefix', '');

            $configs = [];
            if ($prefix !== '') {
                $prefix = $prefix . '.';
            }
            foreach ($arr as $index => $item) {
                $configs[$prefix . $item['key']] = $item['value'];
            }
            return $configs;
        });
        config($configs);
    }

}

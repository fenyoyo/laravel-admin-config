<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppConfigTable extends Migration
{

    // 这里可以指定你的数据库连接
    public function getConnection()
    {
        return config('database.connection') ?: config('database.default');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = config('extensions.table', 'app_configs');
        Schema::connection($this->getConnection())->create($table, function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('配置名');
            $table->string('key')->unique()->comment('标识符');
            $table->string('type')->default('')->comment('配置类型');
            $table->string('group')->comment('组');
            $table->mediumText('content')->nullable()->comment('配置内容');
            $table->boolean('can_destroy')->default(1)->comment('是否可以删除');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table = config('extensions.table', 'app_configs');
        Schema::connection($this->getConnection())->dropIfExists($table);
    }
}

;

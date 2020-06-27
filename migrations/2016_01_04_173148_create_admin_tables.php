<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Jialeo\LaravelSchemaExtend\Schema;

class CreateAdminTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function getConnection()
    {
        return config('admin.database.connection') ?: config('database.default');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('admin.database.users_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 190)->comment('用户名')->unique();
            $table->string('password', 60)->comment('密码');
            $table->string('name')->comment('姓名');
            $table->string('avatar')->comment('头像')->nullable();
            $table->string('remember_token', 100)->comment('记住密码')->nullable();
            $table->timestamps();
	        $table->comment = '后台用户表';
        });

        Schema::create(config('admin.database.roles_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->comment('角色标题')->unique();
            $table->string('slug', 50)->comment('角色名')->unique();
            $table->timestamps();
	        $table->comment = '后台角色表';
        });

        Schema::create(config('admin.database.permissions_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->comment('权限标题')->unique();
            $table->string('slug', 50)->comment('权限名')->unique();
            $table->string('http_method')->comment('HTTP方法')->nullable();
            $table->text('http_path')->comment('HTTP路径')->nullable();
            $table->timestamps();
	        $table->comment = '后台权限表';
        });

        Schema::create(config('admin.database.menu_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->comment('上级菜单ID')->default(0);
            $table->integer('order')->comment('排序')->default(0);
            $table->string('title', 50)->comment('标题');
            $table->string('icon', 50)->comment('图标');
            $table->string('uri', 50)->comment('菜单地址')->nullable();
            $table->string('permission')->comment('权限控制')->nullable();

            $table->timestamps();
	        $table->comment = '后台菜单表';
        });

        Schema::create(config('admin.database.role_users_table'), function (Blueprint $table) {
            $table->integer('role_id')->comment('角色ID');
            $table->integer('user_id')->comment('用户ID');
            $table->index(['role_id', 'user_id']);
            $table->timestamps();
	        $table->comment = '后台角色用户关系表';
        });

        Schema::create(config('admin.database.role_permissions_table'), function (Blueprint $table) {
            $table->integer('role_id')->comment('角色ID');
            $table->integer('permission_id')->comment('权限ID');
            $table->index(['role_id', 'permission_id']);
            $table->timestamps();
	        $table->comment = '后台角色权限关系表';
        });

        Schema::create(config('admin.database.user_permissions_table'), function (Blueprint $table) {
            $table->integer('user_id')->comment('用户ID');
            $table->integer('permission_id')->comment('权限ID');
            $table->index(['user_id', 'permission_id']);
            $table->timestamps();
	        $table->comment = '后台用户权限关系表';
        });

        Schema::create(config('admin.database.role_menu_table'), function (Blueprint $table) {
            $table->integer('role_id')->comment('角色ID');
            $table->integer('menu_id')->comment('菜单ID');
            $table->index(['role_id', 'menu_id']);
            $table->timestamps();
	        $table->comment = '后台角色菜单关系表';
        });

        Schema::create(config('admin.database.operation_log_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户ID');
            $table->string('path')->comment('访问路径');
            $table->string('method', 10)->comment('访问方法');
            $table->string('ip')->comment('来源IP');
            $table->text('input')->comment('请求数据');
            $table->index('user_id');
            $table->timestamps();
	        $table->comment = '操作日志表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('admin.database.users_table'));
        Schema::dropIfExists(config('admin.database.roles_table'));
        Schema::dropIfExists(config('admin.database.permissions_table'));
        Schema::dropIfExists(config('admin.database.menu_table'));
        Schema::dropIfExists(config('admin.database.user_permissions_table'));
        Schema::dropIfExists(config('admin.database.role_users_table'));
        Schema::dropIfExists(config('admin.database.role_permissions_table'));
        Schema::dropIfExists(config('admin.database.role_menu_table'));
        Schema::dropIfExists(config('admin.database.operation_log_table'));
    }
}

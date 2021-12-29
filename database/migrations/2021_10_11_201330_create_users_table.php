<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users')) {

            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->smallInteger('role_id');
                $table->string('code', 50)->nullable();
                $table->string('name', 255);
                $table->string('slug', 255);
                $table->string('email', 190)->unique();
                $table->string('avatar', 255)->nullable();
                $table->string('password', 255)->nullable();
                $table->rememberToken();
                $table->timestamps();
                $table->timestamp('email_verified_at')->nullable();
                $table->timestamp('last_activity')->nullable();
                $table->tinyInteger('active')->default(0);
                $table->tinyInteger('is_deleted')->default(0);
                $table->string('register_ip', 50)->nullable();
                $table->tinyInteger('posts_contributor')->default(0);
                $table->tinyInteger('posts_auto_approve')->default(0);
            });
        } else {

            Schema::table('users', function (Blueprint $table) {

                if (!Schema::hasColumn('users', 'id'))
                    $table->id();

                if (!Schema::hasColumn('users', 'role_id'))
                    $table->smallInteger('role_id');

                if (!Schema::hasColumn('users', 'code'))
                    $table->string('code', 50)->nullable();

                if (!Schema::hasColumn('users', 'name'))
                    $table->string('name', 255);

                if (!Schema::hasColumn('users', 'slug'))
                    $table->string('slug', 255);

                if (!Schema::hasColumn('users', 'email'))
                    $table->string('email', 190)->unique();

                if (!Schema::hasColumn('users', 'avatar'))
                    $table->string('avatar', 255)->nullable();

                if (!Schema::hasColumn('users', 'password'))
                    $table->string('password', 255)->nullable();

                if (!Schema::hasColumn('users', 'id'))
                    $table->rememberToken();

                if (!Schema::hasColumn('users', 'id'))
                    $table->timestamps();

                if (!Schema::hasColumn('users', 'email_verified_at'))
                    $table->timestamp('email_verified_at')->nullable();

                if (!Schema::hasColumn('users', 'last_activity'))
                    $table->timestamp('last_activity')->nullable();

                if (!Schema::hasColumn('users', 'active'))
                    $table->tinyInteger('active');

                if (!Schema::hasColumn('users', 'is_deleted'))
                    $table->tinyInteger('is_deleted')->default(0);

                if (!Schema::hasColumn('users', 'register_ip'))
                    $table->string('register_ip', 50)->nullable();

                if (!Schema::hasColumn('users', 'posts_contributor'))
                    $table->tinyInteger('posts_contributor')->default(0);

                if (!Schema::hasColumn('users', 'posts_auto_approve'))
                    $table->tinyInteger('posts_auto_approve')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

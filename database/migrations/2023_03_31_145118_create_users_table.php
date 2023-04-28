<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('user_name');
            $table->string('user_pass');
            $table->integer('user_super');
            $table->integer('user_ad');
            $table->timestamps();
        });

        \DB::table('users')->insert([
            'user_name' => 'root',
            'user_pass' => \Hash::make('wo9384yjfrtw3978gnh89x04fng'),
            'user_super' => 1,
            'user_ad' => 0,
        ]);
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
};

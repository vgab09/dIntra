<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeavePoliciesTable extends Migration
{

    /**
     * Run the migrations.
     * @table leave_policies
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_policies', function (Blueprint $table) {
            $table->increments('id_leave_policy');
            $table->unsignedInteger('id_leave_type');
            $table->string('name', 191);
            $table->integer('days');
            $table->text('description')->nullable();
            $table->tinyInteger('active')->default('1');

            $table->index(["id_leave_type"], 'fk_leave_policies_leave_types1_idx');
            $table->nullableTimestamps();


            $table->foreign('id_leave_type', 'fk_leave_policies_leave_types1_idx')
                ->references('id_leave_type')->on('leave_types')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('leave_policies');
     }
}

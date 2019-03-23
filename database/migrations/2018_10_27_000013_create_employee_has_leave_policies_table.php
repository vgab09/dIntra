<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeHasLeavePoliciesTable extends Migration
{

    /**
     * Run the migrations.
     * @table employee_has_leave_policies
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_has_leave_policies', function (Blueprint $table) {
            $table->unsignedInteger('id_employee');
            $table->unsignedInteger('id_leave_policy');

            $table->primary(['id_employee','id_leave_policy']);
            $table->index(["id_leave_policy"], 'fk_employee_has_leave_policies_leave_policies1_idx');


            $table->foreign('id_leave_policy', 'fk_employee_has_leave_policies_leave_policies1_idx')
                ->references('id_leave_policy')->on('leave_policies')
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
       Schema::dropIfExists('employee_has_leave_policies');
     }
}

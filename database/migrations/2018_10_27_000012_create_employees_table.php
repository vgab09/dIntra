<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{

    /**
     * Run the migrations.
     * @table employees
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id_employee');
            $table->unsignedInteger('id_designation')->nullable();
            $table->unsignedInteger('id_department')->nullable();
            $table->unsignedInteger('id_employment_form');
            $table->date('hiring_date');
            $table->date('termination_date')->nullable();
            $table->string('name');
            $table->string('email', 127);
            $table->string('password');
            $table->date('date_of_birth');
            $table->unsignedInteger('reporting_to_id_employee')->nullable();
            $table->tinyInteger('active')->default('1');
            $table->string('remember_token', 100)->nullable();

            $table->index(["id_department"], 'fk_employees_departments1_idx');

            $table->index(["id_employment_form"], 'fk_employees_employment_forms1_idx');

            $table->index(["id_designation"], 'fk_employees_designations_idx');
            $table->softDeletes();
            $table->nullableTimestamps();


            $table->foreign('id_designation', 'fk_employees_designations_idx')
                ->references('id_designation')->on('designations')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('id_department', 'fk_employees_departments1_idx')
                ->references('id_department')->on('departments')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('id_employment_form', 'fk_employees_employment_forms1_idx')
                ->references('id_employment_form')->on('employment_forms')
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
       Schema::dropIfExists('employees');
     }
}

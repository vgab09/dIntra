<?php

use App\Persistence\Models\Department;
use App\Persistence\Models\Designation;
use App\Persistence\Models\Employee;
use App\Persistence\Models\EmploymentForm;
use App\Persistence\Models\Holiday;
use App\Persistence\Models\LeavePolicy;
use App\Persistence\Models\LeaveRequest;
use App\Persistence\Models\LeaveRequestHistory;
use App\Persistence\Models\LeaveType;
use App\Persistence\Models\WorkDay;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

class TestDataSeeder extends Seeder
{

    private const designationCount = 8;
    private const departmentCount = 5;
    private const leaveTypeCount = 2;
    private const employeeCount = 100;
    private const workDayCount = 4;

    /**
     * @var Department[]
     */
    private $departments;

    /**
     * @var Designation[]
     */
    private $designations;

    /**
     * @var EmploymentForm[]
     */
    private $employmentForms;

    /**
     * @var Employee[]
     */
    private $employees;

    /**
     * @var LeaveType[]
     */
    private $leaveTypes;


    /**
     * @var WorkDay[]
     */
    private $workDays;

    /**
     * @var Faker
     */
    private $faker;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $this->faker = $faker;

        $this->employmentForms = EmploymentForm::all();
        $this->createDesignations();
        $this->createDepartments();
        $this->createLeaveTypes();
        $this->createEmployees();
        $this->createWorkDays();
    }

    private function createDesignations(){
        $this->designations = factory(Designation::class,self::designationCount)->create();
    }

    private function createDepartments(){
        $this->departments = factory(Department::class,self::departmentCount)->create();
    }

    private function createLeaveTypes(){
        $this->leaveTypes = factory(LeaveType::class,self::leaveTypeCount)->create();
    }

    private function createEmployees(){
        $this->employees = factory(Employee::class,self::employeeCount)->make()->each(function (Employee $employee){
            $employee->designation()->associate($this->faker->randomElement($this->designations));
            $employee->department()->associate($this->faker->randomElement($this->departments));
            $employee->employmentForm()->associate($this->faker->randomElement($this->employmentForms));
            $employee->save();
        });
    }

    private function createWorkDays(){
        $this->workDays = factory(WorkDay::class,self::workDayCount)->create();
    }
}

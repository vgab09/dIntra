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
    private const leavePoliciesCount = 20;
    private const leaveRequestCount = 300;

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
     * @var LeavePolicy[]
     */
    private $leavePolicies;

    /**
     * @var LeaveRequest[]
     */
    private $leaveRequests;

    /**
     * @var Faker
     */
    private $faker;


    /**
     * Seed the application's database.
     *
     * @param Faker $faker
     * @return void
     */
    public function run(Faker $faker)
    {
        $this->faker = $faker;

        $this->employmentForms = EmploymentForm::all();
        $this->createDesignations();
        $this->createDepartments();
        $this->createLeaveTypes();
        $this->createLeavePolicies();
        $this->createEmployees();
        $this->createWorkDays();
        $this->createLeaveRequest();
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

    private function createLeavePolicies(){
        $this->leavePolicies = factory(LeavePolicy::class,self::leavePoliciesCount)->make()->each(function (LeavePolicy $leavePolicy){
            $leavePolicy->leaveType()->associate($this->faker->randomElement($this->leaveTypes));
            $leavePolicy->save();
        });
    }

    private function createEmployees(){
        $this->employees = factory(Employee::class,self::employeeCount)->make()->each(function (Employee $employee){
            $employee->designation()->associate($this->faker->randomElement($this->designations));
            $employee->department()->associate($this->faker->randomElement($this->departments));
            $employee->employmentForm()->associate($this->faker->randomElement($this->employmentForms));

            foreach ($this->faker->randomElements($this->leavePolicies,rand(1,4),false) as $leavePolicy){
                $employee->leavePolicies()->attach($leavePolicy->id_leave_policy);
            }

            $employee->save();
        });
    }

    private function createWorkDays(){
        $this->workDays = factory(WorkDay::class,self::workDayCount)->create();
    }

    private function createLeaveRequest(){
        $this->leaveRequests = factory(LeaveRequest::class,self::leaveRequestCount)->make()->each(function(LeaveRequest $leaveRequest){
            $leaveRequest->employee()->associate($this->faker->randomElement($this->employees));
            $leaveRequest->leavePolicy()->associate($this->faker->randomElement($this->leavePolicies));
            $leaveRequest->save();
        });
    }
}

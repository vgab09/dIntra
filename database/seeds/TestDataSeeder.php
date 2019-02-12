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
use App\Persistence\Models\Workday;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;

class TestDataSeeder extends Seeder
{

    private const DESIGNATION_COUNT = 8;
    private const DEPARTMENT_COUNT = 5;
    private const LEAVE_TYPE_COUNT = 2;
    private const EMPLOYEE_COUNT = 100;
    private const WORK_DAY_COUNT = 4;
    private const LEAVE_POLICY_COUNT = 20;
    private const LEAVE_REQUEST_COUNT = 300;

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
     * @var Workday[]
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
        $this->designations = factory(Designation::class,self::DESIGNATION_COUNT)->create();
    }

    private function createDepartments(){
        $this->departments = factory(Department::class,self::DEPARTMENT_COUNT)->create();
    }

    private function createLeaveTypes(){
        $this->leaveTypes = factory(LeaveType::class,self::LEAVE_TYPE_COUNT)->create();
    }

    private function createLeavePolicies(){
        $this->leavePolicies = factory(LeavePolicy::class,self::LEAVE_POLICY_COUNT)->make()->each(function (LeavePolicy $leavePolicy){
            $leavePolicy->leaveType()->associate($this->faker->randomElement($this->leaveTypes));
            $leavePolicy->save();
        });
    }

    private function createEmployees(){
        $roles = $this->getRoles();
        $this->employees = factory(Employee::class,self::EMPLOYEE_COUNT)->make()->each(function (Employee $employee) use ($roles){
            $employee->designation()->associate($this->faker->randomElement($this->designations));
            $employee->department()->associate($this->faker->randomElement($this->departments));
            $employee->employmentForm()->associate($this->faker->randomElement($this->employmentForms));

            foreach ($this->faker->randomElements($this->leavePolicies,rand(1,4),false) as $leavePolicy){
                $employee->leavePolicies()->attach($leavePolicy->id_leave_policy);
            }
            $employee->assignRole($this->faker->randomElement($roles));
            $employee->save();
        });
    }

    private function createWorkDays(){
        $this->workDays = factory(Workday::class,self::WORK_DAY_COUNT)->create();
    }

    private function createLeaveRequest(){
        $this->leaveRequests = factory(LeaveRequest::class,self::LEAVE_REQUEST_COUNT)->make()->each(function(LeaveRequest $leaveRequest){
            $leaveRequest->employee()->associate($this->faker->randomElement($this->employees));
            $leaveRequest->leavePolicy()->associate($this->faker->randomElement($this->leavePolicies));
            $leaveRequest->save();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Role[]
     */
    private function getRoles(){
        return Role::all();
    }
}

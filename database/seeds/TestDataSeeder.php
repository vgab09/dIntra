<?php

use App\Persistence\Models\Department;
use App\Persistence\Models\Designation;
use App\Persistence\Models\Employee;
use App\Persistence\Models\EmploymentForm;
use App\Persistence\Models\LeavePolicy;
use App\Persistence\Models\LeaveRequest;
use App\Persistence\Models\LeaveType;
use App\Persistence\Models\Workday;
use App\Persistence\Services\LeaveRequestService;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;

class TestDataSeeder extends Seeder
{

    private const DESIGNATION_COUNT = 8;
    private const DEPARTMENT_COUNT = 5;
    private const EMPLOYEE_COUNT = 100;
    private const WORK_DAY_COUNT = 4;
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
     * @var \Illuminate\Support\Collection
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
        $this->leaveTypes = LeaveType::all();
        $this->leavePolicies = LeavePolicy::all();
        $this->createDesignations();
        $this->createDepartments();
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

    private function createEmployees(){
        $roles = $this->getRoles();
        $this->employees = factory(Employee::class,self::EMPLOYEE_COUNT)->make()->each(function (Employee $employee) use ($roles){
            $employee->designation()->associate($this->faker->randomElement($this->designations));
            $employee->department()->associate($this->faker->randomElement($this->departments));
            $employee->employmentForm()->associate($this->faker->randomElement($this->employmentForms));
            $employee->assignRole($this->faker->randomElement($roles));
            $employee->save();


            $employee->leavePolicies()->attach($this->leavePolicies->where('name','=','Alapszabadság'));
            $employee->leavePolicies()->attach($this->leavePolicies->where('name','=','Alap betegszabadság'));

            foreach ($this->faker->randomElements($this->leavePolicies->whereNotIn('name',['Alapszabadság','Alap betegszabadság']),rand(1,4),false) as $leavePolicy){
                $employee->leavePolicies()->attach($leavePolicy->id_leave_policy);
            }


        });
    }

    private function createWorkDays(){
        $this->workDays = factory(Workday::class,self::WORK_DAY_COUNT)->create();
    }

    private function createLeaveRequest(){


        $this->leaveRequests = factory(LeaveRequest::class,self::LEAVE_REQUEST_COUNT)->make()->each(function(LeaveRequest $leaveRequest){

            /**
             * @var Employee $employee
             */
            $employee = $this->faker->randomElement($this->employees);
            $days = rand(1,13);
            $start_at = Carbon::instance($this->faker->dateTimeThisYear())->setTime(0,0);
            $end_at = (clone $start_at)->addDays($days);

            $status = $this->faker->randomElement([LeaveRequest::STATUS_ACCEPTED,LeaveRequest::STATUS_PENDING,LeaveRequest::STATUS_DENIED,LeaveRequest::STATUS_PENDING]);

            $service = new LeaveRequestService($leaveRequest);
            $service->setEmployee($employee);
            $service->setUser($employee);
            $service->setLeaveType($this->faker->randomElement($employee->getLeaveTypes()));
            $service->setDuration($start_at,$end_at);
            $service->create();

            switch ($status){
                case LeaveRequest::STATUS_ACCEPTED:
                    $service->accept();
                    break;
                case LeaveRequest::STATUS_DENIED:
                    $service->denny($this->faker->text(60));
                    break;
                default:
                    break;
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Role[]
     */
    private function getRoles(){
        return Role::all();
    }
}

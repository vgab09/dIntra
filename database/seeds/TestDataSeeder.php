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
    private const LEAVE_TYPE_COUNT = 0;
    private const EMPLOYEE_COUNT = 100;
    private const WORK_DAY_COUNT = 4;
    private const LEAVE_POLICY_COUNT = 0;
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
        $factory = factory(LeaveType::class);
        $this->leaveTypes[] = $factory->create(['name' => 'Szabadság']);
        $this->leaveTypes[] = $factory->create(['name' => 'Beteg szabadság']);
    }

    private function createLeavePolicies()
    {
        $factory = factory(LeavePolicy::class);
        $start_at = Carbon::createFromDate(null, 1, 1)->format('Y-m-d');
        $end_at = Carbon::createFromDate(null, 12, 31)->format('Y-m-d');
        $holiday_leaveType = $this->leaveTypes[0]->getKey();
        $ill_leaveType = $this->leaveTypes[1]->getKey();


        $leavePolicies[] = $factory->create(
            [
                'name' => 'Alapszabadság',
                'id_leave_type' => $holiday_leaveType,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'active' => 1,
                'days' => 20,
            ]
        );

        $leavePolicies[] = $factory->create(
            [
                'name' => 'Alap betegszabadság',
                'id_leave_type' => $ill_leaveType,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'active' => 1,
                'days' => 15,
            ]
        );

        $leavePolicies[] = $factory->create(
            [
                'name' => 'Életkor +1',
                'id_leave_type' => $holiday_leaveType,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'active' => 1,
                'days' => 1,
            ]
        );

        $leavePolicies[] = $factory->create(
            [
                'name' => 'Életkor +2',
                'id_leave_type' => $holiday_leaveType,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'active' => 1,
                'days' => 1,
            ]
        );

        $leavePolicies[] = $factory->create(
            [
                'name' => 'Életkor +3',
                'id_leave_type' => $holiday_leaveType,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'active' => 1,
                'days' => 3,
            ]
        );

        $leavePolicies[] = $factory->create(
            [
                'name' => 'Életkor +4',
                'id_leave_type' => $holiday_leaveType,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'active' => 1,
                'days' => 4,
            ]
        );

        $leavePolicies[] = $factory->create(
            [
                'name' => 'Életkor +5',
                'id_leave_type' => $holiday_leaveType,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'active' => 1,
                'days' => 5,
            ]
        );

        $leavePolicies[] = $factory->create(
            [
                'name' => 'Életkor +6',
                'id_leave_type' => $holiday_leaveType,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'active' => 1,
                'days' => 6,
            ]
        );

        $leavePolicies[] = $factory->create(
            [
                'name' => 'Életkor +7',
                'id_leave_type' => $holiday_leaveType,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'active' => 1,
                'days' => 7,
            ]
        );

        $leavePolicies[] = $factory->create(
            [
                'name' => 'Életkor +7',
                'id_leave_type' => $holiday_leaveType,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'active' => 1,
                'days' => 7,
            ]
        );

        $leavePolicies[] = $factory->create(
            [
                'name' => 'Életkor +8',
                'id_leave_type' => $holiday_leaveType,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'active' => 1,
                'days' => 8,
            ]
        );

        $leavePolicies[] = $factory->create(
            [
                'name' => 'Életkor +9',
                'id_leave_type' => $holiday_leaveType,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'active' => 1,
                'days' => 9,
            ]
        );

        $leavePolicies[] = $factory->create(
            [
                'name' => 'Gyermekek után +2',
                'id_leave_type' => $holiday_leaveType,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'active' => 1,
                'days' => 2,
            ]
        );

        $leavePolicies[] = $factory->create(
            [
                'name' => 'Gyermekek után +4',
                'id_leave_type' => $holiday_leaveType,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'active' => 1,
                'days' => 4,
            ]
        );

        $leavePolicies[] = $factory->create(
            [
                'name' => 'Gyermekek után +7',
                'id_leave_type' => $holiday_leaveType,
                'start_at' => $start_at,
                'end_at' => $end_at,
                'active' => 1,
                'days' => 7,
            ]
        );
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

<?php

use App\Persistence\Models\Department;
use App\Persistence\Models\Designation;
use App\Persistence\Models\Employee;
use App\Persistence\Models\EmploymentForm;
use App\Persistence\Models\Holiday;
use App\Persistence\Models\LeavePolicy;
use App\Persistence\Models\LeaveRequest;
use App\Persistence\Models\LeaveRequestHistory;
use App\Persistence\Models\WorkDay;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{

    /**
     * @var Role Administrator group
     */
    private $administratorRole;

    /**
     * @var Role Manager group
     */
    private $managerRole;

    /**
     * @var Role leader group
     */
    private $leaderRole;

    /**
     * @var Role employee group
     */
    private $employeeRole;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');
        $this->addRoles();
        $this->addPermissions();

        $this->addEmploymentForms();
        $this->addAdministrator();
        $this->addConstantHolidays();

    }

    /**
     * Create default CRUD permission given resource.
     * @param string $modelName
     *
     * @return array Created permissions
     */
    private function createCRUDPermissions($modelName)
    {
        $permissions = [];

        $permissions['create'] = Permission::create(['name' => sprintf('create %s', $modelName)]);
        $permissions['list'] = Permission::create(['name' => sprintf('list %s', $modelName)]);
        $permissions['update'] = Permission::create(['name' => sprintf('update %s', $modelName)]);
        $permissions['delete'] = Permission::create(['name' => sprintf('delete %s', $modelName)]);

        return $permissions;
    }

    /**
     * Assign permissions to multiple rules
     * @param Role ...$roles
     * @param array|mixed $permissions
     */
    private function assignPermissionsToRoles($permissions, ...$roles)
    {

        $permissions = collect($permissions);
        $roles = collect($roles);

        foreach ($roles as $role) {
            $role->givePermissionTo($permissions);
        }

    }

    /**
     * get the date in current year, and default format
     * @param int $month
     * @param int $day
     *
     * @return string
     */
    private function getDate(int $month, int $day)
    {
        return Carbon::createFromDate(null, $month, $day)->format('Y-m-d');
    }

    private function addEmploymentForms()
    {

        factory(EmploymentForm::class)->create(['name' => 'Teljes munkaidős']);
        factory(EmploymentForm::class)->create(['name' => 'Részmunkaidős']);
        factory(EmploymentForm::class)->create(['name' => 'Szerződéses']);
        factory(EmploymentForm::class)->create(['name' => 'Ideiglenes']);
        factory(EmploymentForm::class)->create(['name' => 'Gyakornok']);

    }

    private function addAdministrator()
    {

        $admin = factory(Employee::class)->create(['name' => 'Administrator', 'hiring_date' => Carbon::now(), 'termination_date' => null, 'email' => 'web@erppartner.hu', 'active' => 1]);
        $admin - assignRole($this->administratorRole);
    }

    private function addRoles()
    {
        $this->administratorRole = Role::create(['name' => 'Adminisztrátor']);
        $this->managerRole = Role::create(['name' => 'Manager']);
        $this->leaderRole = Role::create(['name' => 'Vezető']);
        $this->employeeRole = Role::create(['name' => 'Munkatárs']);
    }

    private function addPermissions()
    {
        $this->assignPermissionsToRoles($this->createCRUDPermissions(Role::class), $this->administratorRole);
        $this->assignPermissionsToRoles($this->createCRUDPermissions(Permission::class), $this->administratorRole);

        $this->assignPermissionsToRoles($this->createCRUDPermissions(EmploymentForm::class), $this->administratorRole, $this->managerRole);
        $this->assignPermissionsToRoles($this->createCRUDPermissions(Designation::class), $this->administratorRole, $this->managerRole);
        $this->assignPermissionsToRoles($this->createCRUDPermissions(Department::class), $this->administratorRole, $this->managerRole);
        $this->assignPermissionsToRoles($this->createCRUDPermissions(Employee::class), $this->administratorRole, $this->managerRole);
        $this->assignPermissionsToRoles($this->createCRUDPermissions(LeaveType::class), $this->administratorRole, $this->managerRole);
        $this->assignPermissionsToRoles($this->createCRUDPermissions(WorkDay::class), $this->administratorRole, $this->managerRole);
        $this->assignPermissionsToRoles($this->createCRUDPermissions(Holiday::class), $this->administratorRole, $this->managerRole);
        $this->assignPermissionsToRoles($this->createCRUDPermissions(LeavePolicy::class), $this->administratorRole, $this->managerRole);

        $this->assignPermissionsToRoles(Permission::create(['name' => sprintf('request %s', LeaveRequest::class)]), $this->administratorRole, $this->managerRole, $this->leaderRole, $this->employeeRole);
        $this->assignPermissionsToRoles(Permission::create(['name' => sprintf('withdraw %s', LeaveRequest::class)]), $this->administratorRole, $this->managerRole, $this->leaderRole, $this->employeeRole);

        $this->assignPermissionsToRoles(Permission::create(['name' => sprintf('accept %s', LeaveRequest::class)]), $this->administratorRole, $this->managerRole, $this->leaderRole);
        $this->assignPermissionsToRoles(Permission::create(['name' => sprintf('denny %s', LeaveRequest::class)]), $this->administratorRole, $this->managerRole, $this->leaderRole);

        $this->assignPermissionsToRoles(Permission::create(['name' => sprintf('list %s', LeaveRequestHistory::class)]), $this->administratorRole, $this->managerRole, $this->leaderRole);

    }

    private function addConstantHolidays()
    {

        factory(Holiday::class)->create(['name' => 'Újév', 'start' => $this->getDate(1, 1), 'end' => $this->getDate(1, 1), 'description' => 'B.U.É.K']);
        //factory(Holiday::class)->create(['name'=>'Húsvét','start'=>$this->getDate(3,30),'end'=>$this->getDate(4,2),'description'=>'']);
        factory(Holiday::class)->create(['name' => 'Munka ünnepe', 'start' => $this->getDate(5, 1), 'end' => $this->getDate(5, 1), 'description' => '']);
        //factory(Holiday::class)->create(['name'=>'Pünkösd','start'=>$this->getDate(5,21),'end'=>$this->getDate(5,21),'description'=>'']);
        factory(Holiday::class)->create(['name' => 'Államalapítás Ünnepe', 'start' => $this->getDate(8, 20), 'end' => $this->getDate(8, 20), 'description' => '']);
        factory(Holiday::class)->create(['name' => '56-os Forradalom Ünnepe', 'start' => $this->getDate(10, 23), 'end' => $this->getDate(10, 23), 'description' => '']);
        factory(Holiday::class)->create(['name' => 'Mindenszentek', 'start' => $this->getDate(11, 01), 'end' => $this->getDate(11, 01), 'description' => '']);
        factory(Holiday::class)->create(['name' => 'Szenteste', 'start' => $this->getDate(12, 24), 'end' => $this->getDate(12, 24), 'description' => '']);
        factory(Holiday::class)->create(['name' => 'Karácsony', 'start' => $this->getDate(12, 25), 'end' => $this->getDate(12, 26), 'description' => '']);
    }


}

<?php

use App\Persistence\Models\Employee;
use App\Persistence\Models\EmploymentForm;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->addRoles();
        $this->addPermissions();
        $this->addEmploymentForms();
        $this->addAdministrator();
        $this->addConstantHollidays();

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
        factory(Employee::class)->create(['name' => 'Administrator', 'hiring_date' => Carbon::now(), 'termination_date' => null, 'email' => 'web@erppartner.hu', 'active' => 1]);
    }

    private function addRoles()
    {
        $adminRole = Role::create(['name' => 'Adminisztrátor']);
        $managerRole = Role::create(['name' => 'Manager']);
        $leader = Role::create(['name' => 'Vezető']);
        $employeeRole = Role::create(['name' => 'Munkatárs']);
    }

    private function addPermissions()
    {

    }

    private function addConstantHollidays()
    {

    }


}

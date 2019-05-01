<?php
namespace App\Persistence\Models;


use App\Traits\ValidatableModel;
use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission implements ValidatableModelInterface
{
    use ValidatableModel;

}
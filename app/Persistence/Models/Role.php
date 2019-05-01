<?php
namespace App\Persistence\Models;


use App\Traits\ValidatableModel;
use Spatie\Permission\Models\Role as BaseRole;


class Role extends BaseRole implements ValidatableModelInterface
{
    use ValidatableModel;

}
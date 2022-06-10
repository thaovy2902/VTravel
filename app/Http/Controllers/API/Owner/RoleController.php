<?php

namespace App\Http\Controllers\API\Owner;

use App\Models\Role;
use App\Http\Resources\RoleCollection;
use App\Http\Controllers\API\BaseController;

class RoleController extends BaseController
{
  public function __invoke()
  {
    return new RoleCollection(Role::where('slug', '!=', 'owner')->get());
  }
}

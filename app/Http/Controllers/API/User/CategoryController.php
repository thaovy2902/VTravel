<?php

namespace App\Http\Controllers\API\User;

use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Controllers\API\BaseController;

class CategoryController extends BaseController
{
	public function __invoke()
	{
		return CategoryResource::collection(Category::all());
	}
}

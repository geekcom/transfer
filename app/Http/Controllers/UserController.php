<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\UserRepositoryContract;

class UserController extends Controller
{
    public function transfer(UserRepositoryContract $repositoryContract, Request $request)
    {
        return $repositoryContract->transfer($request);
    }

    public function show(UserRepositoryContract $repositoryContract, $id)
    {
        return $repositoryContract->show($id);
    }

    public function all(UserRepositoryContract $repositoryContract)
    {
        return $repositoryContract->all();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\UserRepositoryContract;

class UserController extends Controller
{
    public function transfer(UserRepositoryContract $repositoryContract, Request $request): object
    {
        return $repositoryContract->transfer($request);
    }

    public function show(UserRepositoryContract $repositoryContract, $id): ?object
    {
        return $repositoryContract->show($id);
    }

    public function all(UserRepositoryContract $repositoryContract): object
    {
        return $repositoryContract->all();
    }
}

<?php

namespace App\Repositories\Contracts;

interface UserRepositoryContract
{
    public function transfer(object $request): object;

    public function show(string $userId): ?object;

    public function all(): object;
}

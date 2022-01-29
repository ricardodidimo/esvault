<?php

namespace App\Services\User;

interface IUserService
{
    public function store(array $entityData): void;
    public function update(array $entityUpdateData): void;
    public function destroy(): void;
}

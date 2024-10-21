<?php

namespace App\Repositories;

use App\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    public function findByName(string $name)
    {
        return Role::where('name', $name)->firstOrFail();
    }

    public function getAll()
    {
        return Role::all();
    }

    public function store(array $data)
    {
        return Role::create($data);
    }

    public function update(array $data, $id)
    {
        $role = Role::findOrFail($id);
        $role->update($data);
        return $role;
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);
        return $role->delete();
    }
}
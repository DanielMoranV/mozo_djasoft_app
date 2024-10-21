<?php

namespace App\Interfaces;

interface RoleRepositoryInterface
{
    public function findByName(string $name);
    public function getAll();
    public function store(array $data);
    public function update(array $data, $id);
    public function delete($id);
}
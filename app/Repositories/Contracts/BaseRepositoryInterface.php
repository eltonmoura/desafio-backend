<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryInterface
{
    public function find($id);

    public function findAll();
  
    public function create(array $data);

    public function update(array $data, $id);
    
    public function delete($id);
}

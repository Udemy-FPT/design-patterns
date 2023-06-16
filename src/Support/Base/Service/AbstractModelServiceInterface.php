<?php
namespace Hirodev\BaseDesignPatterns\Support\Base\Service;

interface AbstractModelServiceInterface
{
    public function getList();
    public function getAll();
    public function getAllPaging();
    public function show($id);
    public function store($attributes = []);
    public function update($model, $attributes = []);
    public function destroy($id);
    public function destroyMultiple($ids);
}

<?php
namespace Hirodev\BaseDesignPatterns\Support\Service;
Abstract class AbstractModelService implements AbstractModelServiceInterface
{
    abstract public function getList();
    abstract public function getAll();
    abstract public function getAllPaging();
    abstract public function show($id);
    abstract public function store($attributes = []);
    abstract public function update($model, $attributes = []);
    abstract public function destroy($id);
    abstract public function destroyMultiple($ids);

}

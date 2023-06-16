<?php
namespace Hirodev\BaseDesignPatterns\Support\Base\Repository;


interface AbstractModelRepositoryInterface
{
    public function getList();

    public function getAll();

    public function getAllPaging();

    public function getWhere($field, $value);

    public function getWherePaging($field, $value);

    public function findById($id);

    public function findByIdWithTrashed($id);

    public function findWhere($field, $value);

    public function create($attributes = []);

    public function insert($attributes = []);

    public function update($model, $attributes = []);

    public function delete($model);

    public function deleteMany($field);

    public function getSearchAll($limit = 10);

    public function getFields($fields = []);

    public function getDataTableWhere($conditions);

    public function getAllPagingWithSearch();

    public function getAllPagingWithSearchWhere($conditions);

    public function getSelectPagingWithSearchWhere($conditions, $select = '*');

    public function getSelectByIds($ids, $select = '*');

    public function updateOrCreate($filters, $attributes);
}

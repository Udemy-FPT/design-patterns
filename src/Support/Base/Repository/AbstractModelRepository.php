<?php
namespace Hirodev\BaseDesignPatterns\Support\Base\Repository;

abstract class AbstractModelRepository implements AbstractModelRepositoryInterface
{
    protected $model;

    protected $perPage = 20;

    public function getDataTable()
    {
        $data = $this->model
            ->withSearch()
            ->withSort();
        $total = $data->count();
        $data = $data->withPaging()->get();

        return [
            'total' => $total,
            'rows' => $data
        ];
    }

    public function getDataTable2()
    {
        $data = $this->model
            ->withSearch()
            ->withSort()->get();
        return $data;
    }

    public function getDataTableCountType($type)
    {
        $data = $this->model
            ->withCountSearch($type)
            ->withSort()->count();
        return $data;
    }

    public function getList()
    {
        $request = \App\Repositories\Base\request();
        if ($request->has('limit')) {
            $this->perPage = $request->limit;
            return $this->getAllPaging();
        }

        return $this->getAll();

    }

    public function getAll()
    {
        return $this->model->get();
    }

    public function getAllPaging()
    {
        return $this->model->latest()->paginate($this->perPage);
    }

    public function getWhere($field, $value)
    {
        return $this->model->where($field, $value)->get();
    }

    public function getFields($fields = [])
    {
        return $this->model->where($fields)->get();
    }

    public function getWherePaging($field, $value)
    {
        return $this->model->where($field, $value)->paginate($this->perPage);
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findByIdWithTrashed($id)
    {
        return $this->model->withTrashed()->findOrFail($id);
    }

    public function findWhereOrFail($field, $value)
    {
        return $this->model->where($field, $value)->firstOrFail();
    }

    public function findWhere($field, $value)
    {
        return $this->model->where($field, $value)->first();
    }

    public function create($attributes = [])
    {
        return $this->model->create($attributes);
    }

    public function insert($attributes = [])
    {
        return $this->model->insert($attributes);
    }

    public function update($model, $attributes = [])
    {
        $model->fill($attributes);
        $model->save();

        return $model;
    }

    public function delete($model)
    {
        return $model->delete();
    }

    public function deleteMany($field)
    {
        return $this->model->where($field)->delete();
    }

    public function getSearchAll($limit = 10)
    {

        if ($limit > 0) {
            return $this->model->withsearch()->limit($limit)->get();
        }
        return $this->model->withsearch()->get();

    }

    public function firstOrCreate($condition = [], $additionInsertField = [])
    {
        return $this->model->firstOrCreate($condition, $additionInsertField);
    }

    public function getFilterKey($key)
    {
        $objects = $this->model->get();
        $arr = [];
        foreach ($objects as $object) {
            $arr[$object->{$key}] = $object->{$key};
        }
        return $arr;
    }

    public function getDataTableWhere($conditions)
    {
        $data = $this->model
            ->where($conditions)
            ->withSearch()
            ->withSort();
        $total = $data->count();
        $data = $data->withPaging()->get();

        return [
            'total' => $total,
            'rows' => $data
        ];
    }

    public function getAllPagingWithSearch()
    {
        $request = \App\Repositories\Base\request();
        $perPageNum = $request->has('perPageNum') ? $request->perPageNum : '15';
        $data = $this->model
            ->withSearch()
            ->withSort();
        return $data->paginate($perPageNum);
    }

    public function getAllPagingWithSearchWhere($conditions)
    {
        $request = \App\Repositories\Base\request();
        $perPageNum = $request->has('perPageNum') ? $request->perPageNum : '15';
        $data = $this->model
            ->where($conditions)
            ->withSearch()
            ->withSort();
        return $data->paginate($perPageNum);
    }

    public function getSelectPagingWithSearchWhere($conditions, $select = '*')
    {
        $request = \App\Repositories\Base\request();
        $perPageNum = $request->has('perPageNum') ? $request->perPageNum : '15';
        $data = $this->model
            ->select($select)
            ->where($conditions)
            ->withSearch()
            ->withSort();
        return $data->paginate($perPageNum);
    }

    public function getSelectByIds($ids, $select = '*')
    {
        return $this->model->select($select)
            ->whereIn('_id', $ids)
            ->withSort()
            ->get();
    }

    public function updateOrCreate($filters, $attributes){
        return $this->model->updateOrCreate($filters, $attributes);
    }
}

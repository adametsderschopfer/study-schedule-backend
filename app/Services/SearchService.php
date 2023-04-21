<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class SearchService
{
    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function search(array $input)
    {
        $search = strip_tags(stripslashes(trim($input['search'])));
        $field = $this->model->getSeachField();

        $searchValues = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

        return $this->model->where(function ($q) use ($searchValues, $field) {
            foreach ($searchValues as $value) {
              $q->orWhere($field, "like", "%{$value}%");
            }
        })
        ->where('account_id', $input['account_id'])
        ->orderBy($field, 'ASC')
        ->get();
    }
}
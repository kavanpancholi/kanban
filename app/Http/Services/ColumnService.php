<?php

namespace App\Http\Services;

use App\Http\Resources\ColumnResource;
use App\Models\Column;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ColumnService
{
    /**
     * @return AnonymousResourceCollection
     */
    public function list(): AnonymousResourceCollection
    {
        $columns = Column::all();

        return ColumnResource::collection($columns);
    }

    /**
     * @param $data
     * @return ColumnResource
     */
    public function create($data): ColumnResource
    {
        $column = new Column();
        $column->fill($data);
        $column->save();

        return ColumnResource::make($column);
    }

    /**
     * @param Column $column
     * @param $data
     * @return ColumnResource
     */
    public function update(Column $column, $data): ColumnResource
    {
        $column->title = $data['title'];
        $column->save();

        return ColumnResource::make($column);
    }

    /**
     * @param Column $column
     * @return true
     */
    public function delete(Column $column): bool
    {
        $column->delete();

        return true;
    }
}

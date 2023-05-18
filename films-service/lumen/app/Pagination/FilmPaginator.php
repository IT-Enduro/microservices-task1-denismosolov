<?php

namespace App\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;

class FilmPaginator extends LengthAwarePaginator
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'page' => $this->currentPage(),
            'pageSize' => $this->perPage(),
            'totalElements' => $this->total(),
            'items' => $this->items->toArray(),
        ];
    }
}

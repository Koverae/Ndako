<?php

namespace Modules\App\Traits\Table;

use Illuminate\Database\Eloquent\Builder;

trait HasSubData{
    
    public abstract function subQuery(int $propertyId): Builder;

    public function subData(int $propertyId)
    {
        return $this
            ->subQuery($propertyId)->isCompany(current_company()->id)
            ->when($this->sortBy !== '', function ($query) {
                $query->orderBy($this->sortBy, $this->sortDirection);
            })
            ->paginate($this->perPage);
    }
    public abstract function subColumns() : array;
}
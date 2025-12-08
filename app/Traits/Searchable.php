<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    public function scopeSearch(Builder $query, string $search = null, array $columns = [])
    {
        if (empty($search) || empty($columns)) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($search, $columns) {
            foreach ($columns as $column) {
                if (str_contains($column, '.')) {
                    // Handle relationship columns
                    [$relation, $relationColumn] = explode('.', $column);
                    $query->orWhereHas($relation, function (Builder $query) use ($relationColumn, $search) {
                        $query->where($relationColumn, 'LIKE', "%{$search}%");
                    });
                } else {
                    // Handle regular columns
                    $query->orWhere($column, 'LIKE', "%{$search}%");
                }
            }
        });
    }

    public function scopeAdvancedSearch(Builder $query, array $filters)
    {
        foreach ($filters as $column => $value) {
            if (empty($value)) {
                continue;
            }

            if (str_contains($column, '.')) {
                // Handle relationship filters
                [$relation, $relationColumn] = explode('.', $column);
                $query->whereHas($relation, function (Builder $query) use ($relationColumn, $value) {
                    if (is_array($value)) {
                        $query->whereIn($relationColumn, $value);
                    } else {
                        $query->where($relationColumn, $value);
                    }
                });
            } else {
                // Handle regular column filters
                if (is_array($value)) {
                    $query->whereIn($column, $value);
                } else {
                    $query->where($column, $value);
                }
            }
        }

        return $query;
    }
}

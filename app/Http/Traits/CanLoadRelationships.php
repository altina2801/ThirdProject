<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

trait CanLoadRelationships
{
    protected function loadRelationships(
        Model|QueryBuilder|EloquentBuilder|HasMany $for,
        array $relations = null
    ): Model|QueryBuilder|EloquentBuilder|HasMany {
        $relations = $relations ?? $this->relations ?? [];

        foreach ($relations as $relation) {
            if ($this->shouldIncludeRelation($relation)) {
                if ($for instanceof Model) {
                    $for->load($relation);
                } else {
                    $for->with($relation);
                }
            }
        }

        return $for;
    }

    protected function shouldIncludeRelation(string $relation): bool
    {
        $include = request()->query('include'); // e.g. ?include=user,attendees.user

        if (!$include) {
            return false;
        }

        $requested = array_map('trim', explode(',', $include));

        return in_array($relation, $requested);
    }

    protected function resolveRelations(array $available): array
    {
        $include = request()->query('include'); // e.g. ?include=user,attendees.user

        if (!$include) {
            return [];
        }

        $requested = array_map('trim', explode(',', $include));

        return array_values(array_intersect($available, $requested));
    }
}

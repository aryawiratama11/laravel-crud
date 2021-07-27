<?php

namespace Wailan\Crud;

use Illuminate\Database\Eloquent\Collection;

class CustomCollection
{
    // Param array
    // Return Collection
    public static function collect($array)
    {
        $collection = new Collection();
        for ($i = 0; $i < count($array); $i++) {
            $collection->push((object)$array[$i]);
        }

        return $collection;
    }
}

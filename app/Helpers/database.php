<?php

use Illuminate\Support\Facades\DB;

/**
 * Builder'i sql sorgusuna çevirir
 * @param \Illuminate\Database\Eloquent\Builder $builder
 * @param bool $die
 * @return string
 */
function builder_to_sql(\Illuminate\Database\Eloquent\Builder $builder, bool $die = false) : string{
    $query = str_replace(array('?'), array('\'%s\''), $builder->toSql());
    $query = vsprintf($query, $builder->getBindings());
    if ($die) {
        echo $query;
        die();
    }
    return $query;
}

/**
 * Veritabanı sorgusunun sonucunu düzenler
 * @param \Illuminate\Database\Eloquent\Builder|Illuminate\Database\Query\Builder $builder
 * @param null|int|string $id
 * @param string $areas
 * @return array
 */
function builder_return_data(\Illuminate\Database\Eloquent\Builder|Illuminate\Database\Query\Builder $builder, null|int|string $id = null, string $areas = '*') : array
{
    if ($id === null) {
        if (get_class($builder) === 'Illuminate\Database\Query\Builder') {
            return $builder->get(DB::raw($areas))->toArray();
        } else {
            return $builder->get(DB::raw($areas))->toArray();
        }
    } else {
        $return = $builder->first(DB::raw($areas));
        if ($return !== null) {
            if (get_class($builder) === 'Illuminate\Database\Query\Builder') {
                return $return;
            } else {
                return $return->toArray();
            }
        }
    }
    return [];
}

/**
 * Veritabanı sonucu sorgusunun onayını verir
 * @param mixed $return
 * @return bool
 */
function builder_return_data_bool(mixed $return) : bool {
    return !empty($return);
}

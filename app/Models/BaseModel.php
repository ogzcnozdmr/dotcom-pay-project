<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class BaseModel extends Model
{
    public bool $autoVisible = true;

    use HasFactory;
    /**
     * Veriyi getirir
     * @param int|null $id
     * @param string $areas
     * @param array $where
     * @param array $orderBy
     * @return array
     */
    public function __data(int|null $id, string $areas = '*', array $where = [], array $orderBy = []) : array
    {
        if ($this->autoVisible) {
            $where[$this->getTable().'_visible'] = '1';
        }
        if ($id !== null) {
            $where[$this->getTable().'_id'] = $id;
        }
        $builder = self::where($where);
        /*
         * Sıralama varsa
         */
        if (!empty($orderBy)) {
            foreach ($orderBy as $key => $value) {
                $builder = $builder->orderBy($key, $value);
            }
        }
        return builder_return_data($builder, $id, $areas);
    }
    /**
     * Adedini getirir
     * @param int|null $id
     * @param array $where
     * @return int
     */
    public function __count(int|null $id, array $where = []) : int
    {
        if ($this->autoVisible) {
            $where[$this->getTable().'_visible'] = '1';
        }
        if ($id !== null) {
            $where[$this->getTable().'_id'] = $id;
        }
        return self::where($where)->count();
    }
    /**
     * Veriyi ekler
     * @param array $array
     * @return bool
     */
    public function __create(array $array = []) : bool
    {
        $builder = self::create($array);
        return builder_return_data_bool($builder);
    }

    /**
     * Veriyi günceller
     * @param int $id
     * @param array $array
     * @param int $limit
     * @return bool
     */
    public function __update(int|null $id = null, array $array = [], int $limit = 0) : bool
    {
        $where = [];
        if ($id !== null) {
            $where[$this->getTable().'_id'] = $id;
        }
        $builder = self::where($where)->update($array);
        if ($limit !== 0) {
            $builder = $builder->limit($limit);
        }
        return builder_return_data_bool($builder);
    }
}

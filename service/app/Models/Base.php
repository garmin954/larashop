<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Base extends Model
{

    public function scopeLike($query, $field, $name)
    {
        return $query->where($field, 'like', "%{$name}%");
    }

    /**
     * 分页
     * @param $query
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function scopePage($query, $page, $limit)
    {
        $offset = ($page - 1) * $limit;
        return $query->offset($offset)->limit($limit)->get();
    }

    /**
     * 字段增减或插入
     * @param $query
     * @param $where
     * @param $data
     * @param bool $isIncrement
     * @return mixed
     */
    public function scopeModifyOrInsert($query, $where,$data,$isIncrement=true)
    {
        if ($query->where($where)->exists()){ // 更新

            $key = current(array_keys($data));
            $value = current($data);

            if ($isIncrement){ // 增加
                return  $query->where($where)->increment($key, $value);

            }

            // 减少
            return  $query->where($where)->decrement($key, $value);
        }

        // 新增
        return $query->insert(array_merge($where, $data));
    }

}

<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Base extends Model
{

    public function scopeLike($query, $field, $name)
    {
        return $query->where($field, 'like', "%{$name}%");
    }

    public function scopePage($query, $page, $limit)
    {
        $offset = ($page - 1) * $limit;
        return $query->offset($offset)->limit($limit)->get();
    }

}

<?php

namespace iLaravel\Acl\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'model',
        'method',
    ];

    public function getNameAttribute()
    {
        return $this->model . '@' . $this->method;
    }
}

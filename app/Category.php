<?php

namespace MKTests;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public function exams()
    {
        return $this->hasMany('MKTests\Exam', 'category_id');
    }
}

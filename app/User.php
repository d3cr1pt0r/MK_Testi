<?php
namespace MKTests;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'admin'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function categories()
    {
        return $this->hasMany('MKTests\Category', 'user_id');
    }

    public function results()
    {
        return $this->hasMany('MKTests\Result', 'user_id');
    }

    public function totalGeneratedCodes()
    {
        return count($this->results);
    }

    public function totalGeneratedCodesByCategory()
    {
        $map = [];
        foreach($this->results as $result) {
            $category_title = $result->exam->category->title;
            $num_exams = count($result->exam->category->exams);
            if (!array_key_exists($category_title, $map)) {
                $map[$category_title] = ['num_codes' => 0, 'num_exams' => $num_exams];
            }
            $map[$category_title]['num_codes']++;
        }

        return $map;

    }

}


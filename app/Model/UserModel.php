<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table="user";
    protected $primaryKey="uid";
    public $timestamps=false;

    private $salt;

    public function setsalt()
    {
        $this->salt = env('APISALT');
        return $this;
    }

    /**
     * 生成token
     * @param $uid
     * @param $username
     * @return string
     */
    public function createtoken($uid,$username)
    {
        $array = [
            'uid'=>$uid,
            'username'=>$username,
            'create_time'=>time()+7200,
            'salt'=>$this->salt
        ];
        $str = serialize($array);
        $token = encrypt($str);
        return $token;
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserConnection extends Model
{
    use HasFactory;

    CONST CONNECTED = 1;
    CONST REQUESTED = 2;

    protected $table = 'user_connections';

    protected $fillable = ['sender', 'receiver', 'status'];

    public function sentRequestsUser()
    {
        return $this->hasOne('App\Models\User','id','receiver')->select('id','email','name');
    }

    public function receivedRequestsUser()
    {
        return $this->hasOne('App\Models\User','id','sender')->select('id','email','name');
    }


}

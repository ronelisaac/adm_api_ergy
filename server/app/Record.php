<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Record extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ["name","record_id"];
    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('write', 'read');
    }
    public function approvals()
    {
        return $this->belongsToMany('App\User', 'approvals');
    }
}

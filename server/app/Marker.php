<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Marker extends Model
{
  use SoftDeletes;
  public function records()
  {
    return $this->belongsToMany('App\Record');
  }
}

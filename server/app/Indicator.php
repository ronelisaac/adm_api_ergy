<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Indicator extends Model
{
  use SoftDeletes;
  public function form()
  {
    return $this->belongsTo('App\Form');
  }
}

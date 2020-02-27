<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class TimeSerie extends Model
{
  use SoftDeletes;
  public function indicator()
  {
    return $this->belongsTo('App\Indicator');
  }
}

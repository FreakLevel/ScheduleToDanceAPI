<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
  protected $table = 'appointment';

  protected $dateFormat = 'Y-m-d H:i';

  protected $fillable = ['email', 'name', 'schedule'];
}

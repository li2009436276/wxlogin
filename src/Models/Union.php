<?php

namespace Wxlogin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Union extends Model
{
    use SoftDeletes;
    protected $table = 'union';
    protected $guarded = [];
}
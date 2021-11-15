<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShortLinks extends Model
{
    //
    protected $table = 'short_links';

    public function Logs() {
        return $this->hasMany(Logs::class,'short_link_id','id');
    }
}

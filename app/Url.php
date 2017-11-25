<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    //
    protected $table = 'urls';
    protected $fillable = ['uri', 'original'];

    public function Logs() {
        return $this->hasMany(Logs::class,'url_id','id');
    }

    protected $appends = ['source', 'created'];

    public function getSourceAttribute()
    {
        if (isset($this->attributes['original']) && $this->attributes['original'] !== '') {
            $arrItem = parse_url($this->attributes['original']);
            $res = $arrItem['host'];
            if (isset($arrItem['port'])) {
                $res .= $arrItem['port'];
            }
            return $res . $arrItem['path'];
        }
        return '';
    }

    public function getCreatedAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    //
    protected $table = 'urls';
    protected $fillable = ['uri', 'original', 'title', 'description', 'image'];

    public function ShortLinks() {
        return $this->hasMany(ShortLinks::class,'url_id','id');
    }

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
            if (isset($arrItem['path'])) {
                $res .= $arrItem['path'];
            }
            return $res;
        }
        return '';
    }

    public function getCreatedAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}

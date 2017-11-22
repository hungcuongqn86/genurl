<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    //
    protected $table = 'urls';

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
        if (isset($this->attributes['created_at']) && $this->attributes['created_at'] !== '') {

            $datetime1 = new \DateTime($this->attributes['created_at']);
            $datetime2 = new \DateTime();
            $interval = $datetime1->diff($datetime2);
            if($interval->y){
                return $interval->y.' years ago';
            }
            if($interval->m){
                return $interval->m.' months ago';
            }

            if($interval->m){
                return $interval->m.' days ago';
            }

            if($interval->h){
                return $interval->h.' hours ago';
            }

            if($interval->i){
                return $interval->i.' minutes ago';
            }

            if($interval->s){
                return 'in 1 minute';
            }
        }
        return 'in 1 minute';
    }
}

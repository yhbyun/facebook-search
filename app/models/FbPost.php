<?php

class FbPost extends Eloquent {

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('FbUser', 'id', 'from');
    }

    public function likes()
    {
        return $this->belongsToMany('FbUser', 'fb_likes');
    }

    public function comments()
    {
        return $this->hasMany('FbComment');;
    }
}

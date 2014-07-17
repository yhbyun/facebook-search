<?php

class FbUser extends Eloquent {

    public $incrementing = false;

    public function posts()
    {
        return $this->hasMany('FbPost', 'from', 'id');
    }

    public function likes()
    {
        return $this->belongsToMany('FbPost', 'fb_likes');
    }

    public function comments()
    {
        return $this->hasMany('FbComment');
    }
}

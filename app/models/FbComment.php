<?php

class FbComment extends Eloquent {

    public $incrementing = false;

    public function posts() {
        return $this->belongsTo('FbPost');
    }

    public function users() {
        return $this->belongsTo('FbUser');
    }
}

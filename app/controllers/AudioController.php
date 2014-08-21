<?php

class AudioController extends BaseController
{
    protected $layout = null;

    public function getDemo1()
    {
        return View::make('facebook.audio.demo1');
    }
}

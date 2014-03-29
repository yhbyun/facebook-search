<?php


class MyLog {

    //debug, info, notice, warning, error, critical, and alert.

    public static function alert($message) {
        Log::alert($message);
    }

    public static function critical($message) {
        Log::critical($message);
    }

    public static function error($message) {
        Log::error($message);
    }

    public static function warning($message) {
        Log::warning($message);
    }

    public static function notice($message) {
        Log::notice($message);
    }

    public static function info($message) {
        Log::info($message);
    }

    public static function debug($message) {
        Log::debug($message);
    }

}
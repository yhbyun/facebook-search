<?php

class MyString {

    /**
     * Convert line breaks into paragraphs
     *
     * @param $str
     * @return mixed|string
     */
    public static function paragraph($str) {
        $str = static::normalize($str);
        $str = preg_replace('/\n(\s*\n)+/', '</p><p>', $str);
        $str = preg_replace('/\n/', '<br>', $str);
        $str = '<p>'.$str.'</p>';
        return $str;
    }


    /**
     * Normalize line endings Convert all line-endings to UNIX format
     *
     * @param $str
     * @return mixed
     */
    public static function normalize($str) {
        $str = str_replace("\r\n", "\n", $str);
        $str = str_replace("\r", "\n", $str);
        // Don't allow out-of-control blank lines
        $str = preg_replace("/\n{2,}/", "\n\n", $str);
        return $str;
    }

    /**
     * 현재 시간 기준으로 상대적인 시간 차이를 반환
     *
     * @param $time UNIX timestamp
     * @return string
     */
    public static function prettyDate($time) {
        $diff = time() - $time;
        $day_diff = floor($diff / 86400);

        if (is_nan($day_diff)) return '';

        if ($day_diff == 0) {
            if ($diff < 60) {
                return $diff . "second ago";
            } else if ($diff < 120) {
                return '1 min ago';
            } else if ($diff < 3600) {
                return floor( $diff / 60 ) . " min ago";
            } else if ($diff < 7200) {
                return '1 hour ago';
            } else if ($diff < 86400) {
                return floor( $diff / 3600 ) . " hour ago";
            }
        } else if ($day_diff == 1) {
            return 'yesterday';
        } else if ($day_diff < 7) {
            return $day_diff . " day ago";
        } else if ($day_diff < 31) {
            return ceil( $day_diff / 7 ) . " week ago";
        } else {
            return '';
        }
    }
} 
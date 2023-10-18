<?php

namespace app\libraries;

class dateFormatter
{
    public function __construct()
    {
    }
    public function strtotime($dateString)
    {
        return strtotime($dateString);
    }
    public function diff($date1, $date2)
    {
        // sec
        return $this->strtotime($date1) - $this->strtotime($date2);
    }
    public function secToMin($secs)
    {
        return  ceil($secs / 60);
    }
}

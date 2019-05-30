<?php

/**
 * Created by IntelliJ IDEA.
 * User: Andreas
 * Date: 17/11/2017
 * Time: 10:25.
 */

namespace Controller;

class textParser
{
    public $textKecil;
    public $textBintang;
    public $textSpasi;

    /**
     * textParser constructor.
     *
     * @param $text
     */
    public function __construct($text)
    {
        $this->textKecil = strtolower($text);
        $this->textBintang = explode('*', $text);
        $this->textSpasi = explode(' ', $text);
    }
}

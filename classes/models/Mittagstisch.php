<?php

/**
 * Created by PhpStorm.
 * User: David
 * Date: 01.08.2016
 * Time: 11:47
 */
class Mittagstisch extends Model
{
    private $geschaeft;
    private $kalenderwoche;
    private $entries;

    public function __construct($geschaeft)
    {
        parent::__construct();
        $this->geschaeft = $geschaeft;
    }

    /**
     * @return mixed
     */
    public function getGeschaeft()
    {
        return $this->geschaeft;
    }
}
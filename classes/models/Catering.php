<?php

/**
 * Created by PhpStorm.
 * User: David
 * Date: 01.08.2016
 * Time: 11:52
 */
class Catering extends Model
{
    private $type;
    private $entries;

    public function __construct($type)
    {
        parent::__construct();
        $this->type = $type;
    }
}
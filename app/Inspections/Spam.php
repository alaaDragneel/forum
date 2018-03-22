<?php

namespace App\Inspections;

interface Spam
{

    /**
     * @param $body
     * @throws \Exception
     */
    public function detect ($body);

}
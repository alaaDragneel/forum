<?php

namespace App\Inspections;

use Exception;

class KeyHeldDown implements Spam
{

    /**
     * @param $body
     * @throws Exception
     */
    public function detect ($body)
    {

        /*
        * . => all characters
        * (.) => Match All the Characters
        * \1 => from the first character
        * {4,} => check for the same character For 4 Or more Same Characters
        */

        if ( preg_match('/(.)\\1{4,}/', $body) ) {
            throw new Exception('Your Reply Contains Spam');
        }
    }
}
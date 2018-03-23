<?php

namespace App\Inspections;

use Exception;

class InvalidKeywords implements Spam
{

    protected $keywords = [
        'Yahoo Customer support',
    ];

    /**
     * @param $body
     * @throws Exception
     */
    public function detect ($body)
    {

        foreach ( $this->keywords as $keyword ) {
            if ( stripos($body, $keyword) !== false ) { // For Case sensitive
                throw new Exception('Your Reply Contains Spam');
            }
        }
    }

}
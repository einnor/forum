<?php

namespace App;

class Spam
{
    public function detect($body)
    {
        $this->detectInvalidKeyWords($body);

        return false;
    }

    private function detectInvalidKeyWords($body)
    {
        $invalidKeyWords = [
            'yahoo customer support'
        ];

        foreach ($invalidKeyWords as $keyWord) {
            if(stripos($body, $keyWord) == false) {
                throw new \Exception('Your reply contains spam');
            }
        }
    }
}
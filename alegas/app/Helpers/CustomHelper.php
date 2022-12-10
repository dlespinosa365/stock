<?php

namespace App\Helpers;


class CustomHelper
{

    static public function domElementString($domElement)
    {
        return $domElement->ownerDocument->saveXML($domElement);
    }

    static public function removeSpecialsChars(string $text)
    {
        $res = str_replace(array("\n", "\r", "\t", "\v", "\x00"), ' ', $text);
        return trim($res);
    }

    static public function strposRecursive($haystack, $needle, $offset = 0, &$results = array()) {
        $offset = strpos($haystack, $needle, $offset);
        if($offset === false) {
            return $results;
        } else {
            $results[] = $offset;
            return CustomHelper::strposRecursive($haystack, $needle, ($offset + 1), $results);
        }
    }

    static public function getAllTextBetween(string $sourceText, string $betweenTextStart, string $betweenTextEnd)
    {
        $output = [];
        $textClean = CustomHelper::removeSpecialsChars($sourceText);
        $betweenTextStart = CustomHelper::removeSpecialsChars($betweenTextStart);
        $betweenTextEnd = CustomHelper::removeSpecialsChars($betweenTextEnd);
        $allPositions = CustomHelper::strposRecursive($textClean, $betweenTextStart);

        dd($allPositions);
        return $output;
    }
}

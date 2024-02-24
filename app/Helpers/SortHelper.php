<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class SortHelper
{
    public static function sortOptionAndMode(string $sortSelected)
    {
        $base = explode("_", $sortSelected);
        $mode = array_pop($base);
        $sortOption = implode('_', $base);
        $allowedModes = ['asc', 'desc'];
        if(!in_array($mode, $allowedModes)){
            return ['sortOption' => 'default'];
        }
        return ['sortOption' => $sortOption, 'mode' => $mode];
    }
}

?>
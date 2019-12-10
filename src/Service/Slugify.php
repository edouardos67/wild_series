<?php


namespace App\Service;


class Slugify
{
    public function generate(string $input) : string
    {
        $slug = strtolower(trim(str_replace(' ', '-', $input)));
        $toReplace = ["&","'", ",",".",";","!","?","#","é","è","ç","à","ù"];
        $replaceBy = ["","","","","","","","","e","e","c","a","u"];
        foreach ($toReplace as $key=>$item) {
            $slug = str_replace($item, $replaceBy[$key], $slug);
        }
        $slug_arr = str_split($slug);
        foreach ($slug_arr  as $key=>$value) {
            if ($slug_arr[$key] ==='-') {
                $i = 1;
                while ($slug_arr [$key+$i] ==='-' && $key!== strlen($slug) -1) {
                    $slug_arr [$key+$i]='*';
                    $i++;
                }
            }
        }
        $slug = str_replace('*', '', implode($slug_arr));
        return $slug;
    }
}
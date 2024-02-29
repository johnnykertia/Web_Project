<?php

use App\Models\Language;
use PhpParser\Node\Expr\Cast\String_;

function formatTags(array $tags): String
{
    return implode(',', $tags);
}

//get selected language session
function getLanguage(): string
{
    if (session()->has('language')) {
        return session('language');
    } else {
        try {
            $language = Language::where('default', 1)->first();
            setLanguage($language->lang);
            return $language->lang;
        } catch (\Throwable $th) {
            setLanguage('en');

            return $language->lang;
        }
    }
}

//set session code di session
function setLanguage(string $code): void
{
    session(['language' => $code]);
}

//Memotong Teks
function truncate(string $text, int $limit = 25,): String
{
    return \Str::limit($text, $limit, '...');
}

//Views ribuan
function countViewsFormat(int $number): String
{
    if($number < 1000){
        return $number;
    }
    else if($number < 1000000){
        return round($number / 1000, 1) . 'K';
    }
    else{
        return round($number /1000000, 1) . 'K';
    }
}

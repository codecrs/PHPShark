<?php

namespace perform{
    class Highlight{
        public static function text($text, $words, $i_color = "#4285F4"){
            $split_words = explode( " ", $words );
            foreach ($split_words as $word) {
                $color = $i_color;
                $text = preg_replace("|($word)|Ui",
                    "<span style=\"color:{$color};\"><b>$1</b></span>", $text );
            }
            return $text;
        }
    }
}

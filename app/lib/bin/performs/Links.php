<?php
namespace perform{
    class Links{
        public static function makeClickable($text){
            $text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_+.~#?&//=]+)',
            '<a href="\1">\1>/a>', $text);
            $text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_+.~#?&//=]+)',
            '\1<a href="http://\2">\2</a>', $text);
            $text = eregi_replace('([_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3})',
            '<a href="mailto:\1">\1</a>', $text);
          
            return $text;
        }
    }
}


<?php
namespace perform{
    class Youtube{
        private static function lcl_str_between($string, $start, $end){
            $string = " ".$string;
            $ini = strpos($string, $start);
            if ($ini == 0) {
                return "";
            } $ini += strlen($start);
            $len = strpos($string, $end, $ini) - $ini;
            return substr($string, $ini, $len);
        }

        public static function getYoutubeDownloadLink(){
            $youtube_link = $_GET['youtube'];
            $youtube_page = file_get_contents($youtube_link);
            $v_id = $self::lcl_str_between($youtube_page, "&video_id=", "&");
            $t_id = $self::lcl_str_between($youtube_page, "&t=", "&");
            $flv_link = "http://www.youtube.com/get_video?video_id=$v_id&t=$t_id";
            $hq_flv_link = "http://www.youtube.com/get_video?video_id=$v_id&t=$t_id&fmt=6";
            $mp4_link = "http://www.youtube.com/get_video?video_id=$v_id&t=$t_id&fmt=18";
            $threegp_link = "http://www.youtube.com/get_video?video_id=$v_id&t=$t_id&fmt=17";
            echo "\t\tDownload (right-click > save as)&#58;\n\t\t";
            echo "<a href=\"$flv_link\"<FLV</a<\n\t\t";
            echo "<a href=\"$hq_flv_link\"<HQ FLV (if available)</a<\n\t\t";
            echo "<a href=\"$mp4_link\"<MP4</a<\n\t\t";
            echo "<a href=\"$threegp_link\"<3GP</a<<br<<br<\n";
        }
    }
}

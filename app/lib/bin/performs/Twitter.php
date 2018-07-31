<?php
namespace perform{
    class Twitter{

        public static function getUser($username){
            $no_of_tweets = 1;
            $feed = "http://search.twitter.com/search.atom?q=from:" . $username . "&rpp=" . $no_of_tweets;
            $xml = simplexml_load_file($feed);
            foreach ($xml->children() as $child) {
                foreach ($child as $value) {
                    if ($value->getName() == "link") {
                        $link = $value['href'];
                    }
                    if ($value->getName() == "content") {
                        $content = $value . "";
                        echo "<p class=\"twit\">{$content}<a class=\"twt\" href=\"{$link}\" title=\">&nbsp; </a></p>";
                    }
                }
            }
        }

        public static function getTweets($hash_tag){

            $url = 'http://search.twitter.com/search.atom?q='.urlencode($hash_tag) ;
            echo "<p>Connecting to <strong>$url</strong> ...</p>";
            $ch = curl_init($url);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
            $xml = curl_exec ($ch);
            curl_close ($ch);
        
            //If you want to see the response from Twitter, uncomment this next part out:
            //echo "<p>Response:</p>";
            //echo "<pre>".htmlspecialchars($xml)."</pre>";
        
            $affected = 0;
            $twelement = new SimpleXMLElement($xml);
            foreach ($twelement->entry as $entry) {
                $text = trim($entry->title);
                $author = trim($entry->author->name);
                $time = strtotime($entry->published);
                $id = $entry->id;
                echo "<p>Tweet from ".$author.": <strong>".$text."</strong>  <em>Posted ".date('n/j/y g:i a', $time)."</em></p>";
            }
            return true ;
        }

        public static function count($url){
            $content = file_get_contents("http://api.tweetmeme.com/url_info?url=".$url);
            $element = new SimpleXmlElement($content);
            $retweets = $element->story->url_count;
            if ($retweets) {
                return $retweets;
            } else {
                return 0;
            }
        }

    }
}


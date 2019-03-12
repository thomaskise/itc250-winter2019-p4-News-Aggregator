<?php
/**
 * config_news.php contains the functions and classes specific to tne News Feed application of the Cotlets website
 * 
 * @package Cotlets website
 * @subpackage News feed
 * @author itc250 winter 2019 project team
 * @author Junqiao Mou(Derrick)
 * @author Thom Harrington
 * @author Elizabeth Jones
 * @author Fikirte Mulugeta
 * @version 1.0 March 12, 2019
 * @link https://<tbd>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @see related_file.php
 * @see other_related_file.php
 * @todo Update code blocks
 */


/*Function  the left means to exclude the left side of the specified string, right means to exclude  to the right of the specified string */
function excludeString($replacedString, $originalString, $a){
    if(!$replacedString){
        return "The replaced string does not exist";
    }
    $replacedString = mb_convert_encoding($replacedString, 'GB2312', 'UTF-8');
    $originalString = mb_convert_encoding($originalString, 'GB2312', 'UTF-8');
    if($a == ""){
    $last = str_replace($replacedString, "", $originalString);
    }elseif($a == "right"){
        $last = preg_replace("/[" . $replacedString . "]+[\d\D\w\W\s\S]*/", "", $originalString);

        }elseif($a == "left"){
            $last = preg_replace("/[\d\D\w\W\s\S]*[". $replacedString . "]+/", "", $originalString);
            }
            $last =  mb_convert_encoding($last, 'UTF-8', 'GB2312'); 
    return $last;

}

// Strip the html tage
function stripHtmlTags($tags, $str, $content = true)
{
    $html = [];
    // Whether to retain the text characters in the tag
    if($content){
        foreach ($tags as $tag) {
            $html[] = '/(<' . $tag . '.*?>(.|\n)*?<\/' . $tag . '>)/is';
        }
    }else{
        foreach ($tags as $tag) {
            $html[] = "/(<(?:\/" . $tag . "|" . $tag . ")[^>]*>)/is";
        }
    }
    $data = preg_replace($html, '', $str);
    return $data;
}

//	Rrim the space 
function trimSpace($str){
    $trimResult = array("&nbsp;");
  	//	Replace all occurrences of the search string with the replacement string
    return str_replace($trimResult, '', $str);  
}

/**
* class NewsFeed - creates an object for each news feed
* 
* The class has three attributes
*     1. $TopicID (string): is the internal id that identifies the RSS Feed
*     2. $SessionStartTime (int): this is input from time() and represents the time the item was placed in session
*     3. $RssFeed (string): is the RSS Feed stored as a string 
*
*<code>
*     $_SESSION['NewsFeeds'][] = new NewsFeed($currentTopicID, time(), $file);
*</code>
*
* @see RelatedClass
* @todo none
*/

class NewsFeed{
    public $TopicID = 0;
    public $SessionStartTime = 0;
    public $RssFeed = '';
    
    public function __construct($TopicID, $SessionStartTime, $RssFeed){
        $this->TopicID = $TopicID;
        $this->SessionStartTime = $SessionStartTime;
        $this->RssFeed = $RssFeed;
    }//end NewsFeed constructor
}//end NewsFeed class

  /**
   * function getRssFeed($currentTopicID, $url)
   *
   * Takes an input topic id & RSS feed url and returns an RSS feed
   * 
   * First is checks to see if the NewsFeeds session has been set. If not, it initializes the session
   * If the session has been set, it looks for a NewsFeed object for the requested TopicID and checks for expiration
   * If it finds an unexpired NewsFeed object, it returnw the RSS feed file from the object as $file
   *    otherwise it gets the RSS feed file from the internet & constructs a NewsFeeds object and populates the $_SESSION
   *    and then it returns the RSS feed file it retrieved from the interest as $file
   *
   * <code>
   * $output = someFunction('Joe','1987-01-01');
   * </code>
   *
   * @param interger $currentTopicID the topic id reguest
   * @param string $url is the url for retriving the RSS feed
   * @return string $file is the RSS feed
   * @todo none
   */

function getRssFeed($currentTopicID, $url, $maxSession) {
    $file = '';
    $secondsSinceRefresh = 0;
    $sessionFoundAlive = 'no';

    //if session not set, initialize it as array
    if(!isset($_SESSION['NewsFeeds'])){
        $_SESSION['NewsFeeds'] = array(); //think feeds - if we don't have an array, start one
    }else{//loop through objects in the session to find the topicID if it exists and is not expired
        foreach ($_SESSION['NewsFeeds'] as $feed) {
            if($feed->TopicID == $currentTopicID) {// find the topicID
                $lastRefresh = date('m/d/Y H:i:s', $feed->SessionStartTime);
                $secondsSinceRefresh =  (time() - $feed->SessionStartTime);//add 10 seconds to allow time for retrieval
                if(($maxSession >  $secondsSinceRefresh + 2) && ($sessionFoundAlive == 'no')) {//use the session if it is fresh (2 second jic is added)
                    $sessionFoundAlive = 'yes';
                    $file = $feed->RssFeed;
                }//end if not timed out
             }//end if topicID
        }//end foreach
    }// end if-else

    //if the session was just intitalized or the requested object wasn't found, then set up a new object for the currentTopicID
    if($sessionFoundAlive == 'no'){
        $file = file_get_contents($url);// get the rss feed from the internet
        $_SESSION['NewsFeeds'][] = new NewsFeed($currentTopicID, time(), $file);
        $lastRefresh = date('m/d/Y H:i:s', time());
        $secondsSinceRefresh = 0;
    }

    $feedData = array($file, $lastRefresh);
    return $feedData; 
}

?>

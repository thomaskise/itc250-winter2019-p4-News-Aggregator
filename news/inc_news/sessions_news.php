<?php
/**
 * sessions_news.php handles sessions for a news aggregation app
 * 
 * Upstream variables determined by feed.php
 *      -- $url which is the url for the rss feed
 *      -- $currentTopicID which is the topic id and will be used to id within in the session
 * 
 * This module will check to see of the cache is available and fresh
 * If it is not available or not fresh, it will use the $url to retrieve it and store it in cache
 * It will return either $file or $result (tbd)
 * 
 * 
 * @author Thom Harrington
 * @author itc250 winter 2019 project team
 * @author Elizabeth Jones
 * @author Junqiao Mou(Derrick)
 * @author Fikirte Mulugeta
 * @version 1.0 March 12, 2019
 * @link https://<tbd>
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @todo create function based on this code
 * @todo check into session GC - not sure what needs to be done
 * @todo 
 * @todo 
 */
    $maxSession = 3 * 60;
    $file = '';
    $secondsSinceRefresh = 0;
    $sessionFoundAlive = 'no';

    //if session not set, initialize it as array
    if(!isset($_SESSION['NewsFeeds'])){
        $_SESSION['NewsFeeds'] = array(); //think feeds - if we don't have an array, start one
    }//end if

    //loop through objects in the session to find the topicID if it exists and is not expired
    foreach ($_SESSION['NewsFeeds'] as $feed) {
        if($feed->TopicID == $currentTopicID) {// find the topicID
            $secondsSinceRefresh =  (time() - $feed->SessionStartTime);//add 10 seconds to allow time for retrieval
            if($maxSession >  $secondsSinceRefresh + 2) {//use the session if it is fresh (2 second jic is added)
                $sessionFoundAlive = 'yes';
                $file = $feed->RssFeed;
            }//end if not timed out
         }//end if topicID
    }//end foreach

    //if the session was just intitalized or the requested object wasn't found, then set up a new object for the currentTopicID
    if($sessionFoundAlive == 'no'){
        $file = file_get_contents($url);// get the rss feed from the internet
        $_SESSION['NewsFeeds'][] = new NewsFeed($currentTopicID, time(), $file);
        $secondsSinceRefresh = 0;
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

?>

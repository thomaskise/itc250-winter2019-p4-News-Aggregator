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
 * @todo none
 */
//   lines 25-35 will be removed as these are redundant in the parent module
    require '../../inc_0700/config_inc.php'; 

//    $currentTopicID = '1';$url = 'https://news.google.com/rss/search?q=vegetarian+foods&hl=en-US&gl=US&ceid=US:en';
//    $currentTopicID = '2';$url = 'https://news.google.com/rss/search?q=vegan+food&hl=en-US&gl=US&ceid=US:en';
//    $currentTopicID = '3';$url = 'https://news.google.com/rss/search?q=thai+food&hl=en-US&gl=US&ceid=US:en';
//    $currentTopicID = '4';$url = 'https://news.google.com/rss/search?q=modern+art&hl=en-US&gl=US&ceid=US:en'; 
//    $currentTopicID = '5';$url = 'https://news.google.com/rss/search?q=impressionism&hl=en-US&gl=US&ceid=US:en'; 
//    $currentTopicID = '6';$url = 'https://news.google.com/rss/search?q=ancient+greek+art&hl=en-US&gl=US&ceid=US:en'; 
    $currentTopicID = '7';$url = 'https://news.google.com/rss/search?q=travel+grand+canyon&hl=en-US&gl=US&ceid=US:en'; 
//    $currentTopicID = '8';$url = 'https://news.google.com/rss/search?q=travel+disneyland&hl=en-US&gl=US&ceid=US:en'; 
//    $currentTopicID = '9';$url = 'https://news.google.com/rss/search?q=travel+london&hl=en-US&gl=US&ceid=US:en'; 

    $maxSession = 10 * 60;
    $file = '';
    $fileSource = '';
    $secondsSinceRefresh = 0;
    $sessionFoundAlive = 'no';

/**
 * Check the session status. Return values are:
 * _DISABLED = 0
 * _NONE = 1
 * _ACTIVE = 2
 **/
 
    echo '<b><u>Session status values : 0=disabled; 1=none; 2=active:</b></u><br />';
    $currentStatus = session_status();
    echo 'Session status before start: ' . $currentStatus . '<br /><br />';

    startSession();
    $currentStatus = session_status();
    echo 'Session status after start: ' . $currentStatus . '<br /><br />';
           
    if(!isset($_SESSION['NewsFeeds'])){//if session not set, initialize
        echo '<b><u>NewsFeeds session ($_SESSION[NewsFeeds]) was intialized. Here\'s the var_dump: </b></u> <br />';
        $_SESSION['NewsFeeds'] = array(); //think feeds - if we don't have an array, start one
    }else {//end if
        echo '<b><u>NewsFeeds session ($_SESSION[NewsFeeds]) was already set. Here\'s the var_dump: </b></u>  <br />';
        echo '<pre>';
            echo var_dump($_SESSION['NewsFeeds']) . '<br /><br />';
        echo '</pre>';
        echo '<pre>';
            echo var_dump($_SESSION) . '<br /><br />';
        echo '</pre>';
        //loop through sessions to find the right one if it exists and is not expired
        foreach ($_SESSION as $feed) {
            echo 'Here\'s the $feed->topic id I found: ' . $feed->TopicID . '<br /><br />';
            if($feed->TopicID == $currentTopicID) {// find the topicID
                echo 'Now we\'re inside the \'topic if\' of the foreach loop<br /><br />';
                $secondsSinceRefresh =  (time() - $feed->SessionStartTime);//add 10 seconds to allow time for retrieval
                echo ' seconds current time = ' .  time() . '<br />';
                echo '- seconds session start = ' . $feed->SessionStartTime . '<br />';
                echo '= seconds since refresh ' . $secondsSinceRefresh . '<br /><br />';
                if($maxSession >  $secondsSinceRefresh + 10) {//use the session if it is fresh (10 second jic is added)
                    echo 'Now we\'re inside the \'timeout if\' of the foreach loop<br /><br />';
                    $fileSource = '<u>session found & not expired</u> (if-conditional within foreach)';
                    $sessionFoundAlive = 'yes';
                    $file = $feed->RssFeed;
                }//end if not timed out
             }//end if topicID
        }//end foreach
    }//end if not isset

    //if the session isn't found alive, then set it
    if($sessionFoundAlive == 'no'){
        $fileSource = '<u>session not found alive</u> (if-conditional)<br />';
        $file = file_get_contents($url);// get the rss feed from the internet
        $_SESSION['NewsFeeds'] = new NewsFeed($currentTopicID, time(), $file);
        $secondsSinceRefresh = 0;
        echo 'Final condition, if session not found alive, was just exectuted<br /><br />';
    }

//output results - lines 97 - 191 will all be deleted after this is working.
        //dumpDie($_SESSION['NewsFeeds']);
        $currentStatus = session_status();
        echo '<b><u>Results:</b></u><br />';
        echo 'Session status at end: ' . $currentStatus . '<br /><br />';
        echo 'Input topic id: ' . $currentTopicID . '<br /><br />';
        echo 'was session found alive? ' . $sessionFoundAlive . ' (If no then foreach not complete. If yes then foreach successful.) <br /><br />';
        echo 'max session: ' . $maxSession . ' seconds<br /><br />';
        echo 'seconds since refresh: ' . $secondsSinceRefresh . '<br /><br />';
        echo '$file was set in the   ' . $fileSource . ' <br /><br />';
       // echo 'file data: ' . $file . '<br />';

//        echo '<pre>';
//            echo '<b><u>Session dump at end ($_Session[NewsFeed]) - just after population:</u></b><br />';
//            var_dump($_SESSION['Newsfeed']) . '<br />';
//            //echo 'session id = ' . $_SESSION["NewsFeeds"]["SessionID"];
//        echo '</pre><br /><br />';
    foreach ($_SESSION as $feed) {
        echo '<pre>';
            echo '<b><u>Session dump at end ($_Session) - just after population:</u></b><br />';
            var_dump($_SESSION) . '<br />';
            //echo 'session id = ' . $_SESSION["NewsFeeds"]["SessionID"];
        echo '</pre>';
    }
        echo '<b><u>This is marginal success</u>, but why is the session being replaced instead of added to?<br /><br />';

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
    public $TopicID ='';
    public $SessionStartTime = '';
    public $RssFeed = '';
    
    public function __construct($TopicID, $SessionStartTime, $RssFeed){
        $this->TopicID = $TopicID;
        $this->SessionStartTime = $SessionStartTime;
        $this->RssFeed = $RssFeed;
    }//end NewsFeed constructor
}//end NewsFeed class

?>

<?php
/**
 * sessions_news.php handles sessions for a news aggregation app
 * 
 * Upstream variables determined by feed.php
 *      -- $url which is the url for the rss feed
 *      -- $topid which is the topic id and will be used to id within in the session
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
    //lines 25-35 will be removed as these are redundant in the parent module
    require '../../inc_0700/config_inc.php'; 
    $url = 'https://news.google.com/rss/search?q=vegetarian+foods&hl=en-US&gl=US&ceid=US:en';
//    $url = 'https://news.google.com/rss/search?q=vegan+food&hl=en-US&gl=US&ceid=US:en';
//    $url = 'https://news.google.com/rss/search?q=thai+food&hl=en-US&gl=US&ceid=US:en';
//    $url = 'https://news.google.com/rss/search?q=modern+art&hl=en-US&gl=US&ceid=US:en'; 
//    $url = 'https://news.google.com/rss/search?q=impressionism&hl=en-US&gl=US&ceid=US:en'; 
//    $url = 'https://news.google.com/rss/search?q=ancient+greek+art&hl=en-US&gl=US&ceid=US:en'; 
//    $url = 'https://news.google.com/rss/search?q=travel+grand+canyon&hl=en-US&gl=US&ceid=US:en'; 
//    $url = 'https://news.google.com/rss/search?q=travel+disneyland&hl=en-US&gl=US&ceid=US:en'; 
//    $url = 'https://news.google.com/rss/search?q=travel+london&hl=en-US&gl=US&ceid=US:en'; 
    $topid = '1';

    $nowtime=time();
    $maxSession=600;
    $secondsSinceRefresh = 0;
    $file = '';
    $fileSource = '';
    $sessionFoundAlive = 'no';

    session_name('NewsFeeds');
    startSession();

//if session not set, initialize
    if(!isset($_SESSION['NewsFeeds'])){
        echo 'session not set <br />';
        $_SESSION['NewsFeeds'] = array(); //think feeds - if we don't have an array, start one
    }//end if
    echo '<pre>';
        echo var_dump($_SESSION['NewsFeeds']);
    echo '</pre>';


//loop through sessions to find the right one if it exists and is not expired
    foreach ($_SESSION['NewsFeeds'] as $feed) {
        echo 'Here\'s the topic id I found: ' . $feed->TopicID . '<br />';
        if($feed->TopicID == $topid) {// find the topicID
            $secondsSinceRefresh == int(time() - int($feed->SessionStartTime) + 10);//add 10 seconds to allow time for retrieval
            if($maxSession >  $secondsSinceRefresh + 10) {//use the session if it is fresh (10 second jic is added)
                $fileSource = 'session found & not expired';
                $sessionFoundAlive = 'yes';
                $file = $feed->RssFeed;
            }//end if not timed out
         }//end if topicID
    }//end foreach

//if the session isn't found alive, then set it
    if($sessionFoundAlive == 'no'){
        echo 'sesssion null or not found, so start <br /><br />';
        $fileSource = 'session not found';
        $file = file_get_contents($url);// get the rss feed from the internet
        $_SESSION['NewsFeeds'][] = new NewsFeed($topid, time(), $file);
        $secondsSinceRefresh = time() - time();
    }

//output results
        //dumpDie($_SESSION['NewsFeeds']);
        echo 'Results: <br />';
        echo 'was session found alive? ' . $sessionFoundAlive . ' <br /><br />';
        echo 'max session: ' . $maxSession . ' <br /><br />';
        echo 'seconds since refresh: ' . $secondsSinceRefresh . '<br /><br />';
        echo 'File came from what loop?  ' . $fileSource . '<br /><br />';
        echo 'file data: ' . $file . '<br />';

        echo '<pre>';
            echo '<b><u>This is a print_r($_SESSION)) : </u></b><br />';
            print_r($_SESSION) . '<br />';
            //echo 'session id = ' . $_SESSION["NewsFeeds"]["SessionID"];
        echo '</pre>';
        echo '<b><u>This is marginal success</u></b>';

/**
* class NewsFeed
* 
*
*
* More stuff about the class
*
*<code>
* $myClass = new someClass('Joe');
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

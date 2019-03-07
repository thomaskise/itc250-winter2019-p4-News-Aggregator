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
    //lines 17-19 will be removed as these are redundant in the parent module
    require '../../inc_0700/config_inc.php'; 
    $url = 'https://news.google.com/rss/search?q=vegetarian+foods&hl=en-US&gl=US&ceid=US:en';
    $topid = '1';
	$file = file_get_contents($url);
    $result = new SimpleXMLElement($file);
	//$result = new SimpleXMLElement($file);
    //echo $file;
    //echo $result->channel->item;
    //die();
    startSession();
    
    $nowtime=time();
// if the session is not set then set it
// if it is set, then check for the topic
// if the topic is found then check to see if it has expired
// if any of the above fail, retrieve the rss feed from the internet
// load the session data in either $file or $result (tbd)
//    if(!isset($_SESSION['NewsFeeds'])){
        $_SESSION["NewsFeeds"] = array();        
        $cacheStartTime=$nowtime;
        $myItem = new NewsFeed(session_id(), $topid, $cacheStartTime);
        $myItem->rssFeed($result);
        $_SESSION["NewsFeeds"] = array($myItem);

        echo '<b><u>This is the result of a foreach statement: </u></b><br />';
        foreach ($_SESSION["NewsFeeds"] as $key => $value) {
            echo 'My key is: ' . $key . '<br />';
            echo 'My value is: ' . $value . '<br /><br />';
        }

        echo '<b><u>This is a simple echo of a variable: </u></b><br />';
        echo $cacheStartTime . '<br />';
        echo '<br />';
        //echo 'This is a var_dump of $myItem: <br /><br />';
            //var_dump($myItem);
            //var_dump($_SESSION['NewsFeeds']['$myItem->SessionID']);
        //$currentSessionID=($_SESSION["NewsFeed"][0]["SessionID"]);
        //echo $currentSessionID;

        echo '<b><u>This is an echo($_SESSION)) : </u></b><br />';
        echo($_SESSION["NewsFeeds"]["Array"]) . '<br /><br />';

        echo '<pre>';
            echo '<b><u>This is a print_r($_SESSION)) : </u></b><br />';
            print_r($_SESSION) . '<br />';
            //echo 'session id = ' . $_SESSION["NewsFeeds"]["SessionID"];
        echo '</pre>';

        echo '<b><u>This is marginal success</u></b>';
        die();
        
 //   }elseif($_SESSION['NewsFeeds']
//      ){
       //  $_SESSION['test'][] = new SimpleXMLElement($file);
        //if($_SESSION['sessionid'][])
//    }
    
class NewsFeed{
    public $SessionID = '';
    public $TopicID ='';
    public $SessionTime = '';
    public $feedArray = array();
    
    public function __construct($SessionID, $TopicID, $SessionTime){
        $this->SessionID = $SessionID;
        $this->TopicID = $TopicID;
        $this->SessionTime = $SessionTime;
    }//end NewsFeed constructor
    
    public function rssFeed($rss) {
        $this->Feeds = $rss;
        
    }//end rssFeed
}//end NewsFeed class

?>

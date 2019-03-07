<?php
    require '../inc_0700/config_inc.php'; 
    $url = 'https://news.google.com/rss/search?q=vegetarian+foods&hl=en-US&gl=US&ceid=US:en';
	$file = file_get_contents($url);
    $result = new SimpleXMLElement($file);
    $topid = '1';
	//$result = new SimpleXMLElement($file);
    //echo $file;
    //echo $result->channel->item;
    //die();
    startSession();
    
    $nowtime=time();
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
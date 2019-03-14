<?php
/**
 *************************************************************************
 *  clean_one.php
 *
 *************************************************************************
 *
 * clean_one.php is the clean (wipe out) ALL old sessions
 * or clean (wipe out) ONE old sessions
 * Show Debugging Information
 *
 *
 * @package Clean One
 * @author itc250 winter 2019 project team
 * @author Junqiao Mou(Derrick)
 * @version 1.0 March 14, 2019
 * @link https://d.zjwda.org/admin/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @todo none
 */

require '/www/wwwroot/d.zjwda.org/news/inc_news/config_news.php'; #provides configuration, pathing, error handling, db credentials
require '/www/wwwroot/d.zjwda.org/inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials

 /**
   * Clean (wipe out) ONE old sessions
   *
   * When there is a POST value and $_SESSION value, unset one
   *
   * <code>
   * POST
   * clean_one.php?id={TopicId}
   * </code>
   *
   * @param int    $_POST['id']           Topic Id
   * @param string $_SESSION['NewsFeeds'] SESSION
   * @todo none
*/
if(isset($_POST['id']) && isset($_SESSION['NewsFeeds'])){
    	foreach(array_keys($_SESSION['NewsFeeds']) as $key){
          	echo 'Key: '.$key.'<br>';
      		if($_SESSION['NewsFeeds'][$key]->TopicID == $_POST['id']){
              	echo 'TopicID: ' . $_SESSION['NewsFeeds'][$key]->TopicID;
          		unset($_SESSION['NewsFeeds'][$key]);
          		echo 'Deleted';
        	}
    	}
      echo 'Deleted';
}else{
  echo 'Nothing';
}

 /**
   * Clean (wipe out) ALL old sessions
   *
   * When there is a POST value, unset All
   *
   * <code>
   * POST
   * clean_one.php?all={value}
   * </code>
   *
   * @param int $_POST['all']  value
   * @todo none
*/
if(isset($_POST['all'])){
      unset($_SESSION['NewsFeeds']);
}

 /**
   * Show the debugging Information
   *
   * When there is set $_SESSION['NewsFeeds']
   *
   *
   * @param string $_SESSION['NewsFeeds'] SESSION data
   * @todo none
*/
if(isset($_SESSION['NewsFeeds'])){
  	echo '<br>Debugging Information<br>';
	echo '<pre>' ,var_dump($_SESSION['NewsFeeds']), '</pre>';
  	echo 'Count: '.count($_SESSION['NewsFeeds']).'<br>';
	foreach(array_keys($_SESSION['NewsFeeds']) as $key){
		echo 'Key: '.$key.'<br>';
		echo 'TopicID: '.$_SESSION['NewsFeeds'][$key]->TopicID.'<br>'; 
    }
}
?>

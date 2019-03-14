<?php

require 'inc_news/config_news.php'; #provides configuration, pathing, error handling, db credentials
require '/www/wwwroot/d.zjwda.org/inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials


if(isset($_GET['id']) && isset($_SESSION['NewsFeeds'])){
    	foreach(array_keys($_SESSION['NewsFeeds']) as $key){
          	echo 'Key: '.$key.'<br>';
      		if($_SESSION['NewsFeeds'][$key]->TopicID == $_GET['id']){
              	echo 'TopicID: ' . $_SESSION['NewsFeeds'][$key]->TopicID;
          		unset($_SESSION['NewsFeeds'][$key]);
          		echo 'Deleted';
        	}
    	}
      echo 'Deleted';
}else{
  echo 'Nothing';
}

//Debugging Information
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

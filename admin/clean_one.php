<?php

require '/www/wwwroot/d.zjwda.org/news/inc_news/config_news.php'; #provides configuration, pathing, error handling, db credentials
require '/www/wwwroot/d.zjwda.org/inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials

//When there is a POST value and $_SESSION value, unset one
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
//When there is a POST value, unset
if(isset($_POST['all'])){
      unset($_SESSION['NewsFeeds']);
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

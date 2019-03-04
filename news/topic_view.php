<?php
/**
 *************************************************************************
 *************************************************************************
 *
 *  needs to be updated for topic_view.php
 *
 *************************************************************************
 *************************************************************************
 *
 * survey_view.php is a page to demonstrate the proof of concept of the 
 * initial SurveySez objects.
 *
 * Objects in this version are the Survey, Question & Answer objects
 * 
 * @package SurveySez
 * @author William Newman
 * @version 2.12 2015/06/04
 * @link http://newmanix.com/ 
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @see Question.php
 * @see Answer.php
 * @see Response.php
 * @see Choice.php
 */

require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
require 'filtration.php'; 
header("Content-type: text/html; charset=utf-8");

spl_autoload_register('MyAutoLoader::NamespaceLoader');//required to load SurveySez namespace objects
$config->metaRobots = 'no index, no follow';#never index survey pages
# check variable of item passed in - if invalid data, forcibly redirect back to demo_list.php page
if(isset($_GET['feedid']) && (int)$_GET['feedid'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['feedid']; #Convert to integer, will equate to zero if fails
}else{
	myRedirect(VIRTUAL_PATH . "news/index.php");
}
/*
$mySurvey = new SurveySez\Survey($myID); //MY_Survey extends survey class so methods can be added

if($mySurvey->isValid)
{
	$config->titleTag = "'" . $mySurvey->Title . "' Survey!";
}else{
	$config->titleTag = smartTitle(); //use constant 
}
*/
#END CONFIG AREA ---------------------------------------------------------- 
get_header(); #defaults to theme header or header_inc.php
?>

<?php 
$sqlTopicID = "SELECT * FROM `wn19_Topics` WHERE `TopicID` = ".$myID."";
$resultTopicID = mysqli_query(IDB::conn(),$sqlTopicID) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

?>
<h3>News Detail</h3>

<?php
if(mysqli_num_rows($resultTopicID) > 0){
  
    # Output the number of surveys
  	while($row = mysqli_fetch_assoc($resultTopicID)){
    	echo '<li class="side-nav-item">';
    	echo '<i class="dripicons-copy"></i>';
    	echo '<a> '. $row['TopicName'].'</span> ';
    	echo '</li>';
    	$url = $row['TopicURL'];
    	$file = file_get_contents($url);
		$result = new SimpleXMLElement($file);
		$image_url = '';
		$number = 1;
		foreach($result->channel->item as $item){
  			echo '<br><br>Number: ' . $number ++. '<br><br>';
  			echo 'Title: ' . excludeString('-', $item->title, 'right') .'<br>';
  			echo 'Link: ' . $item->link .'<br>';
 			/* echo 'Guid: ' . $item->guid.'<br>';*/
  			echo 'PubDate: ' . $item->pubDate.'<br>';
  
  			$description =  trimSpace(stripHtmlTags(['p'], stripHtmlTags(['a', 'font'], $item->description, $content = true), $content = false));
  
  			echo 'Description: ' . $description.'<br>';
  			echo 'Source: ' . $item->source.'<br>';
  			if(isset($item->children('media', true)->content)){
  				echo 'Img: ' . $item->children('media', true)->content->attributes()['url'];
    			$image_url = $item->children('media', true)->content->attributes()['url'];
  			}else{
    			$image_url = 'None';
  			}
  		}
	}
}
?>


<?php

get_footer(); #defaults to theme footer or footer_inc.php
?>

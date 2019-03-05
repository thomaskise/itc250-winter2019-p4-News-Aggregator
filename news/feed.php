
<?php
/**
 *************************************************************************
 *************************************************************************
 *
 *  feed.php
 *
 *************************************************************************
 *************************************************************************
 *
 * feed.php is a function to generate google rss from keywords and capture its 
 * content.
 *
 * Objects in this version are the Survey, Question & Answer objects
 * 
 * @package 
 * @author Derrick Mou
 * @version 1.0 2019/03/05
 * @link https://d.zjwda.org/news/feed.php?$topid=1
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
require '../include/config_news.php'; 

  
$sqlTopicID = "SELECT * FROM `wn19_Topics` WHERE `TopicID` = ".$_GET['topid']."";
$resultTopicID = mysqli_query(IDB::conn(),$sqlTopicID) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));


?>

<div class="container">
	<div id="accordion">  


<?php
if(mysqli_num_rows($resultTopicID) > 0){
  
    # Output the number of surveys
  	while($row = mysqli_fetch_assoc($resultTopicID)){
    	echo '<li>';
    	echo '<h3> '. $row['TopicName'].'</h3> ';
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
</div>
</div>  
<?php
get_footer();
?>


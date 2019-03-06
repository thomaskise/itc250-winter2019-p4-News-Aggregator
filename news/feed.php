<link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>


	

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
require 'include/config_news.php'; 

  
$sqlTopicID = "SELECT * FROM `wn19_Topics` WHERE `TopicID` = ".$_GET['topid']."";
$resultTopicID = mysqli_query(IDB::conn(),$sqlTopicID) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));


?>

<div class="container">
	<div id="accordion">  
		<div class="panel-group" id="accordion">
<?php
if(mysqli_num_rows($resultTopicID) > 0){
  
    # Output the number of surveys
  	while($row = mysqli_fetch_assoc($resultTopicID)){
    	echo '<li>';
    	echo '<h3> <center>'. $row['TopicName'].'</center></h3> ';
    	echo '</li>';
    	$url = $row['TopicURL'];
    	$file = file_get_contents($url);
		$result = new SimpleXMLElement($file);
		$image_url = '';
		$number = 1;
		foreach($result->channel->item as $item){
          	echo '<div class="panel panel-default">';
          	echo '<div class="panel-heading">';
			echo '<h4 class="panel-title">';
			echo '<a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$number.'">';
            echo $number . '. ';
  			echo excludeString('-', $item->title, 'right');
          	echo '</a>';
			echo '</h4>';
			echo '</div>';
         	
          	
          	
          	echo '<div id="collapse'.$number.'" class="panel-collapse collapse">';

          	echo '<div class="col-sm-3">';
  			if(isset($item->children('media', true)->content)){
  				//echo 'Img: ' . $item->children('media', true)->content->attributes()['url'];
              	echo '<img src="' . $item->children('media', true)->content->attributes()['url'].'" align="middle" alt="Smiley face" height="228" width="228">';
    			$image_url = $item->children('media', true)->content->attributes()['url'];
  			}else{
    			$image_url = 'None';
  			}
          	echo '</div>';
          
          	echo '<div class="col-sm-9">';
			echo '<div class="panel-body">';
          
          	if (strstr($item->description, '<ol>') && strstr($item->description, '<li>')){
              	$description =  strip_tags($item->description, '<ol><li>');
            }else{
              	$description =  trimSpace(stripHtmlTags(['p'], stripHtmlTags(['a', 'font'], $item->description, $content = true), $content = false));
            }
          	
          	
          	echo '<strong>Description:<br></strong>';
  			echo '<a href="'. $item->link .'" target="_blank"> ' . $description.'</a><br><br>';
  			
 			/* echo 'Guid: ' . $item->guid.'<br>';*/
          	echo '<strong align="right">Source: </strong> ' . $item->source.'<br><br>';
  			echo '<strong align="right">Date of Publication: </strong>' . $item->pubDate.'<br><br>';
  
          	echo '<a href="'. $item->link .'" target="_blank">' . $item->link .'</a><br>';
          	echo '</div>';
          	
          
           	echo '</div>';
			echo '</div>';
          	echo '</div>';
          
          	$number ++;
  		}
	}
 

}
?>
</div>
</div>
  
  
  
</div>  
<?php
get_footer();
?>



<?php
/**
 *************************************************************************
 *************************************************************************.
 *
 *  feed.php
 *
 *************************************************************************
 *************************************************************************
 *
 * feed.php is a function to generate google rss from keywords and capture its
 * content.
 *
 * @author itc250 winter 2019 project team
 * @author Junqiao Mou(Derrick)
 * @author Elizabeth Jones
 * @author Thom Harrington
 * @author Fikirte Mulugeta
 *
 * @version 1.0 March 12, 2019
 * @version 1.0 2019/03/05
 *
 * @see https://d.zjwda.org/news/feed.php?$topid=1
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */
require 'inc_news/config_news.php';
require '../inc_0700/config_inc.php'; //provides configuration, pathing, error handling, db credentials

get_header();

$sqlTopicID = 'SELECT * FROM wn19_Topics WHERE TopicID = '.$_GET['id'];
$resultTopicID = mysqli_query(IDB::conn(), $sqlTopicID) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
?>

<div class="container">
	<div id="accordion">  
		<div class="panel-group" id="accordion">
<?php
if (mysqli_num_rows($resultTopicID) > 0) {
    $file = ''; //initialize the $file variable coming back from getRssFeed()
    $feedRefreshTime = 0;
    $feedData = array();
    $row = mysqli_fetch_assoc($resultTopicID);
    $url = $row['TopicURL'];
    $currentTopicID = intval($row['TopicID']);
    $refreshRequest = 0;

    if (isset($_POST['refresh'])) {
        $refreshRequest = $_POST['refresh'];
        $_POST['refresh'] = 0;
    }

    if (1 == $refreshRequest) {
        $feedData = getRssFeed($currentTopicID, $url, 0);
    } else {
        $feedData = getRssFeed($currentTopicID, $url, $cookie_timeout);
    }

    $file = $feedData[0];
    $feedRefreshTime = $feedData[1];

    $result = new SimpleXMLElement($file);
    echo '<h3> <center>'.$row['TopicName'].'</center></h3> ';

    
    echo '
    <table>
        <tr class="table-active">
          <td>
            <form action="#" method="post">
                <button id="refresh" type="Submit" class="btn btn-primary btn-sm" name="refresh" value=1 >Refresh News</button>
            </form>
          </td>
          <td class="refreshTable">
            <em>Last refresh of this feed was: ' .$feedRefreshTime . '</em>
           </td>
        </tr>
    </table>';

    $image_url = '';
    $number = 1;
    foreach ($result->channel->item as $item) {
        echo '<div class="panel panel-default">';
        echo '<div class="panel-heading">';
        echo '<h4 class="panel-title">';
        echo '<a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$number.'">';
        echo $number.'. ';
        echo excludeString('-', $item->title, 'right');
        echo '</a>';
        echo '</h4>';
        echo '</div>';

        echo '<div id="collapse'.$number.'" class="panel-collapse collapse">';
        echo '<div class="col-sm-3">';
        if (isset($item->children('media', true)->content)) {
            //echo 'Img: ' . $item->children('media', true)->content->attributes()['url'];
            echo '<img src="'.$item->children('media', true)->content->attributes()['url'].'" align="middle" alt="Smiley face" height="228" width="228">';
            $image_url = $item->children('media', true)->content->attributes()['url'];
        } else {
            $image_url = 'None';
        }
        echo '</div>';

        echo '<div class="col-sm-9">';
        echo '<div class="panel-body">';
        if (strstr($item->description, '<ol>') && strstr($item->description, '<li>')) {
            $description = strip_tags($item->description, '<ol><li>');
        } else {
            $description = trimSpace(stripHtmlTags(['p'], stripHtmlTags(['a', 'font'], $item->description, $content = true), $content = false));
        }

        echo '<strong>Description:<br></strong>';
        echo '<a href="'.$item->link.'" target="_blank"> '.$description.'</a><br><br>';

        /* echo 'Guid: ' . $item->guid.'<br>';*/
        echo '<strong align="right">Source: </strong> '.$item->source.'<br><br>';
        echo '<strong align="right">Date of Publication: </strong>'.$item->pubDate.'<br><br>';

        echo '<a href="'.$item->link.'" target="_blank">'.$item->link.'</a><br>';
        echo '</div>';

        echo '</div>';
        echo '</div>';
        echo '</div>';

        ++$number;
    }
}
?>
        </div>
    </div>
</div>  
<?php
get_footer();
?>

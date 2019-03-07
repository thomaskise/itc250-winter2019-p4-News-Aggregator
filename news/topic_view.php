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
/*spl_autoload_register('MyAutoLoader::NamespaceLoader');//required to load SurveySez namespace objects*/
$config->metaRobots = 'no index, no follow';#never index survey pages

# check variable of item passed in - if invalid data, forcibly redirect back to demo_list.php page
if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	myRedirect(VIRTUAL_PATH . "news/index.php");
}

$sql = "Select * From wn19_Topics Where CategoryID= " . $_GET['id'];

/*$mySurvey = new SurveySez\Survey($myID); //MY_Survey extends survey class so methods can be added
if($mySurvey->isValid)
{
	$config->titleTag = "'" . $mySurvey->Title . "' Survey!";
}else{
	$config->titleTag = smartTitle(); //use constant 
}*/
#END CONFIG AREA ---------------------------------------------------------- 

get_header(); #defaults to theme header or header_inc.php
?>
<h3>Topics</h3>

<?php

$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));


if(mysqli_num_rows($result) > 0)
{#records exist - process
	
    # Create the table header
    echo '
        <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Category Topics</th>
                </tr>
              </thead>
              <tbody>';
              while($row = mysqli_fetch_assoc($result))
              {# process each row
                echo '
                  <tr>
                  <td><a href="' . VIRTUAL_PATH . 'news/feed.php?id=' . (int)$row['TopicID'] . '">' . dbOut($row['TopicName']) . '</a></td>
                  </tr>';
              }
              echo '</tbody>
            </table>
        </div>'
        /*. $myPager->showNAV()*/; # show paging nav, only if enough records	 
}else{#no records
   echo "<div align=center>There are currently no topics!</div>";	
}
@mysqli_free_result($result);



/*if($mySurvey->isValid)
{ #check to see if we have a valid SurveyID
	echo '<p>' . $mySurvey->Description . '</p>';
	echo $mySurvey->showQuestions();
}else{
	echo "The survey detail has not yet been created!";	
}

 echo '<a href="' . VIRTUAL_PATH . 'surveys/index.php?pg=' . $_SESSION["currentpage"] . '">Check out another survey?</a>';*/

get_footer(); #defaults to theme footer or footer_inc.php



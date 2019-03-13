<?php
/**
 *************************************************************************
 *  topic_view.php
 *************************************************************************
 *
 * topic_view.php displays a list of News Topics once the user has chosen a Category
 *
 * @package Cottlets website
 * @subpackage News feed
 * @author itc250 winter 2019 project team
 * @author Junqiao Mou(Derrick)
 * @author Elizabeth Jones
 * @author Thom Harrington
 * @author Fikirte Mulugeta
 * @version 2.0 March 12, 2019
 * @version 1.0 2019/03/05
 * @link https://d.zjwda.org/news/
 * @link http//elizajcreates.com/itc250/wn19/news/
 * @link https://kiseharrington.com/wn19/news/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @see news/index.php
 * @todo none
 */
 
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
/*spl_autoload_register('MyAutoLoader::NamespaceLoader');//required to load SurveySez namespace objects*/
$config->metaRobots = 'no index, no follow';#never index survey pages

# check variable of item passed in - if invalid data, forcibly redirect back to index.php page
if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	myRedirect(VIRTUAL_PATH . "news/index.php");
}

#SQL to get the Topic names and urls from the DB

$sql = "Select * From wn19_Topics Where CategoryID= " . $_GET['id'];

//adds font awesome icons for arrows on pager
$config->loadhead .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">';

#END CONFIG AREA ---------------------------------------------------------- 

get_header(); #defaults to theme header or header_inc.php
?>
<h3>Topics</h3>

<p>What Topic are you interested in learning more about today?.</p>
<p>To see the related RSS Feed please click one of the links below.</p><BR />

<?php

#images in this case are from font awesome
$prev = '<i class="fa fa-chevron-circle-left"></i>';
$next = '<i class="fa fa-chevron-circle-right"></i>';

# Create instance of new 'pager' class
$myPager = new Pager(5,'',$prev,$next,'');
$sql = $myPager->loadSQL($sql);  #load SQL, add offset

$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

if (isset($_GET['pg'])) {
    $_SESSION['currentpage'] = $_GET['pg'];
}else{
    $_SESSION['currentpage'] = 1;
}

if(mysqli_num_rows($result) > 0)
{#records exist - process
    if($myPager->showTotal()==1){
        $itemz = "topic";}
    else{$itemz = "topics";}  //deal with plural
	
      # Output the number of categories
    echo '<h4>We have ' . $myPager->showTotal() . ' ' . $itemz . ' as listed:</h4>';
    
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
        . $myPager->showNAV(); # show paging nav, only if enough records	 
}else{#no records
   echo "<div align=center>There are currently no topics!</div>";	
}

@mysqli_free_result($result);

 echo '<a href="' . VIRTUAL_PATH . 'news/index.php?pg=' . $_SESSION["currentpage"] . '">Check out another Category?</a>';

get_footer(); #defaults to theme footer or footer_inc.php



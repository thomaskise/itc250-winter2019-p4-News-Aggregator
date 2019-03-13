<?php
/**
 *************************************************************************
 *  index.php
 *
 *************************************************************************
 *
 * index.php is the landing page for a user looking for news RSS feeds
 * it displays a list of News Categories stored in a database
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
 * @todo none
 */

# '../' works for a sub-folder.  use './' for the root  
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials 
 
# SQL statement to grab the Categories from our DB

$sql = "Select * From wn19_FeedCategories";

#Fills <title> tag. If left empty will default to $PageTitle in config_inc.php  
$config->titleTag = 'RSS Feed Categories';

#Fills <meta> tags.  Currently we're adding to the existing meta tags in config_inc.php
$config->metaDescription = 'Seattle Central\'s ITC250 Class news are made with pure PHP! ' . $config->metaDescription;
$config->metaKeywords = 'news,PHP,Fun,Bran,Regular,Regular Expressions,'. $config->metaKeywords;

//adds font awesome icons for arrows on pager
$config->loadhead .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">';

/*
$config->metaDescription = 'Web Database ITC281 class website.'; #Fills <meta> tags.
$config->metaKeywords = 'SCCC,Seattle Central,ITC281,database,mysql,php';
$config->metaRobots = 'no index, no follow';
$config->loadhead = ''; #load page specific JS
$config->banner = ''; #goes inside header
$config->copyright = ''; #goes inside footer
$config->sidebar1 = ''; #goes inside left side of page
$config->sidebar2 = ''; #goes inside right side of page
$config->nav1["page.php"] = "New Page!"; #add a new page to end of nav1 (viewable this page only)!!
$config->nav1 = array("page.php"=>"New Page!") + $config->nav1; #add a new page to beginning of nav1 (viewable this page only)!!
*/

# END CONFIG AREA ---------------------------------------------------------- 

get_header(); #defaults to theme header or header_inc.php
?>
<h3 align="center">RSS Category List</h3>

<p>What category are you interested in learning more about today?.</p>
<p>To see more detailed RSS Feeds please select from the following.</p><BR />
<?php

#images in this case are from font awesome
$prev = '<i class="fa fa-chevron-circle-left"></i>';
$next = '<i class="fa fa-chevron-circle-right"></i>';

# Create instance of new 'pager' class
$myPager = new Pager(5,'',$prev,$next,'');
$sql = $myPager->loadSQL($sql);  #load SQL, add offset

# connection comes first in mysqli (improved) function
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

if (isset($_GET['pg'])) {
    $_SESSION['currentpage'] = $_GET['pg'];
}else{
    $_SESSION['currentpage'] = 1;
}


if(mysqli_num_rows($result) > 0)
{#records exist - process
	if($myPager->showTotal()==1){
        $itemz = "category";}
    else{$itemz = "categories";}  //deal with plural
    
    # Output the number of categories
    echo '<h4>We have ' . $myPager->showTotal() . ' ' . $itemz . ' as listed:</h4>';
    # Create the table header
    echo '
        <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Feed Categories</th>
                </tr>
              </thead>
              <tbody>';
              while($row = mysqli_fetch_assoc($result))
              {# process each row
                echo '
                  <tr>
                  <td><a href="' . VIRTUAL_PATH . 'news/topic_view.php?id=' . (int)$row['CategoryID'] . '">' . dbOut($row['CategoryName']) . '</a></td>
                  </tr>';
              }
              echo '</tbody>
            </table>
        </div>'
        . $myPager->showNAV(); # show paging nav, only if enough records	 
}else{#no records
   echo "<div align=center>There are currently no surveys!</div>";	
}
@mysqli_free_result($result);

get_footer(); #defaults to theme footer or footer_inc.php
?>

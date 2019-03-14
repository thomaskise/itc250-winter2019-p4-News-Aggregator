<?php
/**
 * admin.php session protected 'dashboard' page of links to administrator tool pages
 *
 * Use this file as a landing page after successfully logging in as an administrator.  
 * Be sure this page is not publicly accessible by referencing admin_only_inc.php
 * 
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.14 2012/10/01
 * @link http://www.newmanix.com/
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @see $config->adminLogin.php
 * @see $config->adminValidate.php
 * @see $config->adminLogout.php
 * @see admin_only_inc.php 
 * @todo none
 */

require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
$config->titleTag = 'Admin Dashboard'; #Fills <title> tag. If left empty will fallback to $config->titleTag in config_inc.php
$config->metaRobots = 'no index, no follow';#never index admin pages  
$server = 'localhost'; #CHANGE TO YOUR MYSQL HOST!!
$username='horsey01'; #CHANGE TO YOUR MYSQL USERNAME!!
//END CONFIG AREA ----------------------------------------------------------
$access = "admin"; #admin or higher level can view this page
include_once INCLUDE_PATH . 'admin_only_inc.php'; #session protected page - level is defined in $access var


get_header(); #defaults to theme header or header_inc.php
?>

<div align="center"><h3>Clean (wipe out) ALL old sessions</h3></div>
<br>
<!-- Button trigger modal -->
<div class="text-center"> 
  <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalCenterAll">Clear All</button>
</div>
  

<!-- Modal -->
<div class="modal fade" id="modalCenterAll" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Clear all</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Do you want to Clear all?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="clearAll()">Clear all</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>


<br>

<div align="center"><h3>Clean (wipe out) One old sessions</h3></div>

<?php
$sql = "Select * From wn19_FeedCategories";



$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

?>
<div class="container">
	<div id="accordion">  
		<div class="panel-group" id="accordion">
          
<?php
if(mysqli_num_rows($result) > 0){
       
    $number = 1;
    while($row = mysqli_fetch_assoc($result)){
        echo '<div class="panel panel-default">';
        echo '<div class="panel-heading">';
        echo '<h4 class="panel-title">';
        echo '<a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$number.'">';
        echo $number . '. ';
        echo $row['CategoryName'];
        echo '</a>';
        echo '</h4>';
        echo '</div>';
      
        echo '<div id="collapse'.$number.'" class="panel-collapse collapse">';
      
      	$sqlCategoryID = "Select * From wn19_Topics Where CategoryID= " . $row['CategoryID'];
      	$resultCategoryID = mysqli_query(IDB::conn(),$sqlCategoryID) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
      	if(mysqli_num_rows($resultCategoryID) > 0){
        	while($roww = mysqli_fetch_assoc($resultCategoryID)){
            	//echo $roww['TopicName']. ' ID:'. $roww['TopicID'];
              	echo '<button type="button" class="btn btn-secondary btn-lg" data-toggle="modal" data-target="#modalCenter'.$roww['TopicID'].'">'.$roww['TopicName'].'</button>&nbsp;&nbsp;&nbsp;';
              	//<!-- Modal -->
				echo '<div class="modal fade" id="modalCenter'.$roww['TopicID'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">';
  				echo '<div class="modal-dialog modal-dialog-centered" role="document">';
    			echo '<div class="modal-content">';
      			echo '<div class="modal-header">';
        		echo '<h5 class="modal-title" id="modalLongTitle">Clear</h5>';
        		echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
          		echo '<span aria-hidden="true">&times;</span>';
          		echo '</button>';
      			echo '</div>';
      			echo '<div class="modal-body">';
        		echo 'Do you want to clear it？';
      			echo '</div>';
      			echo '<div class="modal-footer">';
        		
        		echo '<button type="button" id="button-id" value="' . $roww['TopicID'] . '" class="btn btn-primary" onclick="removeFunction(this)">Yes, Clear it!</button>';
                echo '<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>';
      			echo '</div>';
    			echo '</div>';
  				echo '</div>';
				echo '</div>';
            }
        }
        echo '</div>';
      
        echo '</div>';
        $number ++;
    }
}
          
?>
		</div>
      </div>
  </div>

<script>

function removeFunction(objButton) {
  var id = objButton.value;
  $.ajax({
           type: "POST",
           url: 'clean_one.php',
           data:{id:id},
           success:function(html) {
             
             location.reload();
             alert('Success');
           }

      });
}
  
function clearAll() {
  var all = 'all';
  $.ajax({
           type: "POST",
           url: 'clean_one.php',
           data:{all:all},
           success:function(html) {
             
             location.reload();
             alert('Success');
           }

      });
}
</script>
  
<?php get_footer()?>

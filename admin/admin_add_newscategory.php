<?php
/**
 * demo_add.php is a single page web application that allows us to add a new customer to 
 * an existing table
 *
 * This page is based on demo_edit.php
 *
 * Any number of additional steps or processes can be added by adding keywords to the switch 
 * statement and identifying a hidden form field in the previous step's form:
 *
 *<code>
 * <input type="hidden" name="act" value="next" />
 *</code>
 * 
 * The above code shows the parameter "act" being loaded with the value "next" which would be the 
 * unique identifier for the next step of a multi-step process
 *
 * @package nmCommon
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 1.12 2012/02/27
 * @link http://www.newmanix.com/
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @todo add more complicated checkbox & radio button examples
 */

# '../' works for a sub-folder.  use './' for the root  
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
 
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

//END CONFIG AREA ----------------------------------------------------------

# Read the value of 'action' whether it is passed via $_POST or $_GET with $_REQUEST
if(isset($_REQUEST['act'])){$myAction = (trim($_REQUEST['act']));}else{$myAction = "";}

switch ($myAction) 
{//check 'act' for type of process
	case "add": //2) Form for adding new categories data
	 	addForm();
	 	break;
	case "insert": //3) Insert new categories data
		insertExecute();
		break; 
	default: //1)Show existing categories
	 	showCategories();
}

function showCategories()
{//Select category
	global $config;
	get_header();
	echo '<h3 align="center">Add a News Category</h3>';

	$sql = "select CategoryID,CategoryName FROM " . PREFIX . "FeedCategories";
	$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
	if (mysqli_num_rows($result) > 0)//at least one record!
	{//show results
		echo '<table align="center" border="1" style="border-collapse:collapse" cellpadding="3" cellspacing="3">';
		echo '<tr>
				<th>CustomerID</th>
				<th>Category Name</th>
              </tr>
			';
		while ($row = mysqli_fetch_assoc($result))
		{//dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
			echo '<tr>
					<td>'	
				     . (int)$row['CategoryID'] . '</td>
				    <td>' . dbOut($row['CategoryName']) . '</td>
				 </tr>
				';
		}
		echo '</table>';
	}else{//no records
      echo '<div align="center"><h3>Currently No Categories in Database.</h3></div>';
	}
	echo '<div align="center"><a href="' . THIS_PAGE . '?act=add">ADD CATEGORY</a></div>';
	@mysqli_free_result($result); //free resources
	get_footer();
}

function addForm()
{# shows details from a single customer, and preloads their first name in a form.
	global $config;
	$config->loadhead .= '
	<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
	<script type="text/javascript">
		function checkForm(thisForm)
		{//check form data for valid info
			if(empty(thisForm.CategoryName,"Please Enter Category Name")){return false;}
			return true;//if all is passed, submit!
		}
	</script>';
	
	get_header();
	echo '<h3 align="center">' . smartTitle() . '</h3>
	<h4 align="center">Add Category</h4>
	<form action="' . THIS_PAGE . '" method="post" onsubmit="return checkForm(this);">
	<table align="center">
	   <tr><td align="right">Category Name</td>
		   	<td>
		   		<input type="text" name="CategoryName" />
		   		<font color="red"><b>*</b></font> <em>(alphanumerics & punctuation)</em>
		   	</td>
	   </tr>
	   <input type="hidden" name="act" value="insert" />
	   <tr>
	   		<td align="center" colspan="2">
	   			<input type="submit" value="Add Category!"><em>(<font color="red"><b>*</b> required field</font>)</em>
	   		</td>
	   </tr>
	</table>    
	</form>
	<div align="center"><a href="' . THIS_PAGE . '">Exit Without Add</a></div>
	';
	get_footer();
	
}

function insertExecute()
{
	$iConn = IDB::conn();//must have DB as variable to pass to mysqli_real_escape() via iformReq()
	
	$redirect = THIS_PAGE; //global var used for following formReq redirection on failure

	$CategoryName = strip_tags(iformReq('CategoryName',$iConn));
		
	
    //build string for SQL insert with replacement vars, %s for string, %d for digits 
    $sql = "INSERT INTO " . PREFIX . "FeedCategories (CategoryName) VALUES ('%s')"; 

    # sprintf() allows us to filter (parameterize) form data 
	$sql = sprintf($sql,$CategoryName);

	@mysqli_query($iConn,$sql) or die(trigger_error(mysqli_error($iConn), E_USER_ERROR));
	#feedback success or failure of update
	if (mysqli_affected_rows($iConn) > 0)
	{//success!  provide feedback, chance to change another!
		feedback("Category Added Successfully!","notice");
	}else{//Problem!  Provide feedback!
		feedback("Category NOT added!");
	}
	myRedirect(THIS_PAGE);
}


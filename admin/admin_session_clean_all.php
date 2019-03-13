<?php
/**
 *************************************************************************
 *************************************************************************
 *
 *  admin_session_clean_all.php
 *
 *************************************************************************
 *************************************************************************
 *
 * https://kiseharrington.com/wn19/news/.php is a module to unset all newsfeed sessions
 * 
 * It is access from an a href link and returns a success message when executed.
 *
 * @package Cottlets website
 * @subpackage News feed
 * @author itc250 winter 2019 project team
 * @author Thom Harrington
 * @author Junqiao Mou(Derrick)
 * @author Elizabeth Jones
 * @author Fikirte Mulugeta
 * @version 1.0 March 12, 2019
 * @link https://d.zjwda.org/news/feed.php?$topid=1
 * @link https://kiseharrington.com/wn19/news/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */
require '../news/inc_news/config_news.php'; #provides configuration, pathing, error handling, db credentials
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials

unset($_SESSION['NewsFeeds']);
$_SESSION['clearSessionStatus'] = 'News Feed Sessions Cleared';
header('Location:' . ADMIN_PATH . 'admin_dashboard.php');

?>
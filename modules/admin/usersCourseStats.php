<?php

/*===========================================================================
*   Open eClass 2.1
*   E-learning and Course Management System
* ===========================================================================
*	Copyright(c) 2003-2008  Greek Universities Network - GUnet
*	A full copyright notice can be read in "/info/copyright.txt".
*
*  	Authors:	Costas Tsibanis <k.tsibanis@noc.uoa.gr>
*				Yannis Exidaridis <jexi@noc.uoa.gr>
*				Alexandros Diamantidis <adia@noc.uoa.gr>
*
*	For a full list of contributors, see "credits.txt".
*
*	This program is a free software under the terms of the GNU
*	(General Public License) as published by the Free Software
*	Foundation. See the GNU License for more details.
*	The full license can be read in "license.txt".
*
*	Contact address: 	GUnet Asynchronous Teleteaching Group,
*						Network Operations Center, University of Athens,
*						Panepistimiopolis Ilissia, 15784, Athens, Greece
*						eMail: eclassadmin@gunet.gr
============================================================================*/


/*
===========================================================================
    admin/usersCourseStats.php
    @last update: 23-09-2006
    @authors list: ophelia neofytou
==============================================================================
    @Description: Shows chart with the number of users per course.

==============================================================================
*/

$require_admin = TRUE;
$require_help = true;
$helpTopic = 'Usage';

include '../../include/baseTheme.php';

// Define $nameTools
$nameTools = $langUsersCourse;
$navigation[] = array("url" => "index.php", "name" => $langAdmin);

$tool_content = '';
$tool_content .= "
  <div id=\"operations_container\">
    <ul id=\"opslist\">
      <li><a href='stateclass.php'>".$langPlatformGenStats."</a></li>
      <li><a href='platformStats.php?first='>".$langVisitsStats."</a></li>
      <li><a href='usersCourseStats.php'>".$langUsersCourse."</a></li>
      <li><a href='visitsCourseStats.php?first='>".$langVisitsCourseStats."</a></li>
      <li><a href='oldStats.php'>".$langOldStats."</a></li>
      <li><a href='monthlyReport.php'>".$langMonthlyReport."</a>></li>
    </ul>
  </div>";


include('../../include/jscalendar/calendar.php');
if ($language == 'greek') {
    $lang = 'el';
} else if ($language == 'english') {
    $lang = 'en';
}

   if (!extension_loaded('gd')) {
        $tool_content .= "<p>$langGDRequired</p>";
    } else {
        $made_chart = true;

        //make chart
        require_once '../../include/libchart/libchart.php';
        $query = "SELECT cours.intitule AS name, count(user_id) AS cnt FROM cours_user LEFT JOIN cours ON ".
            " cours.code = cours_user.code_cours GROUP BY code_cours";

        $result = db_query($query, $mysqlMainDb);
        $chart = new VerticalChart(200, 300);
        while ($row = mysql_fetch_assoc($result)) {
              $chart->addPoint(new Point($row['name'], $row['cnt']));
              $chart->width += 25;
        }
       $chart->setTitle("$langUsersCourse");

       mysql_free_result($result);
       $chart_path = 'temp/chart_'.md5(serialize($chart)).'.png';

        $chart->render($webDir.$chart_path);

        $tool_content .= '<img src="'.$urlServer.$chart_path.'" />';

    }

draw($tool_content, 3, 'admin');
/*
if ($made_chart) {
		while (ob_get_level() > 0) {
  	   ob_end_flush();
  	}
    ob_flush();
    flush();
    sleep(5);
    unlink ($webDir.$chart_path);
}
*/
?>

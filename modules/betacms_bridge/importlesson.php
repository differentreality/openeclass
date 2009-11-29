<?php
/*========================================================================
*   Open eClass 2.1
*   E-learning and Course Management System
* ========================================================================
*  Copyright(c) 2003-2009  Greek Universities Network - GUnet
*  A full copyright notice can be read in "/info/copyright.txt".
*
*  Developers Group:	Costas Tsibanis <k.tsibanis@noc.uoa.gr>
*			Yannis Exidaridis <jexi@noc.uoa.gr>
*			Alexandros Diamantidis <adia@noc.uoa.gr>
*			Tilemachos Raptis <traptis@noc.uoa.gr>
*
*  For a full list of contributors, see "credits.txt".
*
*  Open eClass is an open platform distributed in the hope that it will
*  be useful (without any warranty), under the terms of the GNU (General
*  Public License) as published by the Free Software Foundation.
*  The full license can be read in "/info/license/license_gpl.txt".
*
*  Contact address: 	GUnet Asynchronous eLearning Group,
*  			Network Operations Center, University of Athens,
*  			Panepistimiopolis Ilissia, 15784, Athens, Greece
*  			eMail: info@openeclass.org
* =========================================================================*/
/*===========================================================================
	import.php
	@last update: 28-11-2009 by Thanos Kyritsis
	@authors list: Thanos Kyritsis <atkyritsis@upnet.gr>
==============================================================================
    @Description: 

    @Comments:
==============================================================================
*/
$require_admin = TRUE;
require_once("../../include/baseTheme.php");
require_once("../admin/admin.inc.php");
$nameTools = $langBrowseBCMSRepo;
$navigation[] = array("url" => "../admin/index.php", "name" => $langAdmin);
$tool_content = "";

require_once("include/bcms.inc.php");
session_start();

if (isset($_GET['id']) && isset($_SESSION[BETACMSREPO])) {
	$repo = $_SESSION[BETACMSREPO];
	$coId = $_GET['id'];
	
	$co = getLesson($repo, $coId);
	
	destroyContentObjectInSession();
	putContentObjectInSession($co);
	
	// redirect to create course
	$tool_content .= "Please proceed to create course module to import the Lesson. If your browser doesn't 
		automatically redirect you, press <a href='../create_course/create_course.php'>here...</a>";
}
else {
	$tool_content .= "<p class=\"caution_small\">$langEmptyFields</p>
			<br/><br/><p align=\"right\"><a href='browserepo.php'>$langAgain</a></p>";
}

draw($tool_content,3);


// HELPER FUNCTIONS

function destroyContentObjectInSession() {
	// an yparxei hdh apo prin, sbhsto
	unset($_SESSION[IMPORT_FLAG]);
	unset($_SESSION[IMPORT_INTITULE]);
	unset($_SESSION[IMPORT_DESCRIPTION]);
	unset($_SESSION[IMPORT_COURSE_KEYWORDS]);
	unset($_SESSION[IMPORT_COURSE_ADDON]);
	
	return;
}

function putContentObjectInSession($obj) {
	$_SESSION[IMPORT_FLAG] = true;
	$_SESSION[IMPORT_ID] = $obj[KEY_ID];
	$_SESSION[IMPORT_INTITULE] = $obj[KEY_TITLE];
	$_SESSION[IMPORT_DESCRIPTION] = $obj[KEY_DESCRIPTION];
	$_SESSION[IMPORT_COURSE_KEYWORDS] = $obj[KEY_KEYWORDS];
	$_SESSION[IMPORT_COURSE_ADDON] = "Copyright: " .$obj[KEY_COPYRIGHT] ." "
		."Authors: " .$obj[KEY_AUTHORS] ." "
		."Project: " .$obj[KEY_PROJECT] ." "
		."Comments: " .$obj[KEY_COMMENTS];
	
	return;
}
?>
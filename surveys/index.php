<?php

# '../' works for a sub-folder.  use './' for the root
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials

$config->titleTag = smartTitle(); #Fills <title> tag. If left empty will fallback to $config->titleTag in config_inc.php
$config->metaDescription = smartTitle() . ' - ' . $config->metaDescription;  
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

# SQL statement - PREFIX is optional way to distinguish your app
$sql = "select CONCAT(a.FirstName, ' ', a.LastName) AdminName, s.SurveyID, s.Title, s.Description, 
date_format(s.DateAdded, '%W %D %M %Y %H:%i') 'DateAdded' from "
. PREFIX . "surveys s, " . PREFIX . "Admin a where s.AdminID=a.AdminID order by s.DateAdded desc
";

//END CONFIG AREA ---------------------------------------------------------- 

get_header(); #defaults to header_inc.php
?>
<h3 align="center"><?php echo $config->titleTag; ?></h3>
<p>This page displays results from the wn18_surveys table</p>
<?php
$db = pdo(); # pdo() creates and returns a PDO object

#$result stores data object in memory
try {$result = $db->query($sql);} catch(PDOException $ex) {trigger_error($ex->getMessage(), E_USER_ERROR);}

echo '<table class="table table-hover">
	<thead><tr><th>Date Created</th><th>Title</th><th>Createor\'s Name</th></tr></thead>
	<tbody>
';
if($result->rowCount() > 0)
{#there are records - present data
	while($row = $result->fetch(PDO::FETCH_ASSOC))
	{# pull data from associative array
	   echo '<tr class="table-danger">';
	   echo '<td>' . $row['DateAdded'] . '</td>';
	   echo '<td><a href="survey_view.php?id='.$row['SurveyID'].'">' . $row['Title'] . '</a></td>';
	   echo '<td>' . $row['AdminName'] . '</td>';
	   echo '</tr>';
	}
}else{#no records
	echo '<div align="center">Sorry, there are no records that match this query</div>';
}
unset($result,$db);//clear resources
echo '</tbody></table>';
get_footer(); #defaults to footer_inc.php
?>

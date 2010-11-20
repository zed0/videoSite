<?php
include 'mysql.php';
$errors = array();
$video_id = $_GET['video'];
$mysql = mysql_connect('localhost', $mysql_user, $mysql_password);
if(!$mysql){
	array_push($errors, mysql_error());
}
else
{
	if(!mysql_select_db($mysql_database))
	{
		array_push($errors, mysql_error());
	}
	else
	{
		$result = mysql_query("SELECT * FROM videos WHERE id='".$video_id."'");
		if(!$result)
		{
			array_push($errors, mysql_error());
		}
		else
		{
			if(mysql_num_rows($result) != 1)
			{
				array_push($errors, "Incorrect number of rows returned (".mysql_num_rows($result).")");
			}
			else
			{
				$row = mysql_fetch_assoc($result);
				$video_title = $row['videoName'];
				$video_location = $row['location'];
				$video_rating = $row['rating'];
			}
			mysql_free_result($result);
		}
	}
}
if(!empty($errors))
{
	echo "<p>There were errors with your request:</p>";
	echo "<ul class='errors'>";
	foreach($errors as $error)
	{
		echo "<li>".$error."</li>";
	}
	echo "</ul>";
}

include 'header.php';
if($video_id)
{
?>
	<video id="mainvideo" src="videos/<?php echo $video_location?>" controls autobuffer>
		Your browser does not support html5 video, you should get a better one.
	</video>
<?php
}
else
{
	echo "No video selected.";
}
include 'footer.php';

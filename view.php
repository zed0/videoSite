<?php
$video=$_GET['video'];

include 'header.php';
if($video)
{
?>
	<video id="mainvideo" src="videos/<?php echo $video?>" controls autobuffer>
		Your browser does not support html5 video, you should get a better one.
	</video>
<?php
}
else
{
	echo "No video selected.";
}
include 'footer.php';

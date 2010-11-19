<?php
$file_limit=100000000;
if(!empty($_FILES))
{
	$errors = array();
	if($_FILES['uploadedfile']['size'] > $file_limit)
	{
		array_push($errors, "File too large");
	}
	if((substr($_FILES['uploadedfile']['type'], 0, 6) != 'video/')
	&& (substr($_FILES['uploadedfile']['type'], 0, 6) != 'audio/'))
	{
		array_push($errors, "Incorrect file type: ".$_FILES['uploadedfile']['type']);
	}
	if(empty($errors))
	{
		$target_dir = "videos/";
		$target_path = $target_dir.basename($_FILES['uploadedfile']['name']);
		if(file_exists($target_path))
		{
			array_push($errors, "A file of that name already exists");
		}
		elseif(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path))
		{
			ob_implicit_flush(true);
			ob_end_flush();
			chmod($target_path, 0755);
			echo "The file ".basename($_FILES['uploadedfile']['name'])." has been uploaded.<br />";
			echo "File type: ".$_FILES['uploadedfile']['type'].".<br />";
			echo "Converting...<br />";
			$info = pathinfo($target_path);
			$output = "";
			system("ffmpeg -i ".$info['dirname']."/".$info['basename']." -acodec vorbis ".$info['dirname']."/".$info['filename'].".ogg", $output);
			echo "<br />Output:<br />".$output;
			unlink($target_path);
			chmod($info['dirname']."/".$info['filename'].".ogg", 0755);
			if($ouput = 0)
			{
				echo "Your video is now available <a href='./view.php?video=".$info['filename'].".ogg' >here.</a>";
			}
			else
			{
				array_push($errors, "There was a problem converting the file to ogg vorbis.");
				unlink($info['dirname']."/".$info['filename'].".ogg");
			}
		}
		else
		{
			array_push($errors, "Error moving the file, please try again.");
		}
	}
	if(!empty($errors))
	{
		echo "<p>There were errors with your upload:</p>";
		echo "<ul class='errors'>";
		foreach($errors as $error)
		{
			echo "<li>".$error."</li>";
		}
		echo "</ul>";
	}
}
include 'header.php';
?>
		<form enctype="multipart/form-data" action="upload.php" method="POST">
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $file_limit?>" />
			Choose a file to upload: <input name="uploadedfile" type="file" />
			<input type="submit" value="Upload File" />
		</form>
<?php
include 'footer.php';

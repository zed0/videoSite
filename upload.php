<?php
$file_limit=100000000;
if(!empty($_FILES))
{
	$errors = array();
	if($_FILES['file']['size'] > $file_limit)
	{
		array_push($errors, "File too large");
	}
	if(substr($_FILES['file']['type'], 0, 6) != 'video/')
	{
		array_push($errors, "Incorrect file type");
	}
	if(empty($errors))
	{
		$target_path = "videos/";
		$target_path = $target_path.basename($_FILES['uploadedfile']['name']);
		if(file_exists($target_path))
		{
			array_push($errors, "A file of that name already exists");
		}
		elseif(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path))
		{
			chmod($target_path, 0755);
			echo "The file ".basename($_FILES['uploadedfile']['name'])." has been uploaded.";
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

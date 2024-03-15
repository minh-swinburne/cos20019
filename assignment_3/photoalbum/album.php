<?php
/**
* 	Showing all photos in DB
*
*	@author Swinburne University of Technology
*/
ini_set('display_errors', 1);
require 'mydb.php';
require_once dirname(dirname(__FILE__)).'/aws/aws-autoloader.php';
require_once 'constants.php';

function getMetadata($path) {
	$url = "http://169.254.169.254/latest/meta-data/" . $path;
	return file_get_contents($url);
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="stylesheet" href="defaultstyle.css">
		<title>Photo Album</title>
	</head>
	<body>
		<h4>Student name: <?php echo STUDENT_NAME; ?></h4>
		<h4>Student ID: <?php echo STUDENT_ID; ?></h4>
		<h4>Tutorial session: <?php echo TUTORIAL_SESSION; ?></h4>
		<h4>Instance ID: <?php echo getMetadata("instance-id"); ?>; Availability Zone: <?php echo getMetadata("placement/availability-zone"); ?></h4><br>
		<h3>Uploaded photos:</h3>
		<a href="photouploader.php">Upload more photos</a><br><br>
		<table id="photo_table" border = "1">
		  <tr>
				<th>Photo</th>
				<th>Name</th> 
				<th>Description</th>
				<th>Creation date</th>
				<th>Keywords</th>
				<th>Thumbnail</th>
		  </tr>
		<?php 
		$my_db = new MyDB();
		$photos = $my_db->getAllPhotos();
		foreach ($photos as $photo) {
			$link = $photo->getS3Reference();
			$file_name = pathinfo($link)['basename'];
			$thumbnail_link = str_replace($file_name, "resized-".$file_name, $link);
			echo "<tr>
				<td><img class = 'photo_cell' src='".$link."'></td>
				<td>".$photo->getName()."</td>
				<td>".$photo->getDescription()."</td>
				<td>".$photo->getCreationDate()."</td>
				<td>".$photo->getKeywords()."</td>
				<td><img class = 'photo_cell' src='".$thumbnail_link."'></td>
			</tr>";
		}
		?>
		</table>
	</body>
</html>
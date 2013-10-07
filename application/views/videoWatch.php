<?PHP include 'includes/toppage.php';?>
	<h3><?php echo $video['title'] ?></h3>
	<video width="100%" controls>
  		<source src="<?PHP echo baseUrl('data/uploads/'.$video['fileName']) ?>" type="video/mp4">
		Your browser does not support the video tag.
	</video> 
<?PHP include 'includes/bottompage.php';?>
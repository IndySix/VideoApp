<?PHP include 'includes/toppage.php';?>
<h1>videos</h2>
	<br>
<?php foreach ($videos as $video): ?>
	<div class="videoCap">
		<h3><?php echo $video['title'] ?></h3>
		<a href="<?PHP echo baseUrl('video/watch/'.$video['id']) ?>">
			<img src="<?PHP echo baseUrl('/data/uploads/screencap/'.$video['fileName'].'.png'); ?>">
		</a>
	</div>
<?php endforeach; ?>
<?PHP include 'includes/bottompage.php';?>
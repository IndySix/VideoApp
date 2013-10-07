<?PHP include 'includes/toppage.php';?>

<h1>Upload video</h1>
<?PHP echo $message ?><br><br>
<form action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="authtoken" value="<?PHP echo $this->secure->authToken() ?>" />
	<input type="hidden" name="username" value="<?PHP echo $username ?>" />

	<label>Title:</label>
	<input type="text" name="title" value="<?PHP echo $title ?>"><br/>
	<?PHP echo $title_error ?><br>
	
	<label for="file">Filename:</label>
	<input type="file" name="file" ><br>
	<?PHP echo $file_error ?><br>


	<input type="submit" name="submit" value="Upload">
</form>
<?PHP include 'includes/bottompage.php';?>
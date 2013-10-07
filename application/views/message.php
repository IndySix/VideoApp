<?PHP include 'includes/toppage.php';?>

<?PHP if(isset($titleMessage)): //Display title when set ?>
	<h1><?PHP echo $titleMessage ?></h1>
<?PHP endif; ?>

<?PHP if(isset($message)): //Display message when set ?>
	<p class="message"><?PHP echo $message ?></p>
<?PHP endif; ?>

<?PHP if(isset($error)): //Display error when set ?>
	<p class="error"><?PHP echo $error ?></p>
<?PHP endif; ?>

<?PHP if(isset($warning)): //Display warning when set?>
	<p class="warning"><?PHP echo $warning ?></p>
<?PHP endif; ?>

<?PHP include 'includes/bottompage.php';?>
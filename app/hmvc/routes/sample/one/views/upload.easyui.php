<!DOCTYPE html>
<html>
<head>
	<title>Sample One</title>
</head>
<body>

<style type="text/css">
.form-group {
	padding: 9px 4px;	
} 
</style>

<div style="display: flex; justify-content: center; padding: 20px;">
	
	<form action="" method="post" enctype="multipart/form-data">
		<div style="display: flex; flex-direction: column;">
			<div class="form-group">
				<input type="hidden" name="event" value="upload" />
			</div>
			<div class="form-group">
				<input type="file" name="upload" />
			</div>
			<div class="form-group">
				<input type="submit" value="Upload"/>
			</div>
		</div>
	</form>

</div>

</body>
</html>
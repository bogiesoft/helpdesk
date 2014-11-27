<?php session_start();?>
<?php
	// load functions
	include_once '../includes/functions.php';
?>
<form action="<?=htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data" id="search" class="searchform">
<fieldset>
		<label for="term">Look For</label>
		<input type="text" id="term" name="term" value=""  required />
</fieldset>
<p class="buttons">
	<button name="submit" value="submit" type="submit">Search</button>
</p>
</form>

<div id="resultspost" style="clear: both;">
</div>

<script type="text/javascript">
    $('.searchform').submit(function(e) {
    	$.ajax(
			{
				type: 'post',
				url: 'includes/searchpost.php',
				data: $(this).serialize(),
				beforeSend: function()
				{
				$('#resultspost').html('<img src="/images/spinny.gif" alt="loading" class="loading"/>');
    			},
				success: function(data)
				{
				$('#resultspost').html(data);
    			},
				error: function()
				{
				$('#resultspost').html('error loading data, please refresh.');
    			}
			});
       e.preventDefault();
       return false;
    }); 
</script>
<script src="javascript/jquery.validate.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		$("#search").validate({
			rules: {}
		});
</script>

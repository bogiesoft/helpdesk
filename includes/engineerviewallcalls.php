<?php session_start();
	include_once 'functions.php';
?>
<div id="ajaxforms">
<p>Showing not only your calls, all open helpdesk calls for
	<?
	if ($_SESSION['engineerHelpdesk'] <= '3') { ?>
	<?=helpdesk_friendlyname(1)?>, <?=helpdesk_friendlyname(2)?>, <?=helpdesk_friendlyname(3)?>.
	<? } else { ?>
	<?=helpdesk_friendlyname($_SESSION['engineerHelpdesk'])?>
	<? } ?> included.</p>
	<table>
	<tbody>
	<?php
		//run select query
		// if IT show all av and webdev calls also
			if ($_SESSION['engineerHelpdesk'] <= '3') {
				$whereenginners = 'WHERE helpdesk <= 3';
			} else {
				$whereenginners = 'WHERE helpdesk='.$_SESSION['engineerHelpdesk'];
			};
		$result = mysqli_query($db, "SELECT * FROM calls " .$whereenginners. " AND status='1';");
		if (mysqli_num_rows($result) == 0) { echo "<p>Helpdesk clear!</p>";};
		while($calls = mysqli_fetch_array($result))  {
		?>
		<tr>
		<td>#<?=$calls['callid'];?></td>
		<td><?=date("d/m/y", strtotime($calls['opened']));?></td>
		<td class="view_td"><?=substr(strip_tags($calls['details']), 0, 120);?>...</td>
		<td>
			<form action="<?=$_SERVER['PHP_SELF']?>" method="post" class="assignedtoyou">
				<input type="hidden" id="id" name="id" value="<?=$calls['callid'];?>" />
				<input type="image" name="submit" value="submit" src="/images/ICONS-view@2x.png" width="24" height="25" class="icon" alt="View Call" />
			</form>
		</td>


		</tr>
	<? } ?>
	</tbody>
	</table>
</div>


	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js" type="text/javascript"></script>
	<script src="javascript/jquery.js" type="text/javascript"></script>
	<script type="text/javascript">
    $('.assignedtoyou').submit(function(e) {
    	$.ajax(
			{
				type: 'post',
				url: '/viewcallpost.php',
				data: $(this).serialize(),
				beforeSend: function()
				{
				$('#ajax').html('<img src="/images/spinny.gif" alt="loading" class="loading"/>');
    			},
				success: function(data)
				{
				$('#ajax').html(data);
    			},
				error: function()
				{
				$('#ajax').html('error loading data, please refresh.');
    			}
			});
       e.preventDefault();
       return false;
    });
    </script>
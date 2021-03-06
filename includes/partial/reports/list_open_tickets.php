<h2>Open Tickets</h2>
<?php
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php');
?>

<div id="ajaxforms">
	<table>
	<tbody>
	<?php
		if ($_SESSION['engineerHelpdesk'] <= '3') {
			$STH = $DBH->Prepare("SELECT * FROM calls INNER JOIN engineers ON calls.assigned=engineers.idengineers INNER JOIN status ON calls.status=status.id WHERE engineers.helpdesk <= :helpdeskid AND status='1' ORDER BY callID");
			$hdid = 3;
		} else {
			$STH = $DBH->Prepare("SELECT * FROM calls INNER JOIN engineers ON calls.assigned=engineers.idengineers INNER JOIN status ON calls.status=status.id WHERE engineers.helpdesk = :helpdeskid AND status='1' ORDER BY callID");
			$hdid = $_SESSION['engineerHelpdesk'];
		}
		$STH->bindParam(":helpdeskid", $hdid, PDO::PARAM_STR);
		$STH->setFetchMode(PDO::FETCH_OBJ);
		$STH->execute();
		if ($STH->rowCount() == 0) { echo "<p>All calls Closed</p>";};
		while($row = $STH->fetch()) {
		?>
		<tr>
		<!--<td>#<?=$row->callid;?></td>-->
		<td width="45">
		<?php
		$datetime1 = new DateTime(date("Y-m-d", strtotime($row->opened)));
		$datetime2 = new DateTime(date("Y-m-d"));
		$interval = date_diff($datetime1, $datetime2);
		echo $interval->format('%a days');
		?>
		</td>
		<td>
			<form action="<?=$_SERVER['PHP_SELF']?>" method="post" class="viewpost">
				<input type="hidden" id="id" name="id" value="<?=$row->callid;?>" />
				<button name="submit" value="submit" type="submit" class="calllistbutton" title="view call"><?=substr(strip_tags($row->title), 0, 65);?>...</button>
			</form>
		</td>
		<td>
			<?=strstr($row->engineerName," ", true);?>
		</td>
		<td>
			<form action="<?=$_SERVER['PHP_SELF']?>" method="post" class="forward">
			<input type="hidden" id="id" name="id" value="<?=$row->callid;?>" />
			<input name="submit" value="" type="image" src="/public/images/ICONS-forward@2x.png" width="24" height="25" class="icon" alt="forward ticket"  title="forward ticket"/>
			</form>
		</td>
		<td>
			<form action="<?=$_SERVER['PHP_SELF']?>" method="post" class="reassign">
				<input type="hidden" id="id" name="id" value="<?=$row-callid;?>" />
				<input name="submit" value="" type="image" src="/public/images/ICONS-assign@2x.png" width="24" height="25" class="icon" alt="assign engineer"  title="assign engineer" />
			</form>
		</td>
		</tr>
	<? } ?>
	</tbody>
	</table>
</div>
	<script type="text/javascript">
     $('.viewpost').submit(function(e) {
    	$.ajax(
			{
				type: 'post',
				url: '/includes/partial/form/view_ticket.php',
				data: $(this).serialize(),
				beforeSend: function()
				{
				$('#ajax').html('<img src="/public/images/ICONS-spinny.gif" alt="loading" class="loading"/>');
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

	$('.reassign').submit(function(e) {
    	$.ajax(
			{
				type: 'post',
				url: '/includes/partial/post/reassign_ticket.php',
				data: $(this).serialize(),
				beforeSend: function()
				{
				$('#ajax').html('<img src="/public/images/ICONS-spinny.gif" alt="loading" class="loading"/>');
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

  	$('.forward').submit(function(e) {
    	$.ajax(
			{
				type: 'post',
				url: '/includes/partial/post/forward_ticket.php',
				data: $(this).serialize(),
				beforeSend: function()
				{
				$('#ajax').html('<img src="/public/images/ICONS-spinny.gif" alt="loading" class="loading"/>');
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

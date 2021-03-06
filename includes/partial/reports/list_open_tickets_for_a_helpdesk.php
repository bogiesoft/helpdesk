<?php
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php');
?>
<div id="ajaxforms">
<p class="note">Showing all open tickets for
	<?php if ($_SESSION['engineerHelpdesk'] <= '3') { ?>
	<?=helpdesk_friendlyname(1)?>, <?=helpdesk_friendlyname(2)?>, <?=helpdesk_friendlyname(3)?>.
	<?php } else { ?>
	<?=helpdesk_friendlyname($_SESSION['engineerHelpdesk'])?>
	<?php } ?></p>
	<table>
	<tbody>
	<?php
		if ($_SESSION['engineerHelpdesk'] <= '3') {
			$STH = $DBH->Prepare("SELECT * FROM calls WHERE helpdesk <= :helpdeskid AND status='1'");
			$hdid = 3;
		} else {
			$STH = $DBH->Prepare("SELECT * FROM calls WHERE helpdesk = :helpdeskid AND status='1'");
			$hdid = $_SESSION['engineerHelpdesk'];
		};
		$STH->bindParam(":helpdeskid", $hdid, PDO::PARAM_STR);
		$STH->setFetchMode(PDO::FETCH_OBJ);
		$STH->execute();
		if ($STH->rowCount() == 0) { echo "<p>0 open calls.</p>";};
		while($row = $STH->fetch()) {
		?>
		<tr>
		<td>#<?=$row->callid;?></td>
		<td><?=date("d/m/y", strtotime($row->opened));?></td>
		<td class="view_td"><?=substr(strip_tags($row->title), 0, 90);?>...</td>
		<td>
			<form action="<?=$_SERVER['PHP_SELF']?>" method="post" class="assignedtoyou">
				<input type="hidden" id="id" name="id" value="<?=$row->callid;?>" />
				<input type="image" name="submit" value="submit" src="/public/images/ICONS-view@2x.png" width="24" height="25" class="icon" alt="View ticket"  title="View ticket"/>
			</form>
		</td>
		</tr>
	<? } ?>
	</tbody>
	</table>
</div>
<script type="text/javascript">
    $('.assignedtoyou').submit(function(e) {
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
    </script>
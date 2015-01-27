<h2>Open</h2>
<?php session_start();?>
<?php
	// load functions
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
?>
<div id="ajaxforms">
	<table>
	<thead>
		<tr>
			<td>Age</td>
			<td>Engineer</td>
			<td>Details</td>
		</tr>
	</thead>		
	<tbody>
	<?php
		//run select query
		$result = mysqli_query($db, "SELECT * FROM calls INNER JOIN engineers ON calls.assigned=engineers.idengineers INNER JOIN status ON calls.status=status.id WHERE engineers.helpdesk <= 3 AND status='1' ORDER BY callID;");
		if (mysqli_num_rows($result) == 0) { echo "<p>0 Open Calls</p>";};
		while($calls = mysqli_fetch_array($result))  {
		?>
		<tr>
		<td width="45">
		<?php
		$datetime1 = new DateTime(date("Y-m-d", strtotime($calls['opened'])));
		$datetime2 = new DateTime(date("Y-m-d"));
		$interval = date_diff($datetime1, $datetime2);
		echo $interval->format('%a days');
		?>
		</td>
		<td>
			<?=strstr($calls['engineerName']," ", true);?>
		</td>
		<td>
			<?=substr(strip_tags($calls['details']), 0, 300);?>...
		</td>
		</tr>
	<? } ?>
	</tbody>
	</table>
</div>
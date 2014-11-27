<?php
	session_start();
	include_once 'functions.php';

	$sqlstr = "SELECT * FROM calls INNER JOIN engineers ON calls.assigned=engineers.idengineers INNER JOIN status ON calls.status=status.id ";
	$sqlstr .= "WHERE details LIKE '%" . check_input($_POST['term']) . "%';";
	$result = mysqli_query($db, $sqlstr);	
?>
<h2>Search Results</h2>
<?php
	if (mysqli_num_rows($result) == 0) {
		echo "<h3>0 Returned</h3>";
	} else {
		echo "<h3>" . mysqli_num_rows($result) . " Returned</h3>";
	} ;
?>
<table>
	<tbody>	
<?php
	while($calls = mysqli_fetch_array($result))  {
?>
	<tr>
		<td>#<?=$calls['callid'];?></td>
		<td>		
		<? if ($calls['status'] ==='2') { 
			echo "<span class='closed'>Closed</span>";
			} else { 
			echo date("d/m/y", strtotime($calls['opened']));
			};
		?></td>
		<td>
			<form action="<?=$_SERVER['PHP_SELF']?>" method="post" class="searchresultsview">
				<input type="hidden" id="id" name="id" value="<?=$calls['callid'];?>" />
				<button name="submit" value="submit" type="submit" class="calllistbutton"><?=substr(strip_tags($calls['details']), 0, 40);?>...</button>
				<input type="image" name="submit" value="submit" src="/images/ICONS-view@2x.png" width="24" height="25" class="icon" alt="View Call" />
			</form>
		</td>
		<td><?=strstr($calls['engineerName']," ", true);?></td>
		</tr>
<?  }; ?>
	</tbody>
</table>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js" type="text/javascript"></script>	
	<script src="javascript/jquery.js" type="text/javascript"></script>
	<script type="text/javascript">
    // Ajax form submit
    $('.searchresultsview').submit(function(e) {
        // Post the form data to viewcall
        $.post('viewcallpost.php', $(this).serialize(), function(resp) {
            // return response data into div
            $('#ajax').html(resp);
        });
        // Cancel the actual form post so the page doesn't refresh
        e.preventDefault();
        return false;
    });     
    </script>



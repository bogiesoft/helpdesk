<?php
if ($_POST['report'] == "0") { include('managerreport-default.php');};
if ($_POST['report'] == "1") { echo "<h2>All Tickets</h2>"; include('managerreport-allcalls.php');};
if ($_POST['report'] == "2") { echo "<h2>Oldest Ticket</h2>"; include('managerreport-oldestcalls.php');};
if ($_POST['report'] == "3") { echo "<h2>Assigned Ticket Numbers</h2>"; include('managerreport-assigned.php');};
if ($_POST['report'] == "4") { echo "<h2>Workrate</h2>"; include('managerreport-workrate.php');};
if ($_POST['report'] == "5") { echo "<h2>User Feedback</h2>"; include('managerreport-feedback.php');};
if ($_POST['report'] == "6") { echo "<h2>Punchcard In/Out</h2>"; include('managerreport-punchcard.php');};
if ($_POST['report'] == "7") { echo "<h2>Emerging issues</h2>"; include('managerreport-issues.php');};
if ($_POST['report'] == "8") { echo ""; include('managerreport-search.php');};
if ($_POST['report'] == "9") { echo "<h2>Scheduled Tasks</h2>"; include('managerreport-tasks.php');};
if ($_POST['report'] == "10") { echo "<h2>Add Change Control</h2>"; include('managerreport-changecontrol.php');};
if ($_POST['report'] == "11") { echo "<h2>View Change History</h2>"; include('managerreport-changehistory.php');};
if ($_POST['report'] == "12") { echo "<h1>Tag Control</h1>"; include('managerreport-tags.php');};
if ($_POST['report'] == "13") { echo "<h2>Awaiting Invoice</h2>"; include('managerreport-awaitinginvoice.php');};
if ($_POST['report'] == "14") { die("<script>location.href = '/digitalsigns/index.php'</script>");};
if ($_POST['report'] == "15") { echo "<h2>Reason behind issues</h2>"; include('managerreport-reasonbehind.php');};
if ($_POST['report'] == "16") { echo "<h2>Lockers</h2>"; include('managerreport-lockers.php');};
if ($_POST['report'] == "17") { echo "<h2>Empty</h2>"; include('managerreport-blank.php');};
?>
<?php
//Database Connection
try {
	# PDO_MYSQL
	$DBH = new PDO("mysql:host=".DB_LOC.";dbname=".DB_SCHEMA, DB_USER, DB_PASSWORD);
	# Display development errors
	if (DEVELOPMENT_ENVIRONMENT === true) {
		$DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	}
}
catch(PDOException $e) {
	echo($e->getMessage());
}

// Classes
class ticket {
	public $name;
	public $email;
	public $tel;
	public $details;
	public $assigned;
	public $opened;
	public $lastupdate;
	public $status;
	public $closed;
	public $closeengineerid;
	public $urgency;
	public $location;
	public $room;
	public $category;
	public $owner;
	public $helpdesk;
	public $invoicedate;
	public $callreason;
	public $title;
	public $lockerid;

	function __construct($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n, $o, $p, $q, $r, $s, $t) {
		$this->name = $a;
		$this->email = $b;
		$this->tel = $c;
		$this->details = $d;
		$this->assigned = $e;
		$this->opened = $f;
		$this->lastupdate = $g;
		$this->status = $h;
		$this->closed = $i;
		$this->closeengineerid = $j;
		$this->urgency = $k;
		$this->location = $l;
		$this->room = $m;
		$this->category = $n;
		$this->owner = $o;
		$this->helpdesk = $p;
		$this->invoicedate = $q;
		$this->callreason = $r;
		$this->title = $s;
		$this->lockerid = $t;
	}
}
// Functions
function prompt_auth($data) {
	if (empty($_SESSION['sAMAccountName'])) {
		// User not logged in forward to login page
			die("<script>location.href = '/login/login.php?return=".$data."'</script>");
		};
}
function check_input($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
function last_engineer($data)
{
	// check helpdesk call is for
	$helpdeskid = $data;
	//returns details for last engineer assigned a call.
	$data = "";
	// find and join last engineer assigned
	global $DBH;
	$STH = $DBH->Prepare("SELECT * FROM assign_engineers INNER JOIN engineers ON assign_engineers.engineerid=engineers.idengineers WHERE id= :id");
	$STH->bindParam(":id", $helpdeskid, PDO::PARAM_STR);
	$STH->setFetchMode(PDO::FETCH_OBJ);
	$STH->execute();
	while($row = $STH->fetch()) {
		$data = $row->engineerId . " - " . $row->engineerName . " - " . $row->engineerEmail;
	}
	return $data;
}
function next_engineer($data)
{
	// Check helpdesk call is for
	$helpdeskid = $data;
	// Reset data and calculate next engineer
	$data = "";
	// find last engineer assigned
	global $DBH;
	$STH = $DBH->Prepare("SELECT * FROM assign_engineers INNER JOIN engineers ON assign_engineers.engineerid=engineers.idengineers WHERE id= :id");
	$STH->bindParam(":id", $helpdeskid, PDO::PARAM_INT);
	$STH->setFetchMode(PDO::FETCH_OBJ);
	$STH->execute();
	while($row = $STH->fetch()) {
		$lastengineerid = $row->engineerId;
		$engineername = $row->engineerName;
		$engineeremail = $row->engineerEmail;
	}
	// get next engineer id
	$STH = $DBH->Prepare("SELECT idengineers FROM engineers WHERE idengineers > :lastengineerid AND helpdesk = :id AND engineerLevel=1 ORDER BY idengineers LIMIT 1");
	$STH->bindParam(":lastengineerid", $lastengineerid, PDO::PARAM_INT);
	$STH->bindParam(":id", $helpdeskid, PDO::PARAM_INT);
	$STH->setFetchMode(PDO::FETCH_OBJ);
	$STH->execute();
	if ($STH->rowCount() == 0) {
		$STH = $DBH->Prepare("SELECT idengineers FROM engineers WHERE helpdesk= :id AND engineerLevel=1 LIMIT 1");
		$STH->bindParam(":id", $helpdeskid, PDO::PARAM_INT);
		$STH->setFetchMode(PDO::FETCH_OBJ);
		$STH->execute();
	}
	while($row = $STH->fetch()) {
		$nextid = $row->idengineers;
	}
	return $nextid;
}
function engineer_friendlyname($data)
{
	global $DBH;
	$STH = $DBH->Prepare("SELECT engineerName FROM engineers WHERE idengineers = :id");
	$STH->bindParam(":id", $data, PDO::PARAM_STR);
	$STH->setFetchMode(PDO::FETCH_OBJ);
	$STH->execute();
	while($row = $STH->fetch()) {
		$friendly = $row->engineerName;
	}
	return $friendly;
}
function helpdesk_friendlyname($data)
{
	global $DBH;
	$STH = $DBH->Prepare("SELECT helpdesk_name FROM helpdesks WHERE id = :id");
	$STH->bindParam(":id", $data, PDO::PARAM_STR);
	$STH->setFetchMode(PDO::FETCH_OBJ);
	$STH->execute();
	while($row = $STH->fetch()) {
		$friendly = $row->helpdesk_name;
	}
	return $friendly;
}
function random_locker()
{
	global $db;
		$min=1;
		$max=36;
		$lockerid = rand($min,$max);
	return $lockerid;
}
function callsinlastday($data) {
	// Call count for last day, need to make this helpdesk specific at some point
	global $DBH;
	$STH = $DBH->Prepare("SELECT COUNT(callID) AS count FROM calls WHERE opened >= DATE_SUB(CURDATE(),INTERVAL 1 DAY)");
	$STH->setFetchMode(PDO::FETCH_OBJ);
	$STH->execute();
	while($row = $STH->fetch()) {
		$data = $row->count;
	}
	return $data;
}
function callsclosedinlastday($data) {
	// Call count closed for last day, need to make this helpdesk specific at some point
	global $DBH;
	$STH = $DBH->Prepare("SELECT COUNT(callID) AS count FROM calls WHERE closed >= DATE_SUB(CURDATE(),INTERVAL 1 DAY)");
	$STH->setFetchMode(PDO::FETCH_OBJ);
	$STH->execute();
	while($row = $STH->fetch()) {
		$data = $row->count;
	}
	return $data;
}
function topengineer($data) {
	// Returns engineer friendly name who has closed most calls in last 7 days unless passed count then returns number, need to make this helpdesk specific at some point
	$what = $data;
	global $DBH;
	$STH = $DBH->Prepare("SELECT closeengineerid, engineerName, Count(closeengineerid) AS HowMany FROM calls INNER JOIN engineers ON calls.closeengineerid=engineers.idengineers WHERE closed >= DATE_SUB(CURDATE(),INTERVAL 7 DAY) GROUP BY closeengineerid order by HowMany");
	$STH->setFetchMode(PDO::FETCH_OBJ);
	$STH->execute();
	while($row = $STH->fetch()) {
		if((string)$what === "count") {
			$data = $row->HowMany;
		} else {
			$data = $row->engineerName;
		}
	}
	return $data;
}
function bottomengineer($data) {
	// Returns engineer friendly name who has closed least calls in last 7 days unless passed count then returns number, need to make this helpdesk specific at some point
	$what = $data;
	global $DBH;
	$STH = $DBH->Prepare("SELECT closeengineerid, engineerName, Count(closeengineerid) AS HowMany FROM calls INNER JOIN engineers ON calls.closeengineerid=engineers.idengineers WHERE closed >= DATE_SUB(CURDATE(),INTERVAL 7 DAY) GROUP BY closeengineerid order by HowMany DESC");
	$STH->setFetchMode(PDO::FETCH_OBJ);
	$STH->execute();
	while($row = $STH->fetch()) {
		if((string)$what === "count") {
			$data = $row->HowMany;
		} else {
			$data = $row->engineerName;
		}
	}
	return $data;
}
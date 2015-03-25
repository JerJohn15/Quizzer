<?php
include 'conf/db.conf';

$type = $_POST['type'];

// $type = 'fb';

if ($type == 'fb'){
	$qid = $_POST['qid'];
	$ans = $_POST['value'];

	// $quid = '2';
	// $ans = 'boston';

	if (isset($qid) && isset($ans)){
		$results = pg_query($pgh, "SELECT answer FROM quiz_questions WHERE qid='" . $qid . "' LIMIT 1");
		$resultArray = pg_fetch_all($results);
		for($x = 0; $x < count($resultArray); $x++) {
		    if (strpos(strtolower($ans), strtolower($resultArray[$x]['answer'])) !== false){
		    	echo "TRUE";
		    }else{
		    	echo "FALSE";
		    }
		}
	}
}else if ($type == "tf"){
	$qid = $_POST['qid'];
	$ans = $_POST['value'];

	if (isset($qid) && isset($ans)){
		$results = pg_query($pgh, "SELECT answer FROM quiz_questions WHERE qid='" . $qid . "' LIMIT 1");
		$resultArray = pg_fetch_all($results);
		for($x = 0; $x < count($resultArray); $x++) {
		    if ($ans == $resultArray[$x]['answer']){
		    	echo "TRUE";
		    }else{
		    	echo "FALSE";
		    }
		}
	}
}

?>
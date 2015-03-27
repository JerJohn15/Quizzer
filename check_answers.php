<?php
include 'conf/db.conf';

$data = $_POST['data'];

$jsonData = json_decode($data, true);

$date = new DateTime('NOW');
$dateString = $date->format('Y-m-d H:i:s');
$rtn = [];
$points_scored = 0;
$points_total = 0;
$quiz_name = "";
$user = $_SESSION["user"]; 
$userSQLString = "";

if ($user == null){
	$userSQLString = "NULL";
}else{
	$userSQLString = "'" . $user . "'";
}

for($x = 0; $x < count($jsonData); $x++){

	$qid = $jsonData["\"" + $x + "\""]["qid"];

	$results = pg_query($pgh, "SELECT points, answer, question_type, quiz_name FROM quiz_questions WHERE qid='" . $qid . "'");
	$resultArray = pg_fetch_all($results);

	$quiz_name = $resultArray[0]["quiz_name"];

	$correct = false; 

	if($resultArray[0]["question_type"] == "mc"){
		$aSplit = explode("\n", $resultArray[0]["answer"]);

		for($y = 0; $y < count($aSplit); $y++) {
			if (str_replace("*", "", $aSplit[$y]) == $jsonData["\"" + $x + "\""]["value"] && substr($aSplit[$y], -1, 1) == "*"){
				$correct = true;
			}
		}
	}else if($resultArray[0]["question_type"] == "fb"){
		if (strpos(strtolower($jsonData["\"" + $x + "\""]["value"]), strtolower($resultArray[0]['answer'])) !== false){
			$correct = true;
    	}
    }else if($resultArray[0]["question_type"] == "tf"){ 
    	if (strtolower($jsonData["\"" + $x + "\""]["value"]) == strtolower($resultArray[0]['answer'])){
    		$correct = true;
    	}
    }

    if($correct){
    	$results = pg_query($pgh, "INSERT INTO quiz_answers (qid, user_answer, points, date_taken) VALUES ('" . intval($qid) . "' , '" . $jsonData["\"" + $x + "\""]["value"] . "' , '" . intval($resultArray[0]["points"]) . "' , '" . $dateString . "')");
    	$rtn[$qid] = "true";
    	$points_scored = $points_scored + intval($resultArray[0]["points"]);
    } else {
    	$results = pg_query($pgh, "INSERT INTO quiz_answers (qid, user_answer, points, date_taken) VALUES ('" . intval($qid) . "' , '" . $jsonData["\"" + $x + "\""]["value"] . "' , '0' , '" . $dateString . "')");
    	$rtn[$qid] = "false";
    }

   	$points_total = $points_total + intval($resultArray[0]["points"]);
}

$results = pg_query($pgh, "INSERT INTO quiz_results (quiz_name, points_scored, points_total, user_id) VALUES ('" . $quiz_name . "' , '" . $points_scored . "' , '" . $points_total . "', " . $userSQLString . ")");
$rtn['score'] = "You scored " . $points_scored . "/" . $points_total . " points!";

// $rtn['other'] = "INSERT INTO quiz_results (quiz_name, points_scored, points_total, user_id) VALUES ('" . $quiz_name . "' , '" . $points_scored . "' , '" . $points_total . "', '" . $_SESSION["users"] . "')";

echo json_encode($rtn);
?>
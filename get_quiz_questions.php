<?php
include 'conf/db.conf';

$quiz = $_POST['quiz'];
echo "<h2>" . $quiz . "</h2>";
echo "<h3 id=\"score\"></h3>";

if(isset($quiz)){
	$results = pg_query($pgh, "SELECT points, question, answer, question_type, qid FROM quiz_questions WHERE quiz_name='testQuiz'");
	$resultArray = pg_fetch_all($results);

	$html = '';

	for($x = 0; $x < count($resultArray); $x++) {

	    $type = $resultArray[$x]['question_type'];
	    $points = $resultArray[$x]['points'];
	    $q = $resultArray[$x]['question'];
	    $a = $resultArray[$x]['answer']; 
	    $qid = $resultArray[$x]['qid']; 

		if ($type == 'mc')
		{
			$html .= "<div>";
			$html .= "<h4 qid=\"" . $qid . "\" name=\"mc" . $x . "\" id=\"mc\">" . $q . " (" . $points . " Points)</h4>";
			
			$aSplit = explode("\n", $a);

			for($y = 0; $y < count($aSplit); $y++) {
				if (str_replace(" ", "", $aSplit[$y]) != ""){
					$html .= "<input type=\"radio\" name=\"a" . $x . "\" value=\"" . str_replace("*","",$aSplit[$y]) . "\">" . str_replace("*","",$aSplit[$y]) . "<br>";
				}
			}
			$html .= "</div>";	
		} 
		else if($type == 'fb')
		{

			$html .= "<div>";
			$html .= "<h4 a=\"\"qid=\"" . $qid . "\" name=\"fb" . $x . "\" id=\"fb\">" . str_replace("_", "<input type=\"text\" name=\"a" . $x . "\">", $q) . " (" . $points . " Points)</h4>";
			$html .= "</div>";
		}
		else if ($type == 'tf')
		{
			$html .= "<div>";
			$html .="<h4 qid=\"" . $qid . "\" name=\"tf" . $x . "\" id=\"tf\">" . $q . " (" . $points . " Points)</h4>";
			$html .= "<input type=\"radio\" name=\"a" . $x . "\" value=\"true\">TRUE";
			$html .= "<br>";
			$html .= "<input type=\"radio\" name=\"a" . $x . "\" value=\"false\">FALSE";
			$html .= "</div>";
		}
	}

	print "<br>" . $html;
}else{
	echo "NOTSET";
}

?>
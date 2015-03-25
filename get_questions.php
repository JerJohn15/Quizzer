<?php

$qtype 	= 	$_POST['type'];
$conut = $_POST['count'];

switch ($qtype) {
    case "mc":
        
        break;
    case "fb":
        
        break;
    case "tf":
        
        break;
}

function getMC()
{
	var q = '';

	q += "<tr><th name=\"mc" + $count + "\" id=\"mc\">Multiple Choice</th></tr>";
	q += "<tr><td>Question: </td><td><textarea name=\"q" + $count + "\"\"/></textarea></td></tr>";
	q += "<tr><td>Answers: </td><td><textarea name=\"choices" + $count + "\"></textarea></td></tr>";

	echo q;
}

?>
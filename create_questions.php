<?php

include 'conf/db.conf';

$action  =   $_POST['action'];

if($action == "get"){
    $qtype 	= 	$_POST['type'];
    $count = $_POST['count'];

    switch ($qtype) {
        case "mc":
            getMC($count);
            break;
        case "fb":
            getFB($count);
            break;
        case "tf":
            getTF($count);
            break;
    }
}else if($action == "set"){

    $name = $_POST["name"];
    $points = $_POST["points"];
    $question = $_POST["question"];
    $answer = $_POST["answer"];
    $type = $_POST["type"];

    if(isset($type) && isset($answer) && isset($question) && isset($points) && isset($name))
    {
        $results = pg_query($pgh, "INSERT INTO quiz_questions (quiz_name, points, question_type, question, answer) VALUES('".$_POST["name"]."','".$_POST["points"]."','".$_POST["type"]."','".$_POST["question"]."','".$_POST["answer"]."')");
    }
}

function getMC($count)
{
    $q = '';

    $q .= "<tr><th name=\"mc" . $count . "\" id=\"mc\">Multiple Choice</th></tr>";
    $q .= "<tr><td>Points: </td><td><input type=\"number\" min=\"0\" name=\"point" . $count . "\"></td></tr>";
    $q .= "<tr><td>Question Text: </td><td><textarea name=\"q" . $count . "\"/></textarea></td></tr>";
    $q .= "<tr><td>Answer Choices: </td><td><textarea name=\"choices" . $count . "\"></textarea></td></tr>";
 

    echo $q;
}

function getFB($count)
{
    $q = '';

    $q .= "<tr><th name=\"fb" . $count . "\" id=\"fb\">Fill in the Blank</th></tr>";
    $q .= "<tr><td>Points: </td><td><input type=\"number\" min=\"0\" name=\"point" . $count . "\"></td></tr>";
    $q .= "<tr><td>Question Text: </td><td><textarea name=\"q" . $count . "\"/></textarea></td></tr>";
    $q .= "<tr><td>Answer: </td><td><input type=\"text\" name=\"a" . $count . "\"></td></tr>";

    echo $q;
}

function getTF($count)
{
    $q = '';

    $q .= "<tr><th name=\"tf" . $count . "\" id=\"tf\">True/False</th></tr>";
    $q .= "<tr><td>Points: </td><td><input type=\"number\" min=\"0\" name=\"point" . $count . "\"></td></tr>";
    $q .= "<tr><td>Question: </td><td><textarea name=\"q" . $count . "\"\"/></textarea></td></tr>";
    $q .= "<tr><td>Answer: </td><td><input type=\"radio\" name=\"a" . $count . "\" value=\"true\">TRUE</td></tr>";
    $q .= "<tr><td></td><td><input type=\"radio\" name=\"a" . $count . "\" value=\"false\">FALSE</td></tr>";

    echo $q;
}

?>
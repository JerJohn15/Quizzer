<?php

$results = pg_query($pgh, "SELECT DISTINCT(quiz_name) from quiz_questions");
$resultArray = pg_fetch_all($results);

?>

<p><a href="create_quiz.html">Create Quiz</a></p>
<h3>Available Quizes</h3>

<div id="quiz">
What quiz whould you like to review?<br>
<select id="quizName">

<?php

  $results = pg_query($pgh, "SELECT DISTINCT(quiz_name) from quiz_questions");
  $resultArray = pg_fetch_all($results);
  for($x = 0; $x < count($resultArray); $x++) {
	echo "<option value=\"".$resultArray[$x]['quiz_name']."\">".$resultArray[$x]['quiz_name']."</option>";
   }
?>

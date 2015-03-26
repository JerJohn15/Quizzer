<?php
include 'conf/db.conf';
include 'header.php';
?>



<div id="quiz">
What quiz whould you like to take?<br>
<select id="quizName">



<?php
$results = pg_query($pgh, "SELECT DISTINCT(quiz_name) from quiz_questions");
$resultArray = pg_fetch_all($results);
for($x = 0; $x < count($resultArray); $x++) {
    echo "<option value=\"".$resultArray[$x]['quiz_name']."\">".$resultArray[$x]['quiz_name']."</option>";
}
?>


</select>
<br>
<input type="submit" value="Go" id="go">
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function()
	{
		$("#go").on("click",function(event){
			$.ajax({
				type: "POST",
				url: "get_quiz_questions.php",
				data: { 'quiz' : $("select[id=quizName]").val()},
				success: function(html) {
					$("#quiz").html(html);

					$("#quiz").append("<input type=\"submit\" value=\"Submit\" id=\"submit\">");

					$("#submit").on("click", function(event){
						checkAnswers();
					});
				}
			});
		})
	});

	function checkAnswers(){
		var counter = 0; 
		var dataString = {};


		$("font").remove();

		while(true){
			var qid = 0; 


			if ($("h4[name=mc" + counter + "]").attr('id') == 'mc')
			{	
				qid = $("h4[name=mc" + counter + "]").attr('qid');
				value = $("input[name=a" + counter + "]:checked").val();
				if(value==null)
				{
					// $("h4[name=mc" + counter + "]").prepend("<font color=\"red\">INCORRECT </font>");
					alert("FALSE");
				}
				else
				{
					if(value.substring(value.length-1) == "*"){
						// $("h4[name=mc" + counter + "]").prepend("<font color=\"green\">CORRECT </font>");
						alert("TRUE");
					}else{
						// $("h4[name=mc" + counter + "]").prepend("<font color=\"red\">INCORRECT </font>");
						// dataString[qid] = 'FALSE';
						alert("FALSE");
					}
				}

			} 
			else if($ ("h4[name=fb" + counter + "]").attr('id') == 'fb')
			{
				qid = $("h4[name=fb" + counter + "]").attr('qid');
				value = $("input[name=a" + counter + "]").val();
				if(value=="")
				{
					// $("h4[name=fb" + counter + "]").prepend("<font color=\"red\">INCORRECT </font>");
					alert("FALSE");
				}
				else
				{
					$.ajax({
						type: "POST",
						url: "check_answers.php",
						data: { 'qid' : qid, 'value' : value, 'type' : 'fb'},
						success: function(html) {
							alert(html);
							// if(html == "TRUE"){
							// 	dataString[qid] = 'TRUE';
							// 	$("h4[name=fb" + counter + "]").prepend("<font color=\"green\">CORRECT </font>");
							// }else{
							// 	$("h4[name=fb" + counter + "]").prepend("<font color=\"red\">INCORRECT </font>");
							// 	dataString[qid] = 'FALSE';
							// }
						}
					});
				}
			}
			else if ($("h4[name=tf" + counter + "]").attr('id') == 'tf')
			{
				qid = $("h4[name=tf" + counter + "]").attr('qid');
				value = $("input[name=a" + counter + "]:checked").val();
				if(value==null)
				{
					// $("h4[name=tf" + counter + "]").prepend("<font color=\"red\">INCORRECT </font>");
					alert("FALSE");
				}
				else
				{
					$.ajax({
						type: "POST",
						url: "check_answers.php",
						data: { 'qid' : qid, 'value' : value, 'type' : 'tf'},
						success: function(html) {
							alert(html);
							// if(html == "TRUE"){
							// 	dataString[qid] = 'TRUE';
							// 	$("h4[name=fb" + counter + "]").prepend("<font color=\"green\">CORRECT </font>");
							// }else{
							// 	$("h4[name=fb" + counter + "]").prepend("<font color=\"red\">INCORRECT </font>");
							// 	dataString[qid] = 'FALSE';
							// }
						}
					});
				}
			}
			else
			{
				break;
			}
			counter++;
		}

		// $.ajax({
		// 	type: "POST",
		// 	url: "check_answers.php",
		// 	data: dataString,
		// 	success: function(html) {
		// 		$("#quiz").html(html);
		// 	}
		// });
	}
</script>


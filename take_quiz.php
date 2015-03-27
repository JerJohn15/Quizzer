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
		var jsonObj = "";


		$("font").remove();

		jsonObj += "{\n";

		while(true){

			if ($("h4[name=mc" + counter + "]").attr('id') == 'mc')
			{	
				qid = $("h4[name=mc" + counter + "]").attr('qid');
				value = $("input[name=a" + counter + "]:checked").val();

				if(value == null){
					value = "";
				}

				jsonObj += "\"" + counter + "\" : { \"type\" : \"mc\", \"value\" : \"" + value + "\", \"qid\" : \"" + qid + "\" },\n";
			} 
			else if($ ("h4[name=fb" + counter + "]").attr('id') == 'fb')
			{
				qid = $("h4[name=fb" + counter + "]").attr('qid');
				value = $("input[name=a" + counter + "]").val();

				jsonObj += "\"" + counter + "\" : { \"type\" : \"fb\", \"value\" : \"" + value + "\", \"qid\" : \"" + qid + "\" },\n";
			}
			else if ($("h4[name=tf" + counter + "]").attr('id') == 'tf')
			{
				qid = $("h4[name=tf" + counter + "]").attr('qid');
				value = $("input[name=a" + counter + "]:checked").val();

				if(value == null){
					value = "";
				}

				jsonObj += "\"" + counter + "\" : { \"type\" : \"tf\", \"value\" : \"" + value + "\", \"qid\" : \"" + qid + "\" },\n";
			}
			else
			{
				break;
			}
			counter++;
		}

		jsonObj += "}";

		jsonObj = jsonObj.replace(",\n}","\n}");
		
		$.ajax({
			type: "POST",
			url: "check_answers.php",
			data: { 'data' : jsonObj },
			dataType: 'json',
			success: function(html) {
				for (var key in html) {
					if(html[key] == 'true'){
						$("h4[qid=" + key + "]").prepend("<font color=\"green\">CORRECT  </font>");
					}else if (html[key] == 'false'){
						$("h4[qid=" + key + "]").prepend("<font color=\"red\">INCORRECT  </font>");
					}else if (key == 'score'){
						$("#score").html(html[key]);
					}else{
						alert(html[key]);
					}
				}
				$("#submit").remove();
				$("#quiz").append("<input type=\"submit\" value=\"Continue\" id=\"cont\">");

				$("#cont").on("click", function(event){
					window.location.replace("index.php");
				});
			},
		    error: function (xhr, ajaxOptions, thrownError) {
		        alert(xhr.status);
		        alert(thrownError);
		    }
		});
	}
</script>



<?php
include 'footer.php';
?>
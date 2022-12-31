<?php 
include("connectDB.inc.php");
include("questionAnswerClasses.inc.php");

if(array_key_exists('questionID',$_GET) and is_numeric($_GET['questionID'])){

	$questionID =intval($_GET['questionID']);

} else {
	exit();
}

$query="SELECT * FROM question_types";
$mysqliResult = $mysqli->query($query);
$questionTypeList=array();

while($var = $mysqliResult->fetch_assoc()){

	extract($var);
	$questionTypeList[$questionTypeID] = $questionType;
}
$query="SELECT * FROM `questions` JOIN answers USING (questionID) WHERE questionID =?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $_GET['questionID']);
$stmt->execute();
$stmt->bind_result($questionID, $question, $feedback, $mark, $questionTypeID, $answerID, $answer, $isCorrect);
$questionList=array();
while($stmt->fetch()){
	if(!array_key_exists($questionID, $questionList)) $questionList[$questionID] = new question($question, $feedback, $mark, $questionTypeID);
	$questionList[$questionID]->answerList[$answerID]=new answer($answer, $isCorrect);
}

?> 
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous"> 
	<title>Document</title>
</head>
<body>
<style>
<?php include 'style.css'; ?>
</style>
<div class="d-flex my-card justify-content-center">
<?php
			foreach ($questionList as $keyQuestionID => $valueQuestion) {
			echo "<div class=\"card\" style=\"width: 28rem;\">";
				echo "<div class=\"card-body\">";
					echo "<h5 class=\"card-title\">".$question."</h5>";
					echo "<h6 class=\"card-subtitle mb-2 text-muted\">ID: ".$questionID."<br />Type: ".$questionTypeList[$questionTypeID]."<br />Mark: ".$mark."</h6>";
					echo "<ul class=\"card-text\">";
					foreach ($valueQuestion->answerList as $keyAnswerID => $valueAnswer) {
						echo "<li>".$questionList[$questionID]->answerList[$keyAnswerID]->answer.", <b>".$questionList[$questionID]->answerList[$keyAnswerID]->isCorrect."</b> </li>";
					}
					echo "</ul>";
					echo "<a href=\"readingQuestionsAnswers.php\" class=\"btn btn-danger\">Go back</a>";
				echo "</div>";
			echo "</div>";
}
?>
</div>
      
      
	
    
  
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
</body>
</html>

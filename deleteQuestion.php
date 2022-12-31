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




$query = "DELETE FROM `questions` WHERE questionID = '".$questionID."'";
$mysqliResult = $mysqli->query($query);

foreach ($questionList as $keyQuestionID => $valueQuestion) {
    foreach ($valueQuestion->answerList as $keyAnswerID => $valueAnswer) {
        $query = "DELETE FROM `answers` WHERE answerID = '".$keyAnswerID."'";
        $mysqliResult = $mysqli->query($query);
    }
}
header("Location: http://localhost/app/readingQuestionsAnswers.php");
?> 


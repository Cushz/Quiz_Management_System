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
$questionList[$questionID]->question = $_POST['question'];
$questionList[$questionID]->feedback = $_POST['feedback'];
$questionList[$questionID]->mark = $_POST['mark'];
$query = "UPDATE `questions` SET `question`='".$questionList[$questionID]->question."',`feedback`='".$questionList[$questionID]->feedback."',`mark`='".$questionList[$questionID]->mark."',`questionTypeID`='".$questionList[$questionID]->questionTypeID."' WHERE questionID = $questionID";
$mysqliResult = $mysqli->query($query);
$new_answer_key = "answer0";
$new_point_key = "point0";
$count = 1;
foreach ($questionList as $keyQuestionID => $valueQuestion) {
    foreach ($valueQuestion->answerList as $keyAnswerID => $valueAnswer) {
        $new_answer_key = str_replace($count-1, $count, $new_answer_key);
        $new_point_key = str_replace($count-1, $count, $new_point_key);
        $questionList[$questionID]->answerList[$keyAnswerID]->answer = $_POST[$new_answer_key];
        $questionList[$questionID]->answerList[$keyAnswerID]->isCorrect = $_POST[$new_point_key];
        $query = "UPDATE `answers` SET `answer`='".$questionList[$questionID]->answerList[$keyAnswerID]->answer."',`isCorrect`='".$questionList[$questionID]->answerList[$keyAnswerID]->isCorrect."',`questionID`=$questionID WHERE answerID = $keyAnswerID";
        $mysqliResult = $mysqli->query($query);
        $count += 1;
    }
}
header("Location: http://localhost/app/readingQuestionsAnswers.php");
?> 


<?php 
include("connectDB.inc.php");
include("questionAnswerClasses.inc.php");

$query = "SELECT `questionID` FROM `questions` ORDER BY questionID";
$mysqliResult = $mysqli->query($query);

while($var = $mysqliResult->fetch_assoc()){
    extract($var);
    $last_ID = intval($var['questionID']);
}

$last_ID = $last_ID + 1;
$questionID_last = $last_ID;
$query="SELECT * FROM question_types";
$mysqliResult = $mysqli->query($query);

$questionTypeList=array();

while($var = $mysqliResult->fetch_assoc()){
    
    extract($var);
	$questionTypeList[$questionTypeID] = $questionType;
}

$query="SELECT * FROM questions JOIN answers USING(questionID)";
$mysqliResult = $mysqli->query($query);

$questionList=array();
while( $var = $mysqliResult->fetch_assoc()){
extract($var);
if(!array_key_exists($questionID, $questionList)) $questionList[$questionID] = new question($question, $feedback, $mark, $questionTypeID);
$questionList[$questionID]->answerList[$answerID]=new answer($answer, $isCorrect);
}

$new_questionID = $questionID + 1;
$questionList[$new_questionID] = new question($question, $feedback, $mark, $questionTypeID);
$questionList[$new_questionID]->question = $_POST['question'];
$questionList[$new_questionID]->feedback = $_POST['feedback'];
$questionList[$new_questionID]->mark = $_POST['mark'];
$questionList[$new_questionID]->questionTypeID = $_POST['questionType'];


$answer_name = 'answer0';
$correct_point = 'point0';
for($i = 1; $i <= 5; $i++){
    $answer_name = str_replace($i-1, $i, $answer_name);
    $correct_point = str_replace($i-1, $i, $correct_point);

    if (!empty($_POST[$answer_name])){
        $questionList[$new_questionID]->answerList[$answerID+$i] = new answer($answer, $isCorrect);
        $questionList[$new_questionID]->answerList[$answerID+$i]->answer = $_POST[$answer_name];
        $questionList[$new_questionID]->answerList[$answerID+$i]->isCorrect = $_POST[$correct_point];
        $query="INSERT INTO `answers`(`answerID`, `answer`, `isCorrect`, `questionID`) VALUES ($answerID+$i,'".$questionList[$new_questionID]->answerList[$answerID+$i]->answer."','".$questionList[$new_questionID]->answerList[$answerID+$i]->isCorrect."',$new_questionID)";
        $mysqliResult = $mysqli->query($query);
    }
}

$query="INSERT INTO `questions`(`questionID`,`question`, `feedback`, `mark`, `questionTypeID`) VALUES ($new_questionID,'".$questionList[$new_questionID]->question."','".$questionList[$new_questionID]->feedback."','".$questionList[$new_questionID]->mark."','".$questionList[$new_questionID]->questionTypeID."')";
$mysqliResult = $mysqli->query($query);

header("Location: http://localhost/app/readingQuestionsAnswers.php");
?>


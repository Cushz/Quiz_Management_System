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
        <style>
            <?php
            include("style.css");
            ?>
        </style>
    </head>
    <body>
        <div class="d-flex my-edit-card justify-content-center">
        <?php
    foreach ($questionList as $keyQuestionID => $valueQuestion) {
        echo "<div class=\"card \" style=\"width: 38rem;\">";
            echo "<div class=\"card-body\">";
            echo "<form action=\"editing.php?questionID=$questionID\" method=\"post\">
            <p class=\"card-title\"><b>".$question."</b></p>
            <input type=\"text\" name=\"question\" class=\"form-control\" id=\"question\" placeholder=\"New Question\"></p>
            <p class=\"card-subtitle mb-2 text-muted\">".$questionList[$questionID]->feedback."</p>
            <input type=\"text\" name=\"feedback\" class=\"form-control\" id=\"feedback\" placeholder=\"New Feedback\"></p>
            <p class=\"card-subtitle mb-2 text-muted\">Mark:  ".$questionList[$questionID]->mark." </p>
            <input type=\"number\" name=\"mark\" class=\"form-control mb-3\" id=\"mark\" placeholder=\"New mark\" >";
                $new_answer_key = "answer0";
                $new_point_key = "point0";
                $count = 1;

                foreach ($valueQuestion->answerList as $keyAnswerID => $valueAnswer) {
                    $new_answer_key = str_replace($count-1, $count, $new_answer_key);
                    $new_point_key = str_replace($count-1, $count, $new_point_key);
    
                    echo "<p> 
                    <p class=\"card-subtitle mb-2 text-muted\">Answer: ".$questionList[$questionID]->answerList[$keyAnswerID]->answer." </p>
                    <input id=$new_answer_key class=\"form-control mb-2 \" name=$new_answer_key placeholder=\"New Answer\"required>
                    <input type=\"text\" id=$new_point_key class=\"form-control\" name=$new_point_key placeholder=\"New Point\" required>
                    </p>";
                    $count += 1;
                }
            echo "<input type=\"submit\" value=\"Save\" id=\"submit\" class=\"card-link btn btn-success\">";
            echo "<a href=\"readingQuestionsAnswers.php\" class=\"card-link btn btn-danger\">Cancel</a>";
        echo "</form>";
    echo "</div>";
echo "</div>";
}
?>	

        </div>



<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
</body>
</html>

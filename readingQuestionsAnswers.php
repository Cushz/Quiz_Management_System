<?php 
include("connectDB.inc.php");
include("questionAnswerClasses.inc.php");

try {
$query="SELECT * FROM question_types";
$mysqliResult = $mysqli->query($query);

} catch (Exception $e) { 
echo "MySQLi Error Code: " . $e->getCode() . "<br />";
echo "Exception Msg: " . $e->getMessage();
exit();
}

$questionTypeList=array();
while($var = $mysqliResult->fetch_assoc()){

	extract($var);
	$questionTypeList[$questionTypeID] = $questionType;

}


try {

$query="SELECT * FROM questions JOIN answers USING(questionID)";
$mysqliResult = $mysqli->query($query);

} catch (Exception $e) { 
echo "MySQLi Error Code: " . $e->getCode() . "<br />";
echo "Exception Msg: " . $e->getMessage();
exit();
}


$questionList=array();
while( $var = $mysqliResult->fetch_assoc()){
extract($var);

if(!array_key_exists($questionID, $questionList)) $questionList[$questionID] = new question($question, $feedback, $mark, $questionTypeID);
$questionList[$questionID]->answerList[$answerID]=new answer($answer, $isCorrect);
}

?>
<style>
<?php include 'style.css'; ?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous"> 
	<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
	<script>
      function ListOptions(){
         var typeId = parseInt(document.getElementById('questionType').value);
         const answer1 = document.getElementById("answer1");
         const answer2 = document.getElementById("answer2");
         const answer3 = document.getElementById("answer3");
         const answer4 = document.getElementById("answer4");
         const answer5 = document.getElementById("answer5");
         
         if (typeId == 1){
            answer1.style.display = "block";
            answer2.style.display = "block";
            answer3.style.display = "block";
            answer4.style.display = "block";
            answer5.style.display = "block";
         }
         else if(typeId == 2 || typeId == 3){
            
            answer1.style.display = "block";
            answer2.style.display = "block";
            answer3.style.display = "none";
            answer4.style.display = "none";
            answer5.style.display = "none";
            
         }
         else if(typeId == 4 || typeId == 5){

            answer1.style.display = "block";
            if (typeId==4) {
               answer1.type = "number"
            }
            else 
            {
               answer1.type = "text"
            }
            answer2.style.display = "none";
            answer3.style.display = "none";
            answer4.style.display = "none";
            answer5.style.display = "none";
         }
         else
         {
            answer1.style.display = "none";
            answer2.style.display = "none";
            answer3.style.display = "none";
            answer4.style.display = "none";
            answer5.style.display = "none";
         }
      
     }
      </script>

</head>
<body>
<h1 class="header text-center mt-4 mb-5">Questions</h1>
<div class="container">
<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
  <b>+</b> Create question
</button>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Create Question</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action='/creation.php' method="post" id="formus">         
               <input type="text" placeholder="Enter question" class="form-control questionInput" name="question" id="question" required>
               <input type="text" class="form-control feedback" name="feedback" id="feedback" placeholder="Enter feedback" required>
               <input type="number" class="form-control mark" name="mark" id="mark" placeholder="Enter mark" required>
            <div>
               <select required name='questionType' class="form-control" id="questionType" onchange="ListOptions();">
                  <option selected>Select the question type</option>
                  <option value="1">Multiple Choice</option>
                  <option value="2">True/False</option>
                  <option value="3">Double Choice</option>
                  <option value="4">Numerical</option>
                  <option value="5">Short Answer</option>
            </select>
            </div>
   <div class="answers-container container text-center">
      <div class="row">
         <div id="answer1" class="answerItem col">
            <input type='text' class="form-control" id="answer1" name="answer1" placeholder="answer" required>
            <input type='number' class="form-control" id='point1' name='point1' required placeholder="point">
         </div>
            
         <div id="answer2" class="answerItem col">
            <input type='text' class="form-control" id="answer2" name="answer2" placeholder="answer">
            <input type='number' class="form-control" id='point2' name='point2'placeholder="point">  
         </div>
      </div>
      <div class="row">
         <div id="answer3" class="answerItem col">   
            <input type='text' class="form-control" id="answer3" name="answer3" placeholder="answer">
            <input type='number' class="form-control" id='point3' name='point3'placeholder="point">
         </div>
            
         <div id="answer4" class="answerItem col">
            <input type='text' class="form-control" id="answer4" name="answer4" placeholder="answer">
            <input type='number' class="form-control" id='point4' name='point4'placeholder="point">
         </div>
      </div>
      <div class="row">
         <div id="answer5" class="answerItem col"> 
            <input type='text' class="form-control" id="answer5" name="answer5" placeholder="answer">
            <input type='number' class="form-control" id='point5' name='point5'placeholder="point">
         </div>
      </div>  
   </div>
      </div>
      
      <div class="modal-footer">
        <button type="Submit" id="submit" class="btn btn-success">Add</button>
      </div>
      </form>
    </div>
  </div>
</div>

	
<table class="table">
<thead>
	<tr>
		<th scope="col">#</th>
		<th scope="col">Question</th>
		<th scope="col">Type</th>
		<th scope="col">View</th>
		<th scope="col">Edit</th>
		<th scope="col">Delete</th>
	</tr>
</thead>
<tbody>
<?php 
foreach ($questionList as $keyQuestionID => $valueQuestion) {
	
	echo "<tr>";
	echo "<th scope=\"row\" class=\"id\"><b>".$keyQuestionID."</b></td>";
	echo "<td style=\"padding-top:15px;padding-bottom:15px;padding-right:5px;padding-left:5px;\" class=\"question\">".$valueQuestion->question."</td>";
	echo "<td class=\"type\">".$questionTypeList[$valueQuestion->questionTypeID]."</td>";
	echo "<td class=\"view\"><a class=\"far fa-eye btn btn-success\" href=\"viewQuestion.php?questionID=$keyQuestionID\"></a></td>";
	echo "<td class=\"edit\"><a class=\"far fa-edit btn btn-success\" href=\"editQuestion.php?questionID=$keyQuestionID\"></a></td>";
	echo "<td class=\"delete\"><a class=\"far fa-trash-alt btn btn-success\" href=\"deleteQuestion.php?questionID=$keyQuestionID\"></a></td>";
	echo "</tr>";



}

?>	
</tbody>
</table>





<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
</body>
</html>
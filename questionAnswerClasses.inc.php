<?php  
  
class question {
	public $question;
	public $feedback;
	public $mark;
	public $questionTypeID;
	public $answerList;  


	public function __construct($question, $feedback, $mark, $questionTypeID) {
		$this->question = $question;
		$this->feedback = $feedback;
		$this->mark =floatval($mark);
		$this->questionTypeID = $questionTypeID;
		$this->answerList=array();
	}
}
 
class answer {
	public $answer;
	public $isCorrect;

	public function __construct($answer, $isCorrect) {
		$this->answer = $answer;
		$this->isCorrect = floatval($isCorrect);
	}
}
?>
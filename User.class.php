<?php

class User {
	
	
	
	private $connection;
	
	
	
	
	function __construct($mysqli) {
		//this viitab klassile (this = user)
		$this->connection = $mysqli;
		$this->name = "Romil";
	}
	

	
	function signUp ($email, $password) {
		
		

		$stmt = $this->connection->prepare("INSERT INTO user_sample (email, password) VALUES (?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("ss", $email, $password);
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		
		
	}
	
	
	function login ($email, $password) {
		
		$error = "";
		
		
		$stmt = $this->connection->prepare("
		SELECT id, email, password, created 
		FROM user_sample
		WHERE email = ?");
	
		echo $this->connection->error;
		
		//asendan küsimärgi
		$stmt->bind_param("s", $email);
		
		//määran väärtused muutujatesse
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $created);
		$stmt->execute();
	
	
}

	function saveCar ($plate, $color) {
		
		
		$stmt = $this->connection->prepare("INSERT INTO car_ans_colors (plate, color) VALUES (?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("ss", $plate, $color);
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		
		
	}

	function getAllCars() {
		
		
		
		$stmt = $this->connection->prepare("
			SELECT id, plate, color
			FROM car_ans_colors
			WHERE deleted is NULL
		");
		echo $this->connection->error;
		
		$stmt->bind_result($id, $plate, $color);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$car = new StdClass();
			
			$car->id = $id;
			$car->plate = $plate;
			$car->carColor = $color;
			
			//echo $plate."<br>";
			// iga kord massiivi lisan juurde nr märgi
			array_push($result, $car);
		}
		
		$stmt->close();
		
		
		return $result;
	}
	
	function cleanInput($input){
		
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		
		return $input;
		
	}
	
	
	function saveInterest ($interest) {
		
		
		$stmt = $this->connection->prepare("INSERT INTO interests (interest) VALUES (?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("s", $interest);
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		
		
	}
	
	
	function getAllInterests() {
		
		
		$stmt = $this->connection->prepare("
			SELECT id, interest
			FROM interests
		");
		echo $this->connection->error;
		
		$stmt->bind_result($id, $interest);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$i = new StdClass();
			
			$i->id = $id;
			$i->interest = $interest;
		
			array_push($result, $i);
		}
		
		$stmt->close();
		
		
		return $result;
	}
	
	
	function getAllUserInterests() {
		
		
		$stmt = $this->connection->prepare("
			SELECT interest FROM interests
			JOIN user_interests 
			ON interests.id=user_interests.interest_id
			WHERE user_interests.user_id = ?
		");
		echo $this->connection->error;
		$stmt->bind_param("i", $_SESSION["userId"]);
		
		$stmt->bind_result($interest);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$i = new StdClass();
			
			$i->interest = $interest;
		
			array_push($result, $i);
		}
		
		$stmt->close();
		
		
		return $result;
	}
	
	
	function saveUserInterest ($interest) {
		
		
		$stmt = $this->connection->prepare("
			SELECT id FROM user_interests 
			WHERE user_id=? AND interest_id=?
		");
		$stmt->bind_param("ii", $_SESSION["userId"], $interest);
		$stmt->bind_result($id);
		
		$stmt->execute();
		
		if ($stmt->fetch()) {
			// oli olemas juba selline rida
			echo "juba olemas";
			// pärast returni midagi edasi ei tehta funktsioonis
			return;
			
		} 
		
		$stmt->close();
		
		// kui ei olnud siis sisestan
		
		$stmt = $this->connection->prepare("
			INSERT INTO user_interests
			(user_id, interest_id) VALUES (?, ?)
		");
		
		echo $this->connection->error;
		
		$stmt->bind_param("ii", $_SESSION["userId"], $interest);
		
		if ($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
			echo "ERROR ".$stmt->error;
		}
		
	}
	
	
	
	function deleteCar($id){
     	
         
 		
 		$stmt = $this->connection->prepare("UPDATE car_ans_colors SET deleted=NOW() WHERE id=? AND deleted IS NULL");
 		$stmt->bind_param("i",$id);
 		
 		// kas õnnestus salvestada
 		if($stmt->execute()){
 			// õnnestus
 			echo "kustutamine õnnestus!";
 		}
 		
 		$stmt->close();
 		
 		
 	}
	
	
	
}
?>
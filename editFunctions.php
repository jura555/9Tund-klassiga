<?php

	require_once("../../config.php");
	
	function getSingleCarData($edit_id){
    
        $database = "if16_juri";

		//echo "id on ".$edit_id;
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("SELECT plate, color FROM car_ans_colors WHERE id=?");

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($plate, $color);
		$stmt->execute();
		
		//tekitan objekti
		$car = new Stdclass();
		
		//saime �he rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$car->plate = $plate;
			$car->color = $color;
			
			
		}else{
			// ei saanud rida andmeid k�tte
			// sellist id'd ei ole olemas
			// see rida v�ib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		$mysqli->close();
		
		return $car;
		
	}


	function updateCar($id, $plate, $color){
    	
        $database = "if16_juri";

		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("UPDATE car_ans_colors SET plate=?, color=? WHERE id=? AND WHERE deleted is NULL");
		$stmt->bind_param("ssi",$plate, $color, $id);
		
		// kas �nnestus salvestada
		if($stmt->execute()){
			// �nnestus
			echo "salvestus �nnestus!";
		}
		
		$stmt->close();
		$mysqli->close();
		
	}
	

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
?>
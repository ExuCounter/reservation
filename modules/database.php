<?php
	// For error reporting on production mode
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	function dbConnection(){
		$servername = "localhost";
		$username = "mvvreservation";
		$password = "(VLZM76mGJ}N";
		$dbName = "mvv_reservation";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbName);

		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		return $conn;
	}

	// store data for cron
	function createReservation($name, $email, $date){
		$conn = dbConnection();
			$sql = "INSERT INTO reservation (name, email, date) VALUES ('$name', '$email', '$date')";
			if ($conn->query($sql) === TRUE)
				return true;
			else
				return false;
	}

	// getReservation Data
	function getReservationData($date){
		$conn = dbConnection();
		$sql = "SELECT id, name, email, date FROM reservation WHERE date='$date'";
		$result = $conn->query($sql);
		$data = [];
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				if(date("d/m/Y") == $row["date"]){
					array_push($data, $row);
				}
			}
			return $data;
		} else {
			return $data;
		}
	}

	// delete data after health statment mail sent
	function deleteReservation($id){
		$conn = dbConnection();
		$sql = "DELETE FROM reservation WHERE id='$id'";
		if ($conn->query($sql) === TRUE) {
			return true;
		} else {
			return false;
		}
	}
?>
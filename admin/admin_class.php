<?php
session_start();
ini_set('display_errors', 1);

require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
	
		// Secure the query to prevent SQL injection
		$username = $this->db->real_escape_string($username);
		$password = md5($password); // Encrypt password using MD5
	
		// Query the database for the user
		$qry = $this->db->query("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
	
		if($qry->num_rows > 0){
			$row = $qry->fetch_array();
	
			// Loop through all user data and store in session
			foreach ($row as $key => $value) {
				if($key != 'password' && !is_numeric($key)) {
					$_SESSION['login_'.$key] = $value;
					$_SESSION[$key] = $value;
				}

					
				
			}
	
			// Set profile photo: Use default if 'photo' is empty or file doesn't exist
			$_SESSION['login_photo'] = (!empty($row['photo']) && file_exists('uploads/photos/' . $row['photo']))
				? 'uploads/photos/' . $row['photo']
				: 'uploads/photos/default.jpg';
	
			return 1; // Login successful
		} else {
			return 3; // Login failed
		}
	}
	
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function save_user() {
		extract($_POST);
		include 'db_connect.php';
	
		if ($conn->connect_error) {
			error_log("Connection failed: " . $conn->connect_error);
			return 0;
		}
	
		$name = $conn->real_escape_string($name);
		$username = trim($conn->real_escape_string($username));
		$password = !empty($password) ? md5($password): '';
		$type = in_array($type, [1, 2]) ? $type : 2;
		$status = isset($status) ? (int)$status : 1;
		$id = isset($id) && !empty($id) ? (int)$id : 0;
	
		$stmt = $conn->prepare("SELECT id FROM users WHERE LOWER(username) = LOWER(?) AND id != ?");
		$stmt->bind_param('si', $username, $id);
		$stmt->execute();
		$chk = $stmt->get_result();
	
		if ($chk->num_rows > 0) {
			$stmt->close();
			return 2;
		}
		$stmt->close();
	
		$data = "name = '$name', username = '$username', type = '$type', status = '$status'";
		if (!empty($password)) {
			$data .= ", password = '$password'";
		}
	
		if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
			$allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];
			$maxSize = 2 * 1024 * 1024;
	
			$fileType = mime_content_type($_FILES['photo']['tmp_name']);
			$fileSize = $_FILES['photo']['size'];
	
			if (in_array($fileType, $allowedTypes) && $fileSize <= $maxSize) {
				$uploadDir = 'uploads/photos/';
				if (!is_dir($uploadDir)) {
					mkdir($uploadDir, 0755, true);
				}
				$fileName = uniqid() . '-' . basename($_FILES['photo']['name']);
				$photoPath = $uploadDir . $fileName;
				if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
					$data .= ", photo = '$photoPath'";
				}
			}
		}
	
		if ($id == 0) {
			$sql = "INSERT INTO users SET $data";
			$save = $conn->query($sql);
		} else {
			$sql = "UPDATE users SET $data WHERE id = '$id'";
			$save = $conn->query($sql);
		}
	
		if (!$save) {
			return 0;
		}
		$conn->close();
		return 1;
	}
	
	
	
	
	
	

	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function signup() {
		// Extract the POST variables safely
		$name = $this->db->real_escape_string($_POST['name']);
		$contact = $this->db->real_escape_string($_POST['contact']);
		$address = $this->db->real_escape_string($_POST['address']);
		$email = $this->db->real_escape_string($_POST['email']);
		$password = md5($_POST['password']);

        // Password should be hashed securely (consider using bcrypt instead of MD5)
	
		// Check if the email already exists
		$chk = $this->db->query("SELECT * FROM users WHERE username = '$email'");
		if ($chk->num_rows > 0) {
			return 2; // Email already exists
		}
	
		// Prepare the query to insert a new user
		$data = [
			'name' => $name,
			'contact' => $contact,
			'address' => $address,
			'username' => $email,
			'password' => $password,
			'type' => 3 // Set user type as 3
		];
	
		// Use prepared statements to securely insert the data into the database
		$query = "INSERT INTO users (name, contact, address, username, password, type) VALUES (?, ?, ?, ?, ?, ?)";
		$stmt = $this->db->prepare($query);
		$stmt->bind_param('sssssi', $data['name'], $data['contact'], $data['address'], $data['username'], $data['password'], $data['type']);
	
		// Execute the query
		if ($stmt->execute()) {
			// Now login the user after successful registration
			$qry = $this->db->query("SELECT * FROM users WHERE username = '$email' AND password = '$password'");
	
			if ($qry->num_rows > 0) {
				$user = $qry->fetch_assoc();
				// Store user data in session
				foreach ($user as $key => $value) {
					if ($key != 'password' && !is_numeric($key)) {
						$_SESSION['login_' . $key] = $value;
					}
				}
			}
			return 1; // Successfully signed up and logged in
		} else {
			return 0; // Error during insertion
		}
	}
	

	function save_settings(){
		extract($_POST);
		$data = " name = '".str_replace("'","&#x2019;",$name)."' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}
		
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['settings'][$key] = $value;
		}

			return 1;
				}
	}

	
	function save_venue(){
		extract($_POST);
		$data = " venue = '$venue' ";
		$data .= ", address = '$address' ";
		$data .= ", description = '$description' ";
		$data .= ", rate = '$rate' ";
		if(empty($id)){
			//echo "INSERT INTO arts set ".$data;
			$save = $this->db->query("INSERT INTO venue set ".$data);
			if($save){
				$id = $this->db->insert_id;
				$folder = "assets/uploads/venue_".$id;
				if(is_dir($folder)){
					$files = scandir($folder);
					foreach($files as $k =>$v){
						if(!in_array($v, array('.','..'))){
							unlink($folder."/".$v);
						}
					}
				}else{
					mkdir($folder);
				}
				if(isset($img)){
				for($i = 0 ; $i< count($img);$i++){
						$img[$i]= str_replace('data:image/jpeg;base64,', '', $img[$i] );
						$img[$i] = base64_decode($img[$i]);
						$fname = $id."".strtotime(date('Y-m-d H:i'))."".$imgName[$i];
						$upload = file_put_contents($folder."/".$fname,$img[$i]);
					}
				}
			}
		}else{
			$save = $this->db->query("UPDATE venue set ".$data." where id=".$id);
			if($save){
				$folder = "assets/uploads/venue_".$id;
				if(is_dir($folder)){
					$files = scandir($folder);
					foreach($files as $k =>$v){
						if(!in_array($v, array('.','..'))){
							unlink($folder."/".$v);
						}
					}
				}else{
					mkdir($folder);
				}

				if(isset($img)){
				for($i = 0 ; $i< count($img);$i++){
						$img[$i]= str_replace('data:image/jpeg;base64,', '', $img[$i] );
						$img[$i] = base64_decode($img[$i]);
						$fname = $id."".strtotime(date('Y-m-d H:i'))."".$imgName[$i];
						$upload = file_put_contents($folder."/".$fname,$img[$i]);
					}
				}
			}
		}
		if($save)
			return 1;
	}
	function delete_venue(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM venue where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function send_email(){
		extract($_POST);
		
		$mail = new PHPMailer(true);

		try {
			$mail->isSMTP();
			$mail->Host = 'smtp.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Username = 'emssccp.co@gmail.com';
			$mail->Password = 'hftw syqy igfr gxur';
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
			$mail->Port = 465;

			$mail->setFrom('emssccp.co@gmail.com', 'Event Management');
			$mail->addAddress($email);

			$mail->isHTML(true);
			$mail->Subject = 'Booking Confirmation';
			$mail->Body = "<p>Hello,</p><p>Your booking has been confirmed:</p><p>$details</p>";

			$mail->send();
			return 1;
		} catch (Exception $e) {
			error_log("Mailer Error: {$mail->ErrorInfo}");
			return 0;
		}
	}

	function save_book(){
		extract($_POST);
		error_log("POST data received: " . print_r($_POST, true)); // Debug log
		
		// Sanitize inputs
		$venue_id = $this->db->real_escape_string($venue_id);
		$name = $this->db->real_escape_string($name);
		$address = $this->db->real_escape_string($address);
		$email = $this->db->real_escape_string($email);
		$contact = $this->db->real_escape_string($contact);
		$schedule = $this->db->real_escape_string($schedule);
		$duration = $this->db->real_escape_string($duration);
		$status = isset($status) ? (int)$status : 0; // Ensure status is an integer
		
		error_log("Status value: " . $status); // Debug log for status
		
		$data = " venue_id = '$venue_id' ";
		$data .= ", name = '$name' ";
		$data .= ", address = '$address' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", datetime = '$schedule' ";
		$data .= ", duration = '$duration' ";
		$data .= ", status = $status "; // No quotes for integer value
		
		// Get venue details for email
		$venue = $this->db->query("SELECT venue FROM venue WHERE id = '$venue_id'")->fetch_assoc();
		$venue_name = $venue ? $venue['venue'] : 'Unknown Venue';
		
		if(empty($id)){
			$save = $this->db->query("INSERT INTO venue_booking set ".$data);
			if($save) {
				// Format booking details for new booking email
				$details = "
					Venue: $venue_name
					Schedule: " . date("M d, Y h:i A", strtotime($schedule)) . "
					Duration: $duration
					Name: $name
					Contact: $contact
					Address: $address
					
					Status: Pending Verification
				";

				// Send confirmation email
				$_POST['details'] = $details;
				$this->send_email();
			}
		} else {
			// Get old status before update
			$old_status = $this->db->query("SELECT status FROM venue_booking WHERE id = ".$id)->fetch_assoc()['status'];
			
			$save = $this->db->query("UPDATE venue_booking set ".$data." where id=".$id);
			
			if($save && isset($status) && $old_status != $status) {
				// Format status update email
				$status_text = $status == 0 ? "Pending Verification" : ($status == 1 ? "Confirmed" : "Cancelled");
				$details = "
					Dear $name,
					
					Your booking status has been updated:
					
					Venue: $venue_name
					Schedule: " . date("M d, Y h:i A", strtotime($schedule)) . "
					Duration: $duration
					
					New Status: $status_text					
					" . ($status == 1 ? "Your booking has been confirmed. Please arrive on time." :
						($status == 2 ? "Your booking has been cancelled. Please contact us if you have any questions." :
						"Your booking is being verified. We will notify you once confirmed.")) . "
				";

				// Send status update email
				$_POST['details'] = $details;
				$this->send_email();
			}
		}
		
		if($save)
			return 1;
	}
	function delete_book(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM venue_booking where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_register(){
		extract($_POST);
		$data = " event_id = '$event_id' ";
		$data .= ", name = '$name' ";
		$data .= ", address = '$address' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		if(isset($status))
		$data .= ", status = '$status' ";
		if(isset($payment_status))
		$data .= ", payment_status = '$payment_status' ";
		else
		$data .= ", payment_status = '0' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO audience set ".$data);
		}else{
			$save = $this->db->query("UPDATE audience set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_register(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM audience where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_event(){
		extract($_POST);
		$data = " event = '$event' ";
		$data .= ",venue_id = '$venue_id' ";
		$data .= ", schedule = '$schedule' ";
		$data .= ", audience_capacity = '$audience_capacity' ";
		if(isset($payment_status))
		$data .= ", payment_type = '$payment_status' ";
		else
		$data .= ", payment_type = '2' ";
		if(isset($type))
			$data .= ", type = '$type' ";
		else
		$data .= ", type = '1' ";
			$data .= ", amount = '$amount' ";
		$data .= ", description = '".htmlentities(str_replace("'","&#x2019;",$description))."' ";
		if($_FILES['banner']['tmp_name'] != ''){
						$_FILES['banner']['name'] = str_replace(array("(",")"," "), '', $_FILES['banner']['name']);
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['banner']['name'];
						$move = move_uploaded_file($_FILES['banner']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", banner = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO events set ".$data);
			if($save){
				$id = $this->db->insert_id;
				$folder = "assets/uploads/event_".$id;
				if(is_dir($folder)){
					$files = scandir($folder);
					foreach($files as $k =>$v){
						if(!in_array($v, array('.','..'))){
							unlink($folder."/".$v);
						}
					}
				}else{
					mkdir($folder);
				}
				if(isset($img)){
				for($i = 0 ; $i< count($img);$i++){
						$img[$i]= str_replace('data:image/jpeg;base64,', '', $img[$i] );
						$img[$i] = base64_decode($img[$i]);
						$fname = $id."".strtotime(date('Y-m-d H:i'))."".$imgName[$i];
						$upload = file_put_contents($folder."/".$fname,$img[$i]);
					}
				}
			}
		}else{
			$save = $this->db->query("UPDATE events set ".$data." where id=".$id);
			if($save){
				$folder = "assets/uploads/event_".$id;
				if(is_dir($folder)){
					$files = scandir($folder);
					foreach($files as $k =>$v){
						if(!in_array($v, array('.','..'))){
							unlink($folder."/".$v);
						}
					}
				}else{
					mkdir($folder);
				}

				if(isset($img)){
				for($i = 0 ; $i< count($img);$i++){
						$img[$i]= str_replace('data:image/jpeg;base64,', '', $img[$i] );
						$img[$i] = base64_decode($img[$i]);
						$fname = $id."".strtotime(date('Y-m-d H:i'))."".$imgName[$i];
						$upload = file_put_contents($folder."/".$fname,$img[$i]);
					}
				}
			}
		}
		if($save)
			return 1;
	}
	function delete_event(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM events where id = ".$id);
		if($delete){
			return 1;
		}
	}
	
	function get_audience_report() {
		extract($_POST);
		$data = array();
	
		// Fetch Event Details
		$event = $this->db->query("SELECT e.*, v.venue 
								   FROM events e 
								   INNER JOIN venue v ON v.id = e.venue_id  
								   WHERE e.id = $event_id")->fetch_array();
		if ($event) {
			foreach ($event as $key => $value) {
				if (!is_numeric($key)) {
					$data['event'][$key] = $value;
				}
			}
	
			// Fetch Audience Details
			$audience = $this->db->query("SELECT * FROM audience 
										  WHERE status = 1 AND event_id = $event_id");
			if ($audience->num_rows > 0) {
				while ($row = $audience->fetch_assoc()) {
					$row['pstatus'] = $data['event']['payment_type'] == 1 
						? "N/A" 
						: ($row['status'] == 1 ? "Paid" : "Unpaid");
					$data['data'][] = $row;
				}
			}
		}
	
		return json_encode($data);
	}
	
	function get_venue_report() {
		// Extract POST data
		extract($_POST);
		$data = array();
		$date = $month . '-01';
	
		// Validate venue_id and month inputs
		if (!isset($venue_id) || !is_numeric($venue_id) || !isset($month)) {
			return json_encode(['error' => 'Invalid input']);
		}
	
		// Fetch Venue Details
		$venue = $this->db->query("SELECT * FROM venue WHERE id = $venue_id");
		if ($venue && $venue->num_rows > 0) {
			$venue_details = $venue->fetch_assoc();
			foreach ($venue_details as $key => $value) {
				if (!is_numeric($key)) {
					$data['venue'][$key] = $value;
				}
			}
			$data['venue']['month'] = date("F, Y", strtotime($date));
		} else {
			return json_encode(['error' => 'Venue not found']);
		}
	
		// Fetch Events for the Venue in the Given Month
		$events = $this->db->query("SELECT * FROM events 
									WHERE DATE_FORMAT(schedule, '%Y-%m') = '$month' 
									AND venue_id = $venue_id");
		if ($events && $events->num_rows > 0) {
			while ($row = $events->fetch_assoc()) {
				$row['fee'] = $row['payment_type'] == 1 ? "FREE" : number_format($row['amount'], 2);
				$row['etype'] = $row['type'] == 1 ? "Public" : "Private";
				$row['sched'] = date("M d, Y h:i A", strtotime($row['schedule']));
				$data['data'][] = $row;
			}
		} else {
			$data['data'] = [];
		}
	
		return json_encode($data);
	}
	
	

	function save_art_fs(){
		extract($_POST);
		$data = " art_id = '$art_id' ";
		$data .= ", price = '$price' ";
		if(isset($status)){
		$data .= ", status = '$status' ";
		}
		

		if(empty($id)){
			$save = $this->db->query("INSERT INTO arts_fs set ".$data);
			
		}else{
			$save = $this->db->query("UPDATE arts_fs set ".$data." where id=".$id);
		}
		if($save){

			return json_encode(array("status"=>1,"id"=>$id));
		}
	}
	function delete_art_fs(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM arts_fs where id = ".$id);
		if($delete){
				return 1;
			}
	}
	function delete_order(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM orders where id = ".$id);
		if($delete){
				return 1;
			}
	}
// Function to update an order
function update_order($db, $order_id, $status, $deliver_schedule, $fs_id) {
    // Update the order's status and delivery schedule
    $order = $db->query("UPDATE orders SET status = $status, deliver_schedule = '$deliver_schedule' WHERE id = $order_id");
    if ($order) {
        // Update the status of the related arts_fs record
        if (in_array($status, [1, 3])) {
            $fs = $db->query("UPDATE arts_fs SET status = 1 WHERE id = $fs_id");
        } else {
            $fs = $db->query("UPDATE arts_fs SET status = 0 WHERE id = $fs_id");
        }
        if ($fs) {
            return 1; // Successful update
        }
    }
    return 0; // Failed update
}

// Function to get audience count based on a specific status
function get_audience_status_count($conn, $status) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM audience WHERE status = ?");
    $stmt->bind_param("i", $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result ? $result->fetch_assoc()['count'] : 0;
    $stmt->close();
    return $count;
}
}
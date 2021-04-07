<?php
include_once "autoload.php";

if(isset($_GET['delete_id'])){
$delete_id = $_GET['delete_id'];
$photo_name = $_GET['photo'];

unlink('photos/' . $photo_name);
delete('port', $delete_id);
header('location:index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Development Area</title>
	<!-- ALL CSS FILES  -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
	
	<?php
	// Set to see
	if( isset($_POST['stc'])){
	// get value
    $name= $_POST['name'];
    $email= $_POST['email'];
    $cell= $_POST['cell'];
    $username= $_POST['username'];
    $age= $_POST['age'];
    $location= $_POST['location'];
    $gender=' ';
	if(isset($_POST['gender'])){
		$gender =$_POST['gender'];
	}
	$dept= $_POST['dept'];

// form validation	
	if(empty($name) || empty($email) || empty($cell) || empty($username) || empty($age) || empty($location) || empty($gender) || empty($dept)){
		$msg = validate('All fields are required');
	}
	else if(filter_var($email, FILTER_VALIDATE_EMAIL)== false){
		$msg = validate('Invalid email');
		
	}
	else{
	
		$data =move($_FILES['photo'], 'photos/');

		// Get function
		$unique_name = $data['unique_name'];
		$err_msg = $data['err_msg'];

		if( empty($err_msg)){
				// Data insert
	create("INSERT INTO port (name, email, cell, username, age, location, gender, dept, photo ) 
	VALUES ('$name' , '$email' , '$cell' , '$username' , '$age' , '$location', '$gender' , '$dept' , '$unique_name')");
				
				$msg = validate('Data stable' ,  'success');
		}else{

			$msg= $err_msg;
		}
	}
}

	
	?>

	<div class="wrap-table">
		<a class="btn btn-sm btn-primary" data-toggle="modal" href="#add_student_modal">Add new student</a>
		<br>
		<?php
		if(isset($msg)){
			echo $msg;
		}
		?>
		<br>
		<div class="card shadow">
			<div class="card-body">
				<h2>All Students</h2>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Email</th>
							<th>Cell</th>
							<th>Photo</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>

					<?php
					$data = all('port');
					$i= 1;

					while($student = $data->fetch_object()) :
					?>
						<tr>
							<td><?php  echo $i; 
							$i++?></td>
							<td><?php echo $student->name ?></td>
							<td><?php echo $student->email ?></td>
							<td><?php echo $student->cell ?></td>
							<td><img src="photos/<?php echo $student->photo ?>" alt=""></td>
							<td>
								<a class="btn btn-sm btn-info" href="show.php?show_id=<?php echo $student->id ?>">View</a>
								<a class="btn btn-sm btn-warning" href="edit.php?edit_id=<?php echo $student->id ?>">Edit</a>
								<a id="delete_btn" class="btn btn-sm btn-danger" href="?delete_id=<?php echo $student->id ?>
								&photo=<?php echo $student->photo ?>">Delete</a>
							</td>
						</tr>
					<?php	endwhile;?>	
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	<!-- Student create modal -->
	<div id="add_student_modal" class="modal fade">
		<div class="modal-dialog modal-dialog-centered">
			<div style="padding:0px 20px 0px 20px;" class="modal-content">
				<div class="modal-header"></div>
				<h3>  Add new student</h3>
				<div class="modal-body"></div>
				<form action="" method="POST" enctype="multipart/form-data">
				
					<div class="form-group">
						<label for="">Student name</label>
						<input name="name" class="form-control" type="text">
					</div>

					<div class="form-group">
						<label for="">Email</label>
						<input name="email" class="form-control" type="text">
					</div>

					<div class="form-group">
					<label for="">Cell</label>
					<input name="cell" class="form-control" type="text">
				</div>

				<div class="form-group">
					<label for="">username</label>
					<input name="username" class="form-control" type="text">
				</div>

				<div class="form-group">
					<label for="">Age</label>
					<input name="age" class="form-control" type="text">
				</div>

				<div class="form-group">
					<label for="">Location</label>
					<select class="form-control" name="location" id="">
						<option value="">--SELECT--</option>
						<option value="CTG">CTG</option>
						<option value="DHK">DHK</option>
						<option value="RAJ">RAJ</option>
						<option value="SAY">SAY</option>
						<option value="BAR">BAR</option>
						<option value="KHU">KHU</option>
					</select>
				</div>

				<div class="form-group">
					<label for="">Gender</label><br>
<input name="gender" type="radio" checked value ="male" id="male"><label for="male">Male</label>
<input name="gender" type="radio" value ="female" id="female"><label for="femal">Female</label>
				</div>
				
				<div class="form-group">
					<label for="">Department</label>
					<select class="form-control"  name="dept" id="">
						<option value="">--SELECT--</option>
						<option value="Math">MATH</option>
						<option value="CSC">CSC</option>
						<option value="EEE">EEE</option>
						<option value="English">ENG</option>
						<option value="Bangla">BANGLA</option>
						<option value="LAW">LAW</option>
					</select>
				</div>

				<div class="form-group">
					<label for="">Profile photo</label><br>
					<img id="load_student_photo" style="max-width:100%;" src="" alt="">
					<br>
					<label for="student_photo"><img width="80px" src="assets/media/img/upload.webp" alt=""></label>
					<input id="student_photo" name="photo" style="display:none;" class="form-control" type="file">
				</div>

				<div class="form-group">
					<label for=""></label>
					<input name="stc" class="btn btn-primary btn-sm" type="submit" value="Add Profile">
				</div>

				</form>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>

	<!-- JS FILES  -->
	<script src="assets/js/jquery-3.4.1.min.js"></script>
	<script src="assets/js/popper.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/custom.js"></script>
	<script>
	$('#student_photo').change(function(e){
		let file_url = URL.createObjectURL(e.target.files[0]);
		$('#load_student_photo').attr('src',file_url);
	});
	$('#delete_btn').click(function(){
	let confirmation =confirm('Are you sure?');

	if(confirmation ==true){
		return true;
	}else{
		return false;
	}
	});

	</script>
</body>
</html>
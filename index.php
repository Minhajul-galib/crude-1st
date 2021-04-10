<?php
include_once "autoload.php";

if(isset($_GET['delete_id'])){
$delete_id = $_GET['delete_id'];
$photo_name = $_GET['photo'];

unlink('photos/' . $photo_name);
delete('staff', $delete_id);
header('location:index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>All Staffs</title>
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
	

	
	// form validation	
	if(empty($name) || empty($email) || empty($cell) || empty($username) || empty($age) || empty($location) || empty($gender) ){
		$msg = validate('All fields are required');
	}
	else if(filter_var($email, FILTER_VALIDATE_EMAIL)== false){
		$msg = validate('Invalid email');
		
	}elseif(dataCheck('staff' , 'email' , $email)){     
		$msg = validate('Email already exists !' , 'warning');
		
	}elseif( dataCheck('staff' , 'username' , $username) ){
		$msg = validate('User name already exists !' , 'warning');
	}elseif( dataCheck('staff' , 'cell' , $cell)){
		$msg =validate('Cell already exists !' , 'warning');
	}
	else{
	
		$data =move($_FILES['photo'], 'photos/');

		// Get function
		$unique_name = $data['unique_name'];
		$err_msg = $data['err_msg'];

		if( empty($err_msg)){
				// Data insert
	create("INSERT INTO staff (name, email, cell, username, age, location, gender, photo ) 
	VALUES ('$name' , '$email' , '$cell' , '$username' , '$age' , '$location', '$gender' , '$unique_name')");
				
				$msg = validate('Data stable' ,  'success');
		}else{

			$msg= $err_msg;
		}
	}
}

	
	?>

	<div class="wrap-table">
		<a class="btn btn-sm btn-primary" data-toggle="modal" href="#add_student_modal">Add new Staff</a>
		<a class="btn btn-sm btn-primary" href="http://localhost/Assignment%20School%20data/">Back to dashboard</a>
		<br>
		<?php
		if(isset($msg)){
			echo $msg;
		} 
		?>
		<br>
		<div class="card shadow">

		<form style="padding:10px 0px 0px 20px;" class="form-inline" action="" method="POST" >
			<div class="form-group mx-sn-3 mb-2">
				<label for="inputPassword2" class="sr-only">search</label>
				<input name="search" type="search" class="form-control" id="inptupassword2" placeholder="Search">

			</div>
			<button name="searchbtn" type="submit" class="btn btn-primary mb-2">Search</button>

		</form>

			<div class="card-body">
				<h2>All Staffs</h2>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Email</th>
							<th>Cell</th>
							<th>User name</th>
							<th>Photo</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>

					<?php
						$sql ="SELECT  *  FROM  staff";

						$data = connect()->query($sql);
						
						if(isset($_POST['searchbtn'])){
	
						$search=$_POST['search'];
	
						$sql ="SELECT  *  FROM  staff WHERE name LIKE '%$search%' OR cell LIKE '%$search%' OR cell LIKE '%$search%' ";
	
						$data = connect()->query($sql);
	
						}

					$i= 1;

					while($student = $data->fetch_object()) :
					?>
						<tr>
							<td><?php  echo $i; 
							$i++?></td>
							<td><?php echo $student->name ?></td>
							<td><?php echo $student->email ?></td>
							<td><?php echo $student->cell ?></td>
							<td><?php echo $student->username ?></td>
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
				<h3>  Add new Staff</h3>
				<div class="modal-body"></div>
				<form action="" method="POST" enctype="multipart/form-data">
				
					<div class="form-group">
						<label for="">Staff name</label>
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
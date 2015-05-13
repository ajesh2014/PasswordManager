<?php

	require_once("../config.php");
	
	include(CONTROLLERS_PATH . "/DashboardController.php");

?>


<!DOCTYPE html>
<html>
	<head>
	<title>Password Manager</title>
	</head>
	<body>
		<div>
			<a href="add">Add New Password</a>/
			<a href="logout">logout</a>
		</div>
		<?php
			if (count($rows) > 0){
		?>
		<table>
		  <thead>
			<tr>
			  <th>Application</th>
			
			  <th>Edit / Delete</th>
			</tr>
		  </thead>
		  <tbody>
		<?php foreach ($rows as $row){ ?>
			<tr>
			  <td><?php echo $row['application'];?></td>
			 
				<td><a href="security?id=<?php echo $row['id'];?>&action=Edit">Edit</a> / 
					<a href="security?id=<?php echo $row['id'];?>&action=Delete">Delete</a>
				</td>				  
			</tr>
		<?php } ?>
		  </tbody>
		</table>
		<?php
			}else{
		?>
			<h2>There are no passwords yet</h2>
		<?php
			}
			
		?>
	</body>
</html>

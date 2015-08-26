<?php
	$username = $_POST['username'];
	$password = $_POST['password'];

	$con = mysqli_connect("127.0.0.1:3306","root","root", "helenservice");
	if (!$con)
	{
		echo 1;  //fail to connect db
	}
	else
	{		
		$strsql = "SELECT * FROM `hs_member` where `username`='$username'";
		$result = mysqli_query($con, $strsql);
		if (!$result)
		{
			mysqli_close($con);
			echo 2;  //username doesn't exist
		}
		else
		{		
			if($result_row = mysqli_fetch_array($result))
			{
				$password_in_db = $result_row['password'];
				if ($password_in_db == md5($password))
				{
					mysqli_close($con);
					echo 0; //ok
				}
				else
				{
					mysqli_close($con);
					echo 3; //error password
				}
			}
		}
		
	}
?>
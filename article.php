<?php
	// Initialize the session
	session_start();
	require_once "config.php";
	$admin = false;
	$id = htmlspecialchars($_GET["id"]);
	
	// Get article with id = $id
	   $sql = "SELECT author, title, body, created_at FROM articles WHERE id = ?";
	       if($stmt = mysqli_prepare($link, $sql)){
	           mysqli_stmt_bind_param($stmt, "s", $param_id);
	           $param_id = $id;
	           if(mysqli_stmt_execute($stmt)){
	               mysqli_stmt_store_result($stmt);
				mysqli_stmt_bind_result($stmt, $author_id, $title, $body, $created_at);
				mysqli_stmt_fetch($stmt); 
	           } else{
	               echo "Oops! Something went wrong. Please try again later.";
	           }
	       }   
	   mysqli_stmt_close($stmt);
		
		
	// Get author info with author = $author_id
	   $sql = "SELECT username FROM users WHERE id = ?";
	       if($stmt = mysqli_prepare($link, $sql)){
	           mysqli_stmt_bind_param($stmt, "s", $param_id);
	           $param_id = $author_id;
	           if(mysqli_stmt_execute($stmt)){
	               mysqli_stmt_store_result($stmt);
				mysqli_stmt_bind_result($stmt, $author_name);
				mysqli_stmt_fetch($stmt); 
	           } else{
	               echo "Oops! Something went wrong. Please try again later.";
	           }
	       }   
	   mysqli_stmt_close($stmt);	
	
	// Checking account type
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){ } else {
		$account_type = $_SESSION["account_type"];
		if($author_id == $_SESSION["id"] || $account_type == "a"){
			$admin = true;
		}
	}
?>
<html>
	<head>
		<link rel="stylesheet" href="style.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
		<style type="text/css">
			body{ font: 14px sans-serif; }
			.wrapper{ width: 800px; padding: 20px;margin-left:auto;margin-right:auto; }
			table td th{padding:5px}
		</style>
		<script>
			function deleteArt() {
			var delA = confirm("Weet je zeker dat je dit wilt verwijderen?");
			if (delA == true) {
				window.location.href='delete.php?id=<?php echo $id ?>'
			} else {
			}}
		</script>
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="wrapper">
			<a class="btn btn-primary" href='articles.php'>< Terug naar lijst</a>
			<?php
				echo "<h1>" . $title . "</h1>";
				echo "<p><i>" . $author_name . "</i></p>";
				echo "<div style='height:10px;width:100%;background-color:grey'></div>";
				echo "<p>" . $body . "</p>";
				if($admin) {
				echo "<br><br><br>";
				echo "<a class=\"btn btn-danger\" onclick='deleteArt()'>DELETE</a>";
				echo "&nbsp;&nbsp;&nbsp;";
				echo "<a class=\"btn btn-default\" href='edit.php?id=$id'>EDIT</a>";
				}
				?>
		</div>
	</body>
</html>
<div class="header">
	<div class="login">
		<?php
			echo '
				<div class="form-group form-boep">
					<label style="padding-top:25px;color:white;font-size:20px;">Welkom, ' . $_SESSION["username"] . '</label>
				</div>             
				<div class="form-group form-boep" style="width:100px;">
					<label>&nbsp;</label>
					<a href="logout.php" class="btn btn-default form-control"> Logout</a>
				</div>
				';
		?>
	</div>
</div>
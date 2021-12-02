<!DOCTYPE html>
<html>
<head>
	<title>Είσοδος</title>
	<link rel="stylesheet" type="text/css" href="styles/index_style.css">
</head>
<body>
     <h1 id=game_title>Μουτζούρης</h1>

    <form action="auth/login.php" method="post">
     	<h2>Είσοδος</h2>
     	<?php if (isset($_GET['error'])) { ?>
     		<p class="error"><?php echo $_GET['error']; ?></p>
     	<?php } ?>
     	<label>Όνομα Χρήστη</label>
     	<input type="text" name="uname" placeholder="User Name"><br>

     	<label>Κωδικός</label>
     	<input type="password" name="password" placeholder="Password"><br>

     	<button type="submit">Είσοδος</button>
     </form>

    <p><?php include 'footer.php';?> </p>

</body>
</html>
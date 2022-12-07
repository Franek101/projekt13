<?php

	session_start();
	
	if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
	{
		header('Location: bib.php');
		exit();
	}

?>
<html>

<head lang="pl">
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">

    <style>
    #kebab
        {
            color:#2BFF00;
            float:right;
            background-color:darkgoldenrod;
        }
    
    
    
    
    </style>
    
    
</head>
<body>
<div class="corpus">
    <div id="kebab">
    JESTES ZALOGOWANY JAKBYS NIE ZAUWAZYL/A
    </div>
    
	<br><br><br>
<h1>
Biblioteka 

</h1>

<div style="font-family: Comic Sans MS">
<form action="zaloguj.php" method="post">


	<input type="submit" id="pop" value="wyloguj">


</form>
   <form action="dodaj.php" method="post">


	<input type="submit" id="pop" value="wypozycz" name="riri">


</form>




</div>


</div>

</body>




</html>
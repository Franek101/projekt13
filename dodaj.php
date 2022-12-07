
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Biblioteka</title>
	<link rel="stylesheet" href="style.css">
</head>
 
<body>
<div class="corpus">
	<div id="pole">
	<form method="post">
		Tytuł:<input type="text" id="lularz" name="tytul">
		<br/><br/>
		Autor:<input type="text" id="lularz" name="autor">
		<br/><br/>
		Wydawnictwo:<input type="text" id="lularz" name="wydawnictwo">
		<br/><br/>
		Rok wydania:<input type="data" id="lularz" name="rok_wydania">
		<br/><br/>
		<?php
	require_once "connect.php";
	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
	if($polaczenie->connect_error!=0)
	{
		echo "Błąd połączenia";
	}
	else
	{
		$zapytanie=$polaczenie->query("SELECT * FROM kategorie");
		if($zapytanie->num_rows>0)
		{
			echo "Wybierz kategorię: <br/>";
			echo '<select name="kategorie" >';
			while ($i=$zapytanie->fetvh_assoc())
			{
				echo '<option value"'.$i['Id_kategorii'].'">'.$i['Nazwa'].'</option>';
			}
			echo '</select> <br/><br/>';
		}
		else
		{
			echo "Nie ma żadnej kategorii w bazie danych";
		}
	}
?>
		<br/><br/>
		Opis:<input type="text" id="lularz" name="opis">
		<br/><br/>
		<submit id="lol" role="button">Dodaj książkę</submit>
	</form>
	<br/><br/>
	<a href="bib.php"><button class="button-78" role="button">Wróć</button></a>
	</div>
</div>
</body>
</html>

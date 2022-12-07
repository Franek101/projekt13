<?php
 session_start();
 
 require_once "strona.php";
 
 $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
 
 if($polaczenie->connect_errno!=0)
 {
	echo "Error: ".$polaczenie->connect_errno;
 }
 else
 {
	 $login = $_POST['login'];
     $haslo = $_POST['haslo'];
	 
	 $sql = "SELECT * FROM uczniowie WHERE Login='$login' AND Haslo='$haslo'";
	 
	 if($rezultat = @$polaczenie->query($sql))
		 //$rezultat to zmiennna przechowywująca wiersz z tabeli dla danego ucznia
	 {
		 $ilu_userow = $rezultat->num_rows;
		 if($ilu_userow>0)
		 {
			 $wiersz = $rezultat->fetch_assoc();
			 $name = $wiersz['Imie'];
			 
			 $rezultat->close();
			 
			 
			 
			 header('Location: strona.php');
			 
		 }else{
			 
		 }
	 }
	 
	 $polaczenie->close();
 
 }

 

?>
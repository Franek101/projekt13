<?php
 
	session_start();
 
	if (isset($_POST['email']))
	{
		//Udana walidacja? Załóżmy, że tak!
		$wszystko_OK=true;
 
		//Sprawdź poprawność nickname'a
		$nick = $_POST['nick'];
 
		//Sprawdzenie długości nicka
		if ((strlen($nick)<3) || (strlen($nick)>20))
		{
			$wszystko_OK=false;
			$_SESSION['e_nick']="Nick musi posiadać od 3 do 20 znaków!";
		}
 
		if (ctype_alnum($nick)==false)
		{
			$wszystko_OK=false;
			$_SESSION['e_nick']="Nick może składać się tylko z liter i cyfr (bez polskich znaków)";
		}
 
		// Sprawdź poprawność adresu email
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
 
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
		{
			$wszystko_OK=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail!";
		}
 
		//Sprawdź poprawność hasła
		$haslo1 = $_POST['haslo1'];
		$haslo2 = $_POST['haslo2'];
 
		if ((strlen($haslo1)<8) || (strlen($haslo1)>20))
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Hasło musi posiadać od 8 do 20 znaków!";
		}
 
		if ($haslo1!=$haslo2)
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Podane hasła nie są identyczne!";
		}	
 
 
		//Czy zaakceptowano regulamin?
		if (!isset($_POST['regulamin']))
		{
			$wszystko_OK=false;
			$_SESSION['e_regulamin']="Potwierdź akceptację regulaminu!";
		}				
 
		//Sprawdź poprawność reszty danych:
		$imie=$_POST['imie'];
		$nazwisko=$_POST['nazwisko'];
		$miejscowosc=$_POST['miejscowosc'];
		$ulica=$_POST['ulica'];
		$nr_domu=$_POST['nr_domu'];
		$kod_pocztowy=$_POST['kod_pocztowy'];
		$data_urodzenia=$_POST['data_urodzenia'];
		$telefon=$_POST['telefon'];
		$plec=$_POST['plec'];
 
		//Zapamiętaj wprowadzone dane
 
		$_SESSION['fr_imie']=$imie;
		$_SESSION['fr_nazwisko']=$nazwisko;
		$_SESSION['fr_nick'] = $nick;
		$_SESSION['fr_haslo1'] = $haslo1;
		$_SESSION['fr_haslo2'] = $haslo2;
		$_SESSION['fr_miejscowosc'] = $miejscowosc;
		$_SESSION['fr_ulica'] = $ulica;
		$_SESSION['fr_nr_domu'] = $nr_domu;
		$_SESSION['fr_kod_pocztowy'] = $kod_pocztowy;
		$_SESSION['fr_data_urodzenia'] = $data_urodzenia;
		$_SESSION['fr_telefon'] = $telefon;
		$_SESSION['fr_email'] = $email;
		$_SESSION['fr_plec'] = $plec;
 
 
		if (isset($_POST['regulamin'])) $_SESSION['fr_regulamin'] = true;
 
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
 
		try 
		{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				//Czy email już istnieje?
				$rezultat = $polaczenie->query("SELECT id_ucznia FROM uczniowie WHERE email='$email'");
 
				if (!$rezultat) throw new Exception($polaczenie->error);
 
				$ile_takich_maili = $rezultat->num_rows;
				if($ile_takich_maili>0)
				{
					$wszystko_OK=false;
					$_SESSION['e_email']="Istnieje już konto przypisane do tego adresu e-mail!";
				}		
 
				//Czy nick jest już zarezerwowany?
				$rezultat = $polaczenie->query("SELECT id_ucznia FROM uczniowie WHERE login='$nick'");
 
				if (!$rezultat) throw new Exception($polaczenie->error);
 
				$ile_takich_nickow = $rezultat->num_rows;
				if($ile_takich_nickow>0)
				{
					$wszystko_OK=false;
					$_SESSION['e_nick']="Istnieje już użytkownik o takim nicku! Wybierz inny.";
				}
 
				if ($wszystko_OK==true)
				{
					//Hurra, wszystkie testy zaliczone, dodajemy gracza do bazy
 
					if ($polaczenie->query("INSERT INTO uczniowie VALUES (NULL, '$imie', '$nazwisko', '$nick', '$haslo1', '$miejscowosc', '$ulica', '$nr_domu', '$kod_pocztowy','$data_urodzenia','$telefon','$email','$plec')"))
					{
						$_SESSION['udanarejestracja']=true;
						header('Location: index.php');
					}
					else
					{
						throw new Exception($polaczenie->error);
					}
 
				}
 
				$polaczenie->close();
			}
 
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
		}
 
	}
 
 
?>
 
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Biblioteka online - załóż darmowe konto!</title>
	<link rel="stylesheet" href="style.css">
 
	<style>
		.error
		{
			color:red;
			margin-top: 10px;
			margin-bottom: 10px;
		}
	</style>
</head>
 
<body >


<div class="corpus">
 
	<form method="post">
 
		Imię:<br /> <input type="text" id="lularz" value="<?php
			if(isset($_SESSION['fr_imie']))
			{
				echo $_SESSION['fr_imie'];
				unset($_SESSION['fr_imie']);
			}
		?>" name="imie" /><br />
 
		Nazwisko:<br /> <input type="text" id="lularz" value="<?php
			if(isset($_SESSION['fr_nazwisko']))
			{
				echo$_SESSION['fr_nazwisko'];
				unset($_SESSION['fr_nazwisko']);
			}
		?>" name="nazwisko" /><br />
 
		Nickname: <br /> <input type="text" id="lularz" value="<?php
			if(isset($_SESSION['fr_nick']))
			{
				echo$_SESSION['fr_nick'];
				unset($_SESSION['fr_nick']);
			}
		?>" name="nick" /><br />
 
		<?php
			if(isset($_SESSION['e_nick']))
			{
				echo'<div class="error">'.$_SESSION['e_nick'].'</div>';
				unset($_SESSION['e_nick']);
			}
		?>
 
				Twoje hasło: <br /> <input type="password" id="lularz" value="<?php
			if(isset($_SESSION['fr_haslo1']))
			{
				echo$_SESSION['fr_haslo1'];
				unset($_SESSION['fr_haslo1']);
			}
		?>" name="haslo1" /><br />
 
		<?php
			if(isset($_SESSION['e_haslo']))
			{
				echo'<div class="error">'.$_SESSION['e_haslo'].'</div>';
				unset($_SESSION['e_haslo']);
			}
		?>		
 
		Powtórz hasło: <br /> <input type="password" id="lularz" value="<?php
			if(isset($_SESSION['fr_haslo2']))
			{
				echo$_SESSION['fr_haslo2'];
				unset($_SESSION['fr_haslo2']);
			}
		?>" name="haslo2" /><br />
 
		Miejscowość: <br /> <input type="text" id="lularz" value="<?php
			if(isset($_SESSION['fr_miejscowosc']))
			{
				echo$_SESSION['fr_miejscowosc'];
				unset($_SESSION['fr_miejscowosc']);
			}
		?>" name="miejscowosc" /><br />
 
		Ulica: <br /> <input type="text" id="lularz" value="<?php
			if(isset($_SESSION['fr_ulica']))
			{
				echo$_SESSION['fr_ulica'];
				unset($_SESSION['fr_ulica']);
			}
		?>" name="ulica" /><br />
 
		Numer Domu: <br /> <input type="text" id="lularz" value="<?php
			if(isset($_SESSION['fr_nr_domu']))
			{
				echo$_SESSION['fr_nr_domu'];
				unset($_SESSION['fr_nr_domu']);
			}
		?>" name="nr_domu" /><br />
 
		Kod Pocztowy: <br /> <input type="text" id="lularz" value="<?php
			if(isset($_SESSION['fr_kod_pocztowy']))
			{
				echo$_SESSION['fr_kod_pocztowy'];
				unset($_SESSION['fr_kod_pocztowy']);
			}
		?>" name="kod_pocztowy" /><br />
 
		Data Urodzenia: <br /> <input type="date" value="<?php
			if(isset($_SESSION['fr_data_urodzenia']))
			{
				echo$_SESSION['fr_data_urodzenia'];
				unset($_SESSION['fr_data_urodzenia']);
			}
		?>" name="data_urodzenia" /><br />
 
		Telefon: <br /> <input type="text" id="lularz" value="<?php
			if(isset($_SESSION['fr_telefon']))
			{
				echo$_SESSION['fr_telefon'];
				unset($_SESSION['fr_telefon']);
			}
		?>" name="telefon" /><br />
 
		E-mail: <br /> <input type="text" id="lularz" value="<?php
			if(isset($_SESSION['fr_email']))
			{
				echo$_SESSION['fr_email'];
				unset($_SESSION['fr_email']);
			}
		?>" name="email" /><br />
 
		<?php
			if(isset($_SESSION['e_email']))
			{
				echo'<div class="error">'.$_SESSION['e_email'].'</div>';
				unset($_SESSION['e_email']);
			}
		?>
 
		Płeć: 
		<label><br /> 
			<input type="radio" value="M" name="plec">Mężczyzna</input> 
			<br /> <input type="radio" value="K" name="plec">Kobieta</input>
		</label> <br />
 
 
		<label>
			<input type="checkbox" name="regulamin" <?php
			if(isset($_SESSION['fr_regulamin']))
			{
				echo"checked";
				unset($_SESSION['fr_regulamin']);
			}
				?>/> Akceptuję regulamin
		</label>
 
		<?php
			if (isset($_SESSION['e_regulamin']))
			{
				echo'<div class="error">'.$_SESSION['e_regulamin'].'</div>';
				unset($_SESSION['e_regulamin']);
			}
		?>	
 
		<br />
 
		<input type="submit" value="Zarejestruj się" />
 
	</form>
		</div>
</body>
</html>
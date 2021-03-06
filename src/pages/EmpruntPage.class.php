<?php

class EmpruntPage extends Layout{
	private $statusrend="";
	private $statusemp="";
	
	public function __construct()
	{
		if(!isset($_SESSION['loggedin']))
			die("Bad request");
		$this->pageTitle = "Emprunts";
		if(isset($_GET['rendre']))
		{
			$testifgood = Database::query("SELECT id_emprunt FROM EMPRUNT WHERE date_rendu is null AND id_emprunt = ".$_GET['rendre']." AND id_eleve = ".$_SESSION['loggedin']);
			if($testifgood->num_rows ==1)
			{
				$sql = "UPDATE EMPRUNT SET date_rendu = DATE(NOW()) WHERE id_emprunt = ".$_GET['rendre'];
				if(Database::query($sql))
					$this->statusrend="<font color='green'>Vous avez bien rendu le livre</font>";
				else {
					$this->statusrend="<font color='red'>Erreur inconnue</font>";
				}
			}
			else
				$this->statusrend="<font color='red'>Requete invalide...</font>";
			
		}
		if(isset($_POST['posted']))
		{
			// Nombre d'exemplaires dispos
			$query = "SELECT EXEMPLAIRE.id_exemplaire
			FROM EXEMPLAIRE LEFT JOIN LIVRE_EMPRUNTE ON EXEMPLAIRE.id_exemplaire = LIVRE_EMPRUNTE.id_exemplaire 
			WHERE EXEMPLAIRE.empruntable = TRUE AND LIVRE_EMPRUNTE.id_emprunt is null AND EXEMPLAIRE.id_livre = ".$_POST['id_li'];
			$exempdispo = Database::query($query);
			
			if($exempdispo->num_rows > 0)
			{
				$firstex = Database::fetch();
				$query = "INSERT INTO EMPRUNT (id_eleve, date_emprunt, id_exemplaire) values ( ".$_SESSION['loggedin'].", DATE(NOW()), ".$firstex['id_exemplaire'].")";
				if(Database::query($query))
					$this->statusemp = "<font color='green'>Vous avez bien emprunté le livre</font>";
				else
					$this->statusemp = "<font color='red'>Vous n'avez pas pu emprunter le livre</font>";
			}
			else
			{
				$this->statusemp="Erreur, il n'y a aucun exemplaire disponible de ce livre";
			}
		}
	}
	// cette fonction est ce que va afficher la page web
	public function bodyHTML() {
?>
<style>
	input {margin : 10px;}
</style>
    <script>
$(function() {
 
    $( "button" ).button();

});
    </script>
<h1>Liste de vos emprunts en cours</h1>
<?php
echo $this->statusrend;
$sql= "SELECT * FROM EMPRUNT WHERE date_rendu is null AND id_eleve = ".$_SESSION['loggedin'];
$result = Database::query($sql);
if(!($result))
{
	echo "Erreur de requete : ".Database::errorMsg();
}
elseif($result->num_rows==0)
{
	echo "<p>Vous n'avez aucun emprunt en cours</p>";
}
else
{
	echo "<ul>";
	while($res = Database::fetch($result))
	{
		$sql = "SELECT DISTINCT LIVRE.id_livre, LIVRE.titre 
		FROM EXEMPLAIRE LEFT JOIN LIVRE ON EXEMPLAIRE.id_livre = LIVRE.id_livre 
		WHERE EXEMPLAIRE.id_exemplaire = ".$res['id_exemplaire'];
		$livretitre = Database::query($sql);
		$livretitre = Database::fetch($livretitre);
		$livretitre = $livretitre['titre'];
		echo "<li>".$livretitre." depuis le ".$res['date_emprunt'].". <a href='index.php?action=emprunt&rendre=".$res['id_emprunt']."'>Rendre ce livre</a></li>";
	} 
	echo "</ul>";
}
?>
<h1>Emprunter un livre</h1>
<?php echo $this->statusemp; ?>
<form action="index.php?action=emprunt" method=post>
<input type="hidden" name="posted" value="yes"/>
<table >
<tr>
	<td><label for="id_li">Livres disponibles à l'emprunt</label></td>
<td><select name="id_li" id="id_li">

        <?php
        $sql = "SELECT DISTINCT EXEMPLAIRE.id_livre 
        		FROM EXEMPLAIRE LEFT JOIN LIVRE_EMPRUNTE ON EXEMPLAIRE.id_exemplaire = LIVRE_EMPRUNTE.id_exemplaire
				WHERE EXEMPLAIRE.empruntable = TRUE AND LIVRE_EMPRUNTE.id_emprunt is null";
        $result=Database::query($sql);
			if(!($result))
			{
				echo "Erreur de requete : ".Database::errorMsg();
			}
			else
			{
				while($res = Database::fetch($result))
				{
					$livre = new Livre();
					$livre->getFromDatabase($res['id_livre']);
					
					echo "<option value='".$livre->id."'>".$livre->titre."</option>";
				} 
			}
        ?>
</select></td>
</tr>

</table>
<button id="post">Emprunter</button>
</form>
	
<?php
	}
}
?>

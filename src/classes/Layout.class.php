<?php

// Classe de base contenant tout le html autour de corps de page
abstract class Layout {
	
	protected $AJAX = false;
	protected $pageTitle = "Default";
	protected $eleve_co;
	
	public function showHTML()
	{
		return !($this->AJAX);
	}
	
	public function toHTML() {
		$this->headerHTML();
		$this->bodyHTML();
		$this->footerHTML();
	}
	
	public function bodyHTML() {
		echo "should not be initialized <br />";
	}
	
	public function headerPlus() {
	}
	
	public function headerHTML() {

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

		<title>Projet SGBD - <?php echo $this->pageTitle; ?></title>
		<meta name="description" content="" />
		<meta name="author" content="Pacien Boisson" />

		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<link rel="shortcut icon" href="ressources/favicon.ico" />
		<link rel="apple-touch-icon" href="/apple-touch-icon.png" />
		
		<link rel="stylesheet" href="ressources/style.css" />
		<link rel="stylesheet" href="ressources/jquery-ui-1.9.2.css" />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<script src="ressources/jquery-1.8.3.min.js"></script>
		<script src="ressources/jquery-ui-1.9.2.min.js"></script>
		<script type="text/javascript" src="ressources/script.js"></script>
		<?php $this->headerPlus(); ?>
	</head>

	<body>
		<div id="menu">
			<nav>
				<ul>
					<li>
						<a href="index.php">Accueil</a>
					</li>
					<li>
						<a href="?action=eleves">Liste d'élèves</a>
					</li>

					<li>
						<a href="?action=bureau">Le bureau</a>
					</li>
					<li>
						<a href="?action=evt">Evenements</a>
					</li>
					<li>
						<a href="?action=livres">Les livres</a>
					</li>
					<li>
						<a href="?action=jeux">Les jeux</a>
					</li>
					<li>
						<a href="?action=stats">Statistiques</a>
					</li>
					<li>
						<a href="?action=sql">Requete SQL</a>
					</li>
					<li>
						<a href="?action=tools">Outils</a>
					</li>
				</ul>
			</nav>
	<div id="connect">
<ul>
<?php
	if(isset($_SESSION['loggedin']))
	{
		echo "<li>Connecté en tant que <i>".Core::$eleve_co->login."</i></li><br />";
?>

		<li>
			<a href="?action=comment">Commenter</a>
		</li>
		<li>
			<a href="?action=emprunt">Emprunter</a>
		</li>
		<li>
			<a href="?action=participer">Participer</a>
		</li>
		<li>
			<a href='index.php?action=logout'>Se déconnecter</a>
		</li>
		

<?php	}
	else 
	{
		echo "<li><a href='index.php?action=login'>Se connecter</a></li>";
	}

?>
</ul>
	</div>
	</div>
	<div id="right">
		<div id="body">    
<?php
			}

			public function footerHTML() {
		?>
	<div id="debug">
		<b>Debug :</b><br />
		<?php Core::debugHTML(); ?>
	</div>
</div>
	
		<div id="footer">
			<footer>
				<p>
					&copy; Copyright nsarlin, pboisson, staton
				</p>
			</footer>
		</div>

	</div>
	</body>
</html>


<?php
	}

	}
?>

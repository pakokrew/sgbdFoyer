<?php

class Accueil extends Layout{
	
	public function __construct()
	{
		$this->pageTitle = "Accueil";
	}
	// cette fonction est ce que va afficher la page web
	public function bodyHTML() {
?>

<h1>Foyer de l'ENSEIRB-MATMECA</h1>
<p>Site de gestion du foyer.</p>

<?php
	}
}
?>

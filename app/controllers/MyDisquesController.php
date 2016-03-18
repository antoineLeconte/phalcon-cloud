<?php

class MyDisquesController extends \ControllerBase {
	/**
	 * Affiches les disques de l'utilisateur
	 */
	public function indexAction(){
		//TODO 4.2
		$user = Auth::getUser($this);
		$bootstrap = $this->jquery->bootstrap();

		$this->view->setVar('dIdUser', $user->getId());

		$listeDisques = Disque::find(Array(
			'conditions' => 'idUtilisateur = ?1',
			'bind' => array(1 => $user->getId())
		));


		$infosDisques = Array();

		foreach($listeDisques as $disque){
			$infosDisque = $this->recupererInfosDisque($disque);

			// Création de la barre d'occupation de l'esapce disque
			$quotaOctets = $infosDisque['tailleMax'] * ModelUtils::sizeConverter($infosDisque['uniteTailleMax']);
			$tauxOccupation = floor(($infosDisque['occupationOctets'] / $quotaOctets) * 10000) / 100;

			$bootstrap->htmlProgressbar("barreOccupation" . $disque->getId(), "info", $tauxOccupation)
				->setStyleLimits(Array("progress-bar-info" => 10, "progress-bar-success" => 50,
					"progress-bar-warning" => 80, "progress-bar-danger" => 100))
				->setStriped(true)
				->showCaption(true);

			// Création du bouton d'envoi vers Scan/:id
			$bootstrap->htmlGlyphButton("boutonOuverture" . $disque->getId(), "glyphicon-folder-open", "Ouvrir")
				->addToProperty("class", "btOpen") // Quand ça existe déjà
				->setProperty("data-ajax", $disque->getId()); // Quand c'est pas déjà défini

			$this->jquery->getOnClick("#boutonOuverture" . $disque->getId(), 'scan/1/', '#content',
				Array('attr' => "data-ajax"));

			$infosDisques[$disque->getId()] = $infosDisque;
		}

		$this->view->setVar('loginUser', $user->getLogin());
		$this->view->setVar('disques', $listeDisques);
		$this->view->setVar('infosDisques', $infosDisques);

		$this->jquery->compile($this->view);
	}

	// TODO : Convertir la valeur en octets de l'occupation disque en une unité plus approprié
	private function recupererInfosDisque($disque){
		$tailleMaxDisque = ModelUtils::getDisqueTarif($disque);
		$occupationDisque = Historique::findFirst(array(
			"conditions" => "idDisque = ?1",
			"bind" => array(1 => $disque->getId()),
			"order" => "date DESC"
		));

		$quota = $tailleMaxDisque->getQuota();
		$uniteQuota = $tailleMaxDisque->getUnite();
		$espaceUtilise = $occupationDisque->getOccupation();

		$infosDisque['tailleMax'] = $quota;
		$infosDisque['uniteTailleMax'] = $uniteQuota;
		$infosDisque['occupationOctets'] = $espaceUtilise; // Inutile pour affichage, mais pratique pour calcul taux occupation
		$infosDisque['occupation'] = $espaceUtilise;
		$infosDisque['uniteOcupation'] = ''; // TODO : Trouver la putain de bonne unité !!!1

		return $infosDisque;
	}
}
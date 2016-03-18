<?php

class MyDisquesController extends \ControllerBase {
	/**
	 * Affiches les disques de l'utilisateur
	 */
	public function indexAction(){
		//TODO 4.2
		$user = Auth::getUser($this);

		$this->view->setVar('dIdUser', $user->getId());

		$listeDisques = Disque::find(Array(
			'conditions' => 'idUtilisateur = ?1',
			'bind' => array(1 => $user->getId())
		));


		$infosDisques = Array();

		// TODO : Rassembler occupation disque et infos disques dans un seul tableau
		// TODO : Convertir la valeur en octets de l'occupation disque
		foreach($listeDisques as $disque){
			$infosDisques[$disque->getId()] = ModelUtils::getDisqueTarif($disque);
			$occupationDisque[$disque->getId()] = Historique::findFirst(array(
				"conditions" => "idDisque = ?1",
				"bind" => array(1 => $disque->getId()),
				"order" => "date DESC"
			));
		}

		$this->view->setVar('loginUser', $user->getLogin());
		$this->view->setVar('disques', $listeDisques);
		$this->view->setVar('infosDisques', $infosDisques);
		$this->view->setVar('occupationDisque', $occupationDisque);

		$this->jquery->compile($this->view);
	}
}
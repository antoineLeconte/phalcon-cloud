<?php
class ModelUtils {
	/**
	 * Retourne l'occupation de $disque du jour trouvée dans l'historique
	 * @param config du cloud, accès par $this->config->cloud dans un contrôleur
	 * @param Disque $disque
	 * @return int occupation de $disque
	 */
	public static function getDisqueOccupation($cloud,$disque){
		$histo=DirectoryUtils::updateDaySize($cloud,$disque);
		return $histo->getOccupation();
	}

	/**
	 * Retourne le tarif appliqué actuellement à $disque
	 * @param Disque $disque
	 * @return Tarif tarif actuel de $disque
	 */
	public static function getDisqueTarif($disque){
		$disqueTarif = DisqueTarif::findFirst(Array(
				"conditions" => "idDisque = ?1",
				"bind" => Array(1 => $disque->getId())
			));
		$tarif = Tarif::findFirst(Array(
			"conditions" => "id = ?1",
			"bind" => Array(1 => $disqueTarif->getIdTarif())
		));
		return $tarif;
	}

	public static function recupererInfosDisque($cloud, $disque){
		$tailleMaxDisque = ModelUtils::getDisqueTarif($disque);

		$quota = $tailleMaxDisque->getQuota();
		$uniteQuota = $tailleMaxDisque->getUnite();
		$espaceUtilise = ModelUtils::getDisqueOccupation($cloud, $disque);

		$infosDisque['tailleMax'] = $quota;
		$infosDisque['uniteTailleMax'] = $uniteQuota;
		$infosDisque['occupationOctets'] = $espaceUtilise; // Inutile pour affichage, mais pratique pour calcul taux occupation

		$indiceUnite = 0;
		$units= ["o","Ko", "Mo", "Go", "To", "Po"];
		while($espaceUtilise >= pow(1024, $indiceUnite + 1) && $indiceUnite < count($units)){
			$indiceUnite++;
		}

		$infosDisque['occupation'] = round($espaceUtilise / ModelUtils::sizeConverter($units[$indiceUnite]), 2);
		$infosDisque['uniteOccupation'] = $units[$indiceUnite];

		return $infosDisque;
	}

	/**
	 * Convertit une unité en nombre d'octets (1 Ko= 1024 o)
	 * @param String $unit unité à convertir (o, Ko, Mo, Go, To, Po)
	 * @return int
	 */
	public static function sizeConverter($unit){
		$units= ["Ko"=>1024,"Mo"=>1024*1024,"Go"=>pow(1024, 3),"To"=>pow(1024, 4),"Po"=>pow(1024,5),"o"=>1];
		return $units[$unit];
	}

	/**
	 * Affiche un tableau d'objets au format HTML
	 * @param array $array Tableau d'objets (doivent implémenter __toString)
	 * @param string $mainTag
	 * @param string $tag
	 * @param string $sep séparateur entre objets
	 * @return string Représentation des objets au format HTML
	 */
	public static function arrayAsHtml($array,$mainTag="div",$tag="span class='label label-info'",$sep="&nbsp;"){
		$result="<{$mainTag}>";
		foreach ($array as $o){
			$result.="<{$tag}>".$o."</{$tag}>".$sep;
		}
		$result.="</{$mainTag}>";
		return $result;
	}
}
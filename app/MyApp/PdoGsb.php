<?php
namespace App\MyApp;
use PDO;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str; //pour générer des chaînes aléatoires pour le champ id
class PdoGsb{
        private static $serveur;
        private static $bdd;
        private static $user;
        private static $mdp;
        private  $monPdo;
	
/**
 * crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */				
	public function __construct(){
        
        self::$serveur='mysql:host=' . Config::get('database.connections.mysql.host');
        self::$bdd='dbname=' . Config::get('database.connections.mysql.database');
        self::$user=Config::get('database.connections.mysql.username') ;
        self::$mdp=Config::get('database.connections.mysql.password');	  
        $this->monPdo = new PDO(self::$serveur.';'.self::$bdd, self::$user, self::$mdp); 
  		$this->monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		$this->monPdo =null;
	}
	

/**
 * Retourne les informations d'un visiteur
 
 * @param $login 
 * @param $mdp
 * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
*/
	public function getInfosVisiteur($login, $mdp){
		$req = "select visiteur.id as id, visiteur.nom as nom, visiteur.prenom as prenom from visiteur 
        where visiteur.login='" . $login . "' and visiteur.mdp='" . $mdp ."'";
    	$rs = $this->monPdo->query($req);
		$ligne = $rs->fetch();
		return $ligne;
	}



/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
 * concernées par les deux arguments
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
*/
	public function getLesFraisForfait($idVisiteur, $mois){
		$req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, 
		lignefraisforfait.quantite as quantite from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idvisiteur ='$idVisiteur' and lignefraisforfait.mois='$mois' 
		order by lignefraisforfait.idfraisforfait";	
		$res = $this->monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes; 
	}
/**
 * Retourne tous les id de la table FraisForfait
 
 * @return un tableau associatif 
*/
	public function getLesIdFrais(){
		$req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
		$res = $this->monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
/**
 * Met à jour la table ligneFraisForfait
 
 * Met à jour la table ligneFraisForfait pour un visiteur et
 * un mois donné en enregistrant les nouveaux montants
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
 * @return un tableau associatif 
*/
	public function majFraisForfait($idVisiteur, $mois, $lesFrais){
		$lesCles = array_keys($lesFrais);
		foreach($lesCles as $unIdFrais){
			$qte = $lesFrais[$unIdFrais];
			$req = "update lignefraisforfait set lignefraisforfait.quantite = $qte
			where lignefraisforfait.idvisiteur = '$idVisiteur' and lignefraisforfait.mois = '$mois'
			and lignefraisforfait.idfraisforfait = '$unIdFrais'";
			$this->monPdo->exec($req);
		}
		
	}

/**
 * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return vrai ou faux 
*/	
	public function estPremierFraisMois($idVisiteur,$mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.mois = '$mois' and fichefrais.idvisiteur = '$idVisiteur'";
		$res = $this->monPdo->query($req);
		$laLigne = $res->fetch();
		if($laLigne['nblignesfrais'] == 0){
			$ok = true;
		}
		return $ok;
	}
/**
 * Retourne le dernier mois en cours d'un visiteur
 
 * @param $idVisiteur 
 * @return le mois sous la forme aaaamm
*/	
	public function dernierMoisSaisi($idVisiteur){
		$req = "select max(mois) as dernierMois from fichefrais where fichefrais.idvisiteur = '$idVisiteur'";
		$res = $this->monPdo->query($req);
		$laLigne = $res->fetch();
		$dernierMois = $laLigne['dernierMois'];
		return $dernierMois;
	}
	
/**
 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés
 
 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
 * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function creeNouvellesLignesFrais($idVisiteur,$mois){
		$dernierMois = $this->dernierMoisSaisi($idVisiteur);
		$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur,$dernierMois);
		if($laDerniereFiche['idEtat']=='CR'){
				$this->majEtatFicheFrais($idVisiteur, $dernierMois,'CL');
				
		}
		$req = "insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idVisiteur','$mois',0,0,now(),'CR')";
		$this->monPdo->exec($req);
		$lesIdFrais = $this->getLesIdFrais();
		foreach($lesIdFrais as $uneLigneIdFrais){
			$unIdFrais = $uneLigneIdFrais['idfrais'];
			$req = "insert into lignefraisforfait(idvisiteur,mois,idFraisForfait,quantite) 
			values('$idVisiteur','$mois','$unIdFrais',0)";
			$this->monPdo->exec($req);
		 }
	}


/**
 * Retourne les mois pour lesquel un visiteur a une fiche de frais
 
 * @param $idVisiteur 
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
*/
	public function getLesMoisDisponibles($idVisiteur){
		$req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' 
		order by fichefrais.mois desc ";
		$res = $this->monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$lesMois["$mois"]=array(
		     "mois"=>"$mois",
		    "numAnnee"  => "$numAnnee",
			"numMois"  => "$numMois"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesMois;
	}
/**
 * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
*/	
	public function getLesInfosFicheFrais($idVisiteur,$mois){
		$req = "select fichefrais.idEtat as idEtat, fichefrais.dateModif as dateModif, fichefrais.nbJustificatifs as nbJustificatifs, 
			fichefrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join etat on fichefrais.idEtat = etat.id 
			where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = $this->monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;
	}
/**
 * Modifie l'état et la date de modification d'une fiche de frais
 
 * Modifie le champ idEtat et met la date de modif à aujourd'hui
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 */
 
	public function majEtatFicheFrais($idVisiteur,$mois,$etat){
		$req = "update ficheFrais set idEtat = '$etat', dateModif = now() 
		where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$this->monPdo->exec($req);
	}

//2A

	public function afficherVisiteurs(){
		$req = "SELECT id, nom, prenom, login, mdp, adresse, cp, ville, dateEmbauche FROM visiteur";
		$res = $this->monPdo->prepare($req);
		$res->execute();
		$laLigne = $res->fetchAll(PDO::FETCH_ASSOC);
		return $laLigne;
	}

	function genererLogin($prenom, $nom) {
		if (empty($prenom) || empty($nom)) {
			return null;
		}
		$initialePrenom = strtoupper(substr($prenom, 0, 1));
		$login = $initialePrenom . $nom;
	
		return $login;
	}

	public function majVisiteur($nom,$prenom,$login,$adresse,$cp,$ville,$de){
		$id = Str::random(3);
		$mdp = Str::random(5);
		$req = "INSERT INTO visiteur (id,nom,prenom,login,mdp,adresse,cp,ville,dateEmbauche)
		VALUES (:id,:nom,:prenom,:login,:mdp,:adresse,:cp,:ville,:dateEmbauche)";
		$res = $this->monPdo->prepare($req);

		$res->bindParam(':id', $id, PDO::PARAM_STR);
		$res->bindParam(':nom', $nom, PDO::PARAM_STR); 
		$res->bindParam(':prenom', $prenom, PDO::PARAM_STR); 
		$res->bindParam(':login', $login, PDO::PARAM_STR);
		$res->bindParam(':mdp', $mdp, PDO::PARAM_STR);
		$res->bindParam(':adresse', $adresse, PDO::PARAM_STR); 
		$res->bindParam(':cp', $cp, PDO::PARAM_STR); 
		$res->bindParam(':ville', $ville, PDO::PARAM_STR); 
		$res->bindParam(':dateEmbauche', $de, PDO::PARAM_STR);  

		$res->execute();
		$laLigne = $res->fetchAll(PDO::FETCH_ASSOC);
		return $laLigne;
	}

	public function afficherLeVisiteur($id)
	{
		$req = "SELECT id, nom, prenom, login, mdp, adresse, cp, ville, dateEmbauche FROM visiteur WHERE id = :id";
		$res = $this->monPdo->prepare($req);
		$res->bindParam(':id', $id, PDO::PARAM_STR); 
		$res->execute();
		$laLigne = $res->fetch(PDO::FETCH_ASSOC);
		return $laLigne;
	}

	public function updateVisiteur($nom,$prenom,$adresse,$cp,$ville,$de)
	{
		$req = "UPDATE visiteur v
				set v.nom = :nom, prenom = :prenom, adresse = :adresse, cp = :cp, ville = :ville, dateEmbauche = :de WHERE id = :id";
		$res = $this->monPdo->prepare($req);
		$res->bindParam(':nom', $nom, PDO::PARAM_STR); 
		$res->bindParam(':prenom', $prenom, PDO::PARAM_STR); 
		$res->bindParam(':adresse', $adresse, PDO::PARAM_STR); 
		$res->bindParam(':cp', $cp, PDO::PARAM_STR); 
		$res->bindParam(':ville', $ville, PDO::PARAM_STR); 
		$res->bindParam(':de', $de, PDO::PARAM_STR); 
		$res->bindParam(':id', $id, PDO::PARAM_STR); 
		$res->execute();
		$laLigne = $res->fetch(PDO::FETCH_ASSOC);
		return $laLigne;
	}

	//11 et 12

	public function afficherVisiteursParDE(){
		$req = "SELECT id, nom, prenom, login, mdp, adresse, cp, ville, dateEmbauche FROM visiteur
				ORDER BY dateEmbauche";
		$res = $this->monPdo->prepare($req);
		//$lesVisiteurs = array();
		$res->execute();
		$laLigne = $res->fetchAll(PDO::FETCH_ASSOC);
		return $laLigne;
	}
}

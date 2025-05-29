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
     * Retourne les informations d'un comptable
     */
public static function getInfosCompta($login, $mdp)
{
    $pdo = new self();
    $req = "SELECT nom, prenom, login FROM compta WHERE login = :login AND mdp = :mdp";
    $stmt = $pdo->monPdo->prepare($req);
    $stmt->bindParam(':login', $login, PDO::PARAM_STR);
    $stmt->bindParam(':mdp', $mdp, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
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
// Recherche l'utilisateur par login
public function getInfosVisiteurParLogin($login)
{
    $req = "SELECT id, nom, prenom, mdp FROM visiteur WHERE login = :login";
    $stmt = $this->monPdo->prepare($req);
    $stmt->bindParam(':login', $login, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC); 
}


public function updatePassword($login, $newPassword)
{
    $req = "UPDATE visiteur SET mdp = :newPassword WHERE login = :login";
    $stmt = $this->monPdo->prepare($req);
    $stmt->bindParam(':login', $login, PDO::PARAM_STR);
    $stmt->bindParam(':newPassword', $newPassword, PDO::PARAM_STR);

    // Retourne true ou false pour vérifier si la mise à jour a réussi
    return $stmt->execute();
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

//Mission 2A

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

public function modifierVisiteur($id, $nom, $prenom, $login, $mdp, $adresse, $cp, $ville, $dateEmbauche)
{
    $sql = "UPDATE visiteur 
            SET nom = :nom, prenom = :prenom, login = :login, mdp = :mdp, adresse = :adresse, 
                cp = :cp, ville = :ville, dateEmbauche = :dateEmbauche 
            WHERE id = :id";

    $stmt = $this->monPdo->prepare($sql);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':mdp', $mdp);
    $stmt->bindParam(':adresse', $adresse);
    $stmt->bindParam(':cp', $cp);
    $stmt->bindParam(':ville', $ville);
    $stmt->bindParam(':dateEmbauche', $dateEmbauche);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
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

public function updateVisiteur($id, $nom, $prenom, $adresse, $cp, $ville, $de)
{
    $req = "UPDATE visiteur 
            SET nom = :nom, prenom = :prenom, adresse = :adresse, cp = :cp, ville = :ville, dateEmbauche = :de 
            WHERE id = :id";

    $res = $this->monPdo->prepare($req);
    $res->bindParam(':nom', $nom, PDO::PARAM_STR);
    $res->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $res->bindParam(':adresse', $adresse, PDO::PARAM_STR);
    $res->bindParam(':cp', $cp, PDO::PARAM_STR);
    $res->bindParam(':ville', $ville, PDO::PARAM_STR);
    $res->bindParam(':de', $de, PDO::PARAM_STR);
    $res->bindParam(':id', $id, PDO::PARAM_STR);
    $res->execute();

    return $res->rowCount(); // Nombre de lignes modifiées
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

    public function getInfoVisiteur($id)
	{
		$req ="SELECT * FROM visiteur WHERE id = :id";
		$stmt = $this->monPdo->prepare($req);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
    public function getSupprimerVisiteur($id){
		$req = "DELETE FROM visiteur where id=:id";
		$rs = $this->monPdo->prepare($req);
		$rs->bindParam(':id', $id);
		$rs->execute();

	}

	public function supprimerFichesFraisVisiteur($idVisiteur)
{
	  // Supprimer les lignes de frais associées au visiteur
	  $reqLigneFrais = "DELETE FROM lignefraisforfait WHERE idVisiteur = :idVisiteur";
	  $rsLigneFrais = $this->monPdo->prepare($reqLigneFrais);
	  $rsLigneFrais->bindParam(':idVisiteur', $idVisiteur);
	  $rsLigneFrais->execute();

    $req = "DELETE FROM fichefrais WHERE idVisiteur = :idVisiteur";
    $rs = $this->monPdo->prepare($req);
    $rs->bindParam(':idVisiteur', $idVisiteur);
    $rs->execute();
}
//Fin Mission 2A


//Mission 2B

	public function getLesMois(){
		$req = "select fichefrais.mois as mois from fichefrais
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

	public function afficherVisiteurs2B(){
		$req = "SELECT id, nom, prenom, login, mdp, adresse, cp, ville, dateEmbauche as visiteur FROM visiteur ORDER BY nom ASC";
		$res = $this->monPdo->prepare($req);
		$res->execute();
		$laLigne = $res->fetchAll(PDO::FETCH_ASSOC);
		return $laLigne;
	}
// Méthodes pour AdminController

public static function getLesAnnees()
{
    $pdo = new self();
    $req = "SELECT DISTINCT LEFT(mois, 4) AS annee FROM fichefrais ORDER BY annee DESC";
    $res = $pdo->monPdo->query($req);
    return $res->fetchAll(PDO::FETCH_COLUMN);
}

public static function getInfosParAnnee($annee)
{
    $pdo = new self();
    $req = "SELECT visiteur.nom, visiteur.prenom, fichefrais.mois, fichefrais.montantValide
            FROM fichefrais
            INNER JOIN visiteur ON fichefrais.idVisiteur = visiteur.id
            WHERE LEFT(fichefrais.mois, 4) = :annee
            ORDER BY fichefrais.mois DESC";
    $stmt = $pdo->monPdo->prepare($req);
    $stmt->bindParam(':annee', $annee, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public static function getLesVisiteurs()
{
    $pdo = new self();
    $req = "SELECT id, nom, prenom FROM visiteur ORDER BY nom ASC";
    $res = $pdo->monPdo->query($req);
    return $res->fetchAll(PDO::FETCH_ASSOC);
}

public static function getInfosParVisiteur($idVisiteur)
{
    $pdo = new self();
    $req = "SELECT mois, montantValide
            FROM fichefrais
            WHERE idVisiteur = :idVisiteur
            ORDER BY mois DESC";
    $stmt = $pdo->monPdo->prepare($req);
    $stmt->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public static function getLesTypes()
{
    $pdo = new self();
    $req = "SELECT id, libelle FROM fraisforfait";
    $res = $pdo->monPdo->query($req);
    return $res->fetchAll(PDO::FETCH_ASSOC);
}

public static function getLesInfosParType($type)
{
    $pdo = new self();
    $req = "SELECT lignefraisforfait.mois, lignefraisforfait.idVisiteur, visiteur.nom, visiteur.prenom, lignefraisforfait.quantite
            FROM lignefraisforfait
            INNER JOIN visiteur ON lignefraisforfait.idVisiteur = visiteur.id
            WHERE idFraisForfait = :type
            ORDER BY lignefraisforfait.mois DESC";
    $stmt = $pdo->monPdo->prepare($req);
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
	
}
public function ajouterVisiteur($data)
{
    $req = "INSERT INTO visiteur (nom, prenom, login, mdp, adresse, cp, ville, dateEmbauche)
            VALUES (:nom, :prenom, :login, :mdp, :adresse, :cp, :ville, :dateEmbauche)";
    $stmt = $this->monPdo->prepare($req);
    $stmt->execute([
        ':nom' => $data['nom'],
        ':prenom' => $data['prenom'],
        ':login' => $data['login'],
        ':mdp' => $data['mdp'],
        ':adresse' => $data['adresse'],
        ':cp' => $data['cp'],
        ':ville' => $data['ville'],
        ':dateEmbauche' => $data['dateEmbauche']
    ]);
	
return $stmt->execute();
}

private function genererIdVisiteur($listeIds)
{
    $prefixe = 'V';
    $nombres = [];

    foreach ($listeIds as $id) {
        if (str_starts_with($id, $prefixe)) {
            $num = intval(substr($id, strlen($prefixe)));
            $nombres[] = $num;
        }
    }

    $max = $nombres ? max($nombres) : 0;
    $prochain = $max + 1;

    return $prefixe . str_pad($prochain, 3, '0', STR_PAD_LEFT); // ex: V011
}
}
<?php

//namespace Models;

class Projet
{
	 protected $allowed_properties = [ 'id_projet', 'id_user', 'nom', 'description',];
   protected $property = [];
	 protected $pk_name = 'id_projet';

  public $id_projet;
  public $id_user;
  public $nom;
  public $description;


	public static function get( $id_projet ) {
    $projet = new Projet();
    $db = Db::getInstance();
    $sql = "SELECT * FROM projets WHERE id_projet = '".$id_projet."'";
    $sth = $db->prepare($sql);
    $sth->execute();
    $projet->hydrate($sth->fetch());
    return $projet;
  }


///////////////////////////////////////////////////////////////////////
//////////////////// A PARTIR D'ICI CA MARCHE ///////////////////////////////////
  public static function getList() {
    $list = [];
    //on ouvre l'accès à la bdd
    $db = Db::getInstance();
    $sql = 'SELECT * FROM projets';
    $sth = $db->prepare($sql);
    $sth->execute();

    // Recuperer une liste d'objet projet
    foreach($sth->fetchAll() as $projet) {
      $curr = new Projet();
      $curr->hydrate($projet);
      $list[] = $curr;
    }

    return $list;
   }

   public static function getByUser($id_user){
    $list = [];
    //on ouvre l'accès à la bdd
    $db = Db::getInstance();
    $sql = 'SELECT * FROM projets WHERE id_user = '.$id_user;
    $sth = $db->prepare($sql);
    $sth->execute();
    $verif = sizeof($sth->fetchAll());
    if($verif == 0){
      echo "Vous n'avez aucun projet !";
      return 0;
    }

     $sth->execute();

    // Recuperer une liste d'objet projet
    foreach($sth->fetchAll() as $projet) {
      $curr = new Projet();
      $curr->hydrate($projet);
      $list[] = $curr;
    }

    return $list;
   }


  public function hydrate(array $data)
  {
    $this->id_user = $_SESSION['id'];
    //remplis les propriétés de l'objet avec un array, comme un $_POST 
     foreach($data as $property => $value){
       $this->$property = $value;
     }
  }

   public function getProperties(){
    //permet de retourner les propriétés sous forme d'un string pour injection directe dans le sql

      $prop="('".$this->id_user."', '".$this->nom."','".$this->description."')";
      echo $prop;
      return $prop;
   }
}
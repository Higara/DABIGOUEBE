<?php

//namespace App\Models;
//use \PDO;

class User{
  protected $allowed_properties = ['id_user', 'nom', 'prenom', 'id_statut', 'id_filiere', 'date_naissance', 'adresse', 'mail', 'password', 'telephone', 'cotisation', 'photo'];
  protected $pk_name            = 'id_user';


  public static function connexion($mail,$password)
  {
    //a refaire
    $nb = 0;
    $db = Db::getInstance();
    $sql = "SELECT id_statut FROM users WHERE mail='".$mail."' AND password='".$password."' LIMIT 1";
    $sth = $db->prepare($sql);
    $sth->execute();
    $nb=count($sth->fetch());
    return $nb;
  }

  public static function getStatut($mail,$password)
  {
    $db = Db::getInstance();
    $sql = "SELECT id_statut FROM users WHERE mail='$mail' AND password='$password' LIMIT 1";
    $sth = $db->prepare($sql);
    $sth->execute();
    //var_dump($sth->fetch());
    $result = $sth->fetch();
    //var_dump($result);
    return $result;
  }

  public static function getId($mail,$password){
    $db = Db::getInstance();
    $sql = "SELECT id_user FROM users WHERE mail='$mail' AND password='$password' LIMIT 1";
    $sth = $db->prepare($sql);
    $sth->execute();
    $result = $sth->fetch();
    return $result;
  }

  static function isLogged(){
      if(isset($_SESSION['User']) and isset($_SESSION['User']['mail']) and isset($_SESSION['User']['password']))
      {
        extract($_SESSION['User']);
        $nb = User::connexion($mail, $password);
        $statut = User::getStatut($mail, $password);
        $_SESSION['User']['statut']=$statut['id_statut'];
        if($nb > 0 && isset($_SESSION['User']['statut']))
        {
            return true;
        }
        return false;
      }
      return false;
   }


  //retourne un seul utilisateur
  public static function get( $id_user ) 
  {
    $user = new User();
    $db = Db::getInstance();
    $sql = "SELECT * FROM users WHERE id_user = '".$id_user."'";
    $sth = $db->prepare($sql);
    $sth->execute();
    $user->hydrate($sth->fetch());
    return $user;
  }

//retourne tous les utilisateurs
  public static function getList() 
  {
    $list = [];
    //on ouvre l'accès à la bdd
    $db = Db::getInstance();
    $sql = 'SELECT * FROM users';
    $sth = $db->prepare($sql);
    $sth->execute();
    // Recuperer une liste d'objet user
    foreach($sth->fetchAll() as $user) {
      $curr = new User();
      $curr->hydrate($user);
      $list[] = $curr;
    }

    return $list;
  }


 public function hydrate(array $data)
  {
    //remplis les propriétés de l'objet avec un array, comme un $_POST 
     foreach($data as $property => $value){
       $this->$property = $value;
     }
  }

   public function getProperties(){
    //permet de retourner les propriétés sous forme d'un string pour injection directe dans le sql
      $prop="('".$this->nom."','".$this->prenom."', ".$this->id_filiere.",'".$this->date_naissance."','".$this->adresse."','".$this->mail."','".$this->password."','".$this->telephone."')";
      echo $prop;
      return $prop;
   }
}
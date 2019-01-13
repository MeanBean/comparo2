<?php

// Système de contrôle de l'accès
// Chaque utilisateur a une clé publique et une clé privée
// La clé publique permet au système de commentaire de savoir
// si l'utilisateur peut commenter le comparo.


// pour récupérer la clé lors de la création du comparo
function get_key() {
  global $db, $_COOKIE;
  
  if ( isset($_COOKIE['comparoPublicKey']) && isset($_COOKIE['comparoPrivateKey']) ) {
    // Si la cle existe
    $sql = $db->query("SELECT public, private FROM userkeys "
                      ."WHERE public = '".$db->real_escape_string($_COOKIE['comparoPublicKey'])."' AND private = '".$_COOKIE['comparoPrivateKey']."'");
    
    $key = $sql->fetch_assoc();
    if ( $key ) {
      setcookie("comparoPublicKey", $key['public'], (time() + 60*60*24*30*2));
      setcookie("comparoPrivateKey", $key['private'], (time() + 60*60*24*30*2));
      return $key['public'];
    }
  }
    
  do {
    $public = rand_str(10, true);
    $sql = $db->query("SELECT public FROM userkeys WHERE public = '{$public}'");
  } while ( $sql->num_rows );
    
  $private = rand_str(16, true);
  
  $db->insert("userkeys", array('public' => $public, 'private' => $private));
  setcookie("comparoPublicKey", $public, (time() + 60*60*24*30*2));
  setcookie("comparoPrivateKey", $private, (time() + 60*60*24*30*2));
    
  return $public;
}

function check_key($idComparo, $privateKey) {
  global $db;
  $sql = $db->query("SELECT c.id FROM comparos c "
                    ."JOIN userkeys k ON k.public = c.code "
                    ."WHERE c.id = '".$db->real_escape_string($idComparo)."' AND k.private = '".$db->real_escape_string($privateKey)."'");

  $data = $sql->fetch_assoc();
  
  if ( $data ) {
    return $data['id'];
  }
  
  return false;
}

?>

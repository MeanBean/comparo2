<?php

class db {
  var $config = array();
  var $connexion = null;
  
  function db() {
    global $config;
    
    $this->config = $config;
    
    $this->connexion = new mysqli($this->config['mysql_server'], $this->config['mysql_user'], $this->config['mysql_pass'], $this->config['mysql_db']) or die("Connection au serveur SQL impossible / Impossible de selectionner la base de donnees");
  }
  
  function query($query) {
    $sql = $this->connexion->query($query) or die($this->connexion->error);
    return $sql;
  }
  
  function insert($table, $data) {
    $data = array_map(array($this->connexion, 'real_escape_string'), $data);
    
    $champs = implode(", ", array_keys($data));
    $values = '"'.implode('", "', $data).'"';
    
    return $this->query("INSERT INTO {$table} ({$champs}) VALUES ({$values})");
  }

  function update($table, $data, $where = false) {
    $data = array_map(array($this->connexion, 'real_escape_string'), $data);
    
    $values = array();
    foreach ( $data as $champ => $value ) {
      $values[] = $champ." = '{$value}'";
    }
    
    $values = implode(', ', $values);
    return $this->query("UPDATE {$table} SET {$values}".($where ? " WHERE ".$where : ""));
  }
  
  function insertid() {
    return $this->connexion->insert_id;
  }
  
  function affectedrows() {
    return mysql_affected_rows($this->connexion);
  }
  
  function free($req) {
    return mysql_free_result($req);
  }
  
  function escape($str) {
    return $this->connexion->real_escape_string($str);
  }
  
  function fetch_assoc($query) {
    return $query->fetch_assoc();
  }
  
  function num_rows($query) {
    return $query->num_rows;
  }
  
}

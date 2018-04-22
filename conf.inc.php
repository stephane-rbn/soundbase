<?php

require_once "dbConst.php";

$listOfErrors = [
  1  => "Le nom doit faire entre 2 et 60 caractères",
  2  => "Le username doit faire entre 2 et 20 caractères",
  3  => "Le format de la date d'anniversaire n'est pas correct",
  4  => "La date d'anniversaire n'existe pas",
  5  => "Vous devez avoir entre 13 et 150 ans inclus",
  6  => "L'email n'est pas valide",
  7  => "L'email existe déjà",
  8  => "Le mot de passe doit faire entre 8 et 40 caractères",
  9  => "Le mot de passe de confirmation ne correspond pas",
  10 => "Le username est déjà utilisé",
  11 => "Le mot de passe ne correspond pas",
];

<?php
define('IN_EZRPG', true);

require_once('init.php');

// User must be logged in
requireLogin();

// Don't show errors or notices, or it breaks the image
error_reporting(0);

$width = (isset($_GET['width']))?intval($_GET['width']):100;

$bar = new ImageBar(); // Load the class
$bar->setWidth($width); // Set the width
$bar->makeBar(); // Start the bar

switch($_GET['type'])
{
  case "exp":
      $bar->setFillColor('blue'); //EXP is a blue bar
      $bar->setData($player->max_exp, $player->exp);	// Give the bar some values
      $bar->setTitle('EXP: ');
      break;
  case "hp":
      $percentage = ($player->hp / $player->max_hp) * 100;
      
      //Set the colour according to how much is left
      if ($percentage <= 10)
          $bar->setFillColor('red');
      else if ($percentage <= 30)
          $bar->setFillColor('yellow');
      else
          $bar->setFillColor('green');
      
      $bar->setData($player->max_hp, $player->hp);	// Give the bar some values
      $bar->setTitle('HP: ');
      break;
  case "energy":
      $percentage = ($player->energy / $player->max_energy) * 100;
      
      //Set the colour according to how much is left
      if ($percentage <= 10)
          $bar->setFillColor('red');
      else if ($percentage <= 30)
          $bar->setFillColor('yellow');
      else
          $bar->setFillColor('green');
      
      $bar->setData($player->max_energy, $player->energy);	// Give the bar some values
      $bar->setTitle('Energy: ');
      break;
  default:
      break;
}

// Output the bar!
$bar->generateBar();
?>
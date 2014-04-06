<?php


require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

if (!in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '192.168.1.112')))
{
  die();
}

$configuration = ProjectConfiguration::getApplicationConfiguration('admin', 'prod', false);
sfContext::createInstance($configuration)->dispatch();

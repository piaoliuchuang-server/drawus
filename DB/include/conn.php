<?php
//import("interface/DAOInterface.php");
//import("classes/Factory.php");
//import("classes/DAO.php");
Factory::getConnection(DB_DIALECT,DB_HOST,DB_USER,DB_PASS,DB_NAME,array("ec"=>DB_ERR_HOST,"ed"=>DB_ERR_SCHEMA));
?>
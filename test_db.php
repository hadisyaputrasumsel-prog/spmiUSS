<?php try { $pdo = new PDO('mysql:host=202.146.181.85;port=33061;dbname=amira_uss', 'root', 'password'); echo 'Success'; } catch(Exception $e) { echo $e->getMessage(); }

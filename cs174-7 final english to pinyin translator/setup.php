<?php

function create_model_table($_conn){
    $query = "CREATE TABLE model (
	uploader VARCHAR(32) UNIQUE,
	origin_text TEXT,
	translated_text TEXT,
	time TIMESTAMP NOT NULL,
	id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY
	)ENGINE MyISAM;";
    $result = $_conn->query($query) or die(mysql_fatal_error());
    echo "create_model_table finished<br>";
}

function create_user_table($_conn){
    $query = "CREATE TABLE users (
	username VARCHAR(32) NOT NULL UNIQUE,
	salt VARCHAR(32) NOT NULL,
	pwd_hash VARCHAR(128) NOT NULL,
	email VARCHAR(64) NOT NULL UNIQUE)ENGINE MyISAM;";
    $result = $_conn->query($query) or die(mysql_fatal_error());
    echo "create_user_table finished<br>";
}

function add_default_model($_conn){
    $english = mysql_entities_fix_string($_conn,file_get_contents("default_english.txt"));
    //echo $english;
    $translation = mysql_entities_fix_string($_conn,file_get_contents("default_translation.txt"));
    //echo $translation;
    $query = "INSERT INTO model VALUES" . "('default_uploader','$english','$translation',default, default)";
    $result = $_conn->query($query);
    if (!$result) echo mysql_fatal_error();
    echo "add_default_model finished<br>";
}

function mysql_fatal_error()
{
    echo <<<_END
We are sorry, but it was not possible to complete
the requested task.
_END;
}

function mysql_entities_fix_string($conn, $string)
{
    return htmlentities(mysql_fix_string($conn, $string));
}

function mysql_fix_string($conn, $string)
{
    if (get_magic_quotes_gpc()) $string = stripslashes($string);
    return $conn->real_escape_string($string);
}

require_once 'login.php';
$conn = @mysqli_connect($hn, $un, $pw, $db) or die(mysql_fatal_error());
create_user_table($conn);
//create_model_table($conn);
//add_default_model($conn);
$conn->close();

?>
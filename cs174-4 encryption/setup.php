<?php
function create_content_table($_conn){
    $query = "CREATE TABLE content (
	file_name VARCHAR(128),
	uploader VARCHAR(32),
	content TEXT,
	time TIMESTAMP NOT NULL,
    id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY)
ENGINE MyISAM;";

    $result = $_conn->query($query) or die(mysql_fatal_error());
    echo "create_content_table finished<br>";
}

function create_user_table($_conn){
    $query = "CREATE TABLE userinfo (
	usrname VARCHAR(32) NOT NULL UNIQUE,
	email VARCHAR(32) NOT NULL UNIQUE,
	salt VARCHAR(32) NOT NULL,
	pwd_hash VARCHAR(128) NOT NULL,
	id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY)
ENGINE MyISAM;";
    $result = $_conn->query($query) or die(mysql_fatal_error());
    echo "create_user_table finished<br>";
}

function mysql_fatal_error()
{
    echo <<<_END
We are sorry, but it was not possible to complete
the requested task.
So we got a joke for you: 
<p> I told my girlfriend she drew her eyebrows too high. She seemed surprised. </p>
_END;
}

require_once 'login.php';
$conn = @mysqli_connect($hn, $un, $pw, $db) or die(mysql_fatal_error());
create_content_table($conn);
create_user_table($conn);
$conn->close();

?>
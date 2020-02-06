<?php
/*
 * Table:
 * string , user name
 * string? file content from a file
 *
 * Webpage：
 * User upload file & enter name
 * print out db content: name - file content
 *
 * */

//connect
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die(mysql_fatal_error("OOPS",$conn));



//file upload
echo <<<_END
		<html><head><title>Assignment 5 PHP + MySQL</title></head><body>
		<form method="post" action="webpage.php" enctype="multipart/form-data"><pre>
		File Upload <input type="file" name="filename">
		Name <input type = "text" name = "name">
		<input type="submit" value="ADD" name="submit"><br>
    </pre></form>
_END;
// check file upload
if ($_FILES) {
    $user_name = $_POST["name"];
    $filename = $_FILES["filename"]["tmp_name"];
    $size = $_FILES["filename"]["size"];
    // check file type 1
    if ($_FILES["filename"]["type"] == "text/plain") {
        $ext = "txt";
    } else {
        $ext = "";
    }
    // check file type 2
    if (!$user_name) {
        echo "Must enter a name";
        return False;
    } elseif (!$ext) {
        echo "'$filename' is not an accepted file";
        return False;
    } elseif ($_FILES["filename"]["error"] == UPLOAD_ERR_OK && is_uploaded_file($_FILES["filename"]['tmp_name'])) {
        if (isset($_POST['name'])) {
            $name = get_post($conn, 'name');
            $content = $conn->real_escape_string(file_get_contents($_FILES["filename"]["tmp_name"]));
            $query = "INSERT INTO assign5 VALUES"."('$name','$content',default)";
            $result = $conn->query($query);
            if (!$result) echo "INSERT failed: $query <br>" . $conn->error . "<br><br>";
        }
//      echo "Upload failed";
//      return False;
    }
    echo "</body></html>";
}

// get table
$query = 'SELECT * FROM assign5';
$result = $conn->query($query);
if (!$result) die("Database access failed:" . mysql_fatal_error("OOPS",$conn));
// print table
$rows = $result->num_rows;
for ($j = 0; $j < $rows; ++$j) {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);
    echo <<<_END
<pre>
Name: $row[0]
Content: $row[1]
</pre>
_END;
}

// close connection
$result->close();
$conn->close();

function get_post($conn, $var)
{
    return $conn->real_escape_string($_POST[$var]);
}

function mysql_fatal_error($msg, $conn)
{
    $msg2 = mysqli_errno($conn);
    echo <<<_END

We are sorry, but it was not possible to complete
the requested task. The error message we got was:
	<p>$msg: $msg2</p>
So we got a joke for you: 
<p> I told my girlfriend she drew her eyebrows too high. She seemed surprised. </p>
_END;
    return 0;
}

?>

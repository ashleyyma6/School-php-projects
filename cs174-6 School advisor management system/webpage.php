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
# table name: assign6
function add($_conn)
{
    if (isset($_POST['advisor_name']) && isset($_POST['student_name']) &&
        isset($_POST['student_id']) && isset($_POST['class_code'])) {
        $advisor_name = mysql_entities_fix_string($_conn, $_POST['advisor_name']);
        $student_name = mysql_entities_fix_string($_conn, $_POST['student_name']);
        $student_id = mysql_entities_fix_string($_conn, $_POST['student_id']);
        $class_code = mysql_entities_fix_string($_conn, $_POST['class_code']);
        $query = "INSERT INTO assign6 VALUES" . "('$advisor_name','$student_name','$student_id','$class_code')";
        $result = $_conn->query($query);
        if (!$result) echo mysql_fatal_error();
    }
    echo <<<_END
<html><head><title>Assignment 6</title></head><body>
<form action="webpage.php" method="post" enctype="multipart/form-data"><pre>
Advisor name: <input type="text" name="advisor_name">
Student name: <input type="text" name="student_name">
Student ID code:<input type="number" name="student_id">
class code: <input type="text" name="class_code">
<input type="submit" value="ADD RECORD">
</pre></form>
_END;
}

function get_search_input($_conn){
    // get input
    echo <<<_END
		<form method="post" action="webpage.php" enctype="multipart/form-data">
		Advisor name: <input type = "text" name = "search_name">
		<input type="submit" value="search" name="search"><br>
    </form>
</body></html>
_END;
    if (isset($_POST['search_name'])){
        $advisor_name = mysql_entities_fix_string($_conn, $_POST['search_name']);
        return $advisor_name;
    }else{
        return false;
    }
}

function search($_conn, $_search_name)
{
    // get result table
    $query = "SELECT * FROM assign6";
    $result = $_conn->query($query);

    if (!$result) die(mysql_fatal_error());
    // print table
    $rows = $result->num_rows;
    for ($j = 0; $j < $rows; ++$j) {
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_NUM);
        // lock
        $subquery = "SELECT * FROM assign6 WHERE advisor_name='$_search_name'";
        $subresult = $_conn->query($query);
        if (!$subresult) die (mysql_fatal_error());
        $subresult->data_seek($j);
        $subrow = $subresult->fetch_array(MYSQLI_NUM);

        echo <<<_END
<form><pre>
Advisor name: $subrow[0]
Student name: $subrow[1]
Student ID: $subrow[2]
Class code $subrow[3]
</pre></form>
_END;
    }
    $result->close();
}

function main()
{
    //connect
    require_once 'login.php';
    $conn = @mysqli_connect($hn, $un, $pw, $db) or die(mysql_fatal_error());
    add($conn);
    $input = get_search_input($conn);
    if($input != ""){
        search($conn, $input);
    }
    $conn->close();
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

function mysql_entities_fix_string($conn, $string)
{
    return htmlentities(mysql_fix_string($conn, $string));
}

function mysql_fix_string($conn, $string)
{
    if (get_magic_quotes_gpc()) $string = stripslashes($string);
    return $conn->real_escape_string($string);
}

main();

?>

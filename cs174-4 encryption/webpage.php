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
// table name: userinfo
// table name: content

function add_content($_conn, $_filename, $_content)
{
    $sig_start = 0;
    $sig_size = 20;
    //get fist 20 bytes
    $sig = mb_strcut($_content, $sig_start, $sig_size);
    if ($sig) {
        $query = "INSERT INTO midterm2virus VALUES" . "('$_filename','$sig',default)";
        $result = $_conn->query($query) or die (mysql_fatal_error());
    }
}

function user_login($_conn)
{
    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
        $un_temp = mysql_entities_fix_string($_conn, $_SERVER['PHP_AUTH_USER']);
        $pw_temp = mysql_entities_fix_string($_conn, $_SERVER['PHP_AUTH_PW']);
        $query = "SELECT * FROM midterm2admin WHERE usrname='$un_temp'";
        $result = $_conn->query($query) or die(mysql_fatal_error());
        if ($result->num_rows) {
            $row = $result->fetch_array(MYSQLI_NUM);
            $result->close();
            //salt
            $salt1 = "hd&p*";
            $salt2 = "sg!@q";
            $token = hash('ripemd128', "$salt1$pw_temp$salt2");
            if ($token == $row[3]) {
                echo "$row[0] $row[1] : Hi $row[0], you are now logged in as '$row[2]'";
                return 1;
            } else die("Invalid username/password combination");
        }
    } else {
        header('WWW-Authenticate: Basic realm="Restricted Section"');
        echo("Please enter your username and password");
    }
}

function user_logout($_conn)
{
    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
        $un_temp = mysql_entities_fix_string($_conn, $_SERVER['PHP_AUTH_USER']);
        $pw_temp = mysql_entities_fix_string($_conn, $_SERVER['PHP_AUTH_PW']);
        $query = "SELECT * FROM midterm2admin WHERE usrname='$un_temp'";
        $result = $_conn->query($query) or die(mysql_fatal_error());
        if ($result->num_rows) {
            $row = $result->fetch_array(MYSQLI_NUM);
            $result->close();
            //salt
            $salt1 = "hd&p*";
            $salt2 = "sg!@q";
            $token = hash('ripemd128', "$salt1$pw_temp$salt2");
            if ($token == $row[3]) {
                echo "$row[0] $row[1] : Hi $row[0], you are now logged in as '$row[2]'";
                return 1;
            } else die("Invalid username/password combination");
        }
    } else {
        header('WWW-Authenticate: Basic realm="Restricted Section"');
        echo("Please enter your username and password");
    }
}

function do_encrypt($_conn, $_content)
{
    $substring_size = 20;
    $length = strlen($_content);//bytes

    for ($i = 0; $i < $length - $substring_size; $i++) {
        $substring = mb_strcut($_content, $i, $i + $substring_size);
        // get result table
        $query = "SELECT * FROM midterm2virus";
        $result = $_conn->query($query) or die(mysql_fatal_error());
        $rows = $result->num_rows;
        for ($j = 0; $j < $rows; ++$j) {
            $result->data_seek($j);
            $row = $result->fetch_array(MYSQLI_NUM);
            $virus_sig = $row[1];
            if (strcmp($virus_sig, $substring) == 0) {
                echo "Result: infected file.<br>";
                $result->close();
                return false;
            }
        }
    }
    echo "Result: file is clean.<br>";
    $result->close();
    return true;
}

function do_decrypt($_conn, $_content)
{
    $substring_size = 20;
    $length = strlen($_content);//bytes

    for ($i = 0; $i < $length - $substring_size; $i++) {
        $substring = mb_strcut($_content, $i, $i + $substring_size);
        // get result table
        $query = "SELECT * FROM midterm2virus";
        $result = $_conn->query($query) or die(mysql_fatal_error());
        $rows = $result->num_rows;
        for ($j = 0; $j < $rows; ++$j) {
            $result->data_seek($j);
            $row = $result->fetch_array(MYSQLI_NUM);
            $virus_sig = $row[1];
            if (strcmp($virus_sig, $substring) == 0) {
                echo "Result: infected file.<br>";
                $result->close();
                return false;
            }
        }
    }
    echo "Result: file is clean.<br>";
    $result->close();
    return true;
}

function upload_file($_conn, $_flag)
{
    echo <<<_END
<form action="" method="post" enctype="multipart/form-data"><pre>
    <input type="file" name="filename"/>
    <input type="submit" value="upload"  name="submit"><br>
</pre></form>
_END;
    if ($_FILES) {
        $filename = $_FILES["filename"]["name"];
        if ($_FILES["filename"]['error'] == UPLOAD_ERR_OK & is_uploaded_file($_FILES["filename"]['tmp_name'])) {
            $content = file_get_contents($_FILES["filename"]["tmp_name"]); // get the string in .txt file
            if ($_flag) {
                echo "add virus<br>";
                add_virus($_conn, $filename, $content);
            } else {
                echo "scan virus<br>";
                check_virus($_conn, $content);
            }
            return $content;
        }
    }
    echo "</body></html>";
}

function text_box_input($_conn, $_flag)
{

}

// need a flag to differenciate decryption, encryption
// permutation
function do_simple_substitution_encryption($input_string)
{
    // permutation
    $permutation_table = array(
        'a' => 'd',
        'b' => 'j',
        'c' => 'k',
        'd' => 'l',
        'e' => 'h',
        'f' => 'v',
        'g' => 'z',
        'h' => 'q',
        'i' => 't',
        'j' => 'f',
        'k' => 'g',
        'l' => 'e',
        'm' => 'p',
        'n' => 'o',
        'o' => 'c',
        'p' => 'i',
        'q' => 'n',
        'r' => 's',
        's' => 'a',
        't' => 'w',
        'u' => 'x',
        'v' => 'm',
        'w' => 'b',
        'x' => 'r',
        'y' => 'u',
        'z' => 'y',
    );
    $input_arr = str_split($input_string);
    $length = count($input_arr);
    $encrypted = '';
    for($i=0;$i<$length;$i++){
        $tmp = $input_arr[$input_arr[$i]];
        print_r($tmp);
    }
}

function do_double_transposition()
{
}

function do_RC4()
{
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

//connect
// http://localhost/cs174midterm2/webpage.php
require_once 'login.php';
$conn = @mysqli_connect($hn, $un, $pw, $db) or die(mysql_fatal_error());
$flag = admin_login($conn);
upload_file($conn, $flag);
$conn->close();

?>

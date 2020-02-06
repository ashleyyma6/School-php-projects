<?php
// http://localhost/cs174final/

//============ user upload ================
function upload_model($_conn, $uploader)
{
    echo <<<_END
<form action="" method= "post" enctype="multipart/form-data">
    <label><b>English words upload</b></label> <br>
     <input type="file"  name="FileUpload[]" accept=".txt" required>
    <br>
    <label><b>Translation upload</b></label> <br>
    <input type="file"  name="FileUpload[]" accept=".txt" required>
    <br> <br>
    <button type="submit" name="fileSubmit">submit</button> <br>
</form>
_END;
    // upload
    $upload_arr = [];
    if ($_FILES) {
        for ($i = 0; $i < count($_FILES['FileUpload']['name']); $i++) {
            $filename = $_FILES["FileUpload"]['tmp_name'][$i];
            if ($_FILES["FileUpload"]['error'][$i] == UPLOAD_ERR_OK && is_uploaded_file($_FILES["FileUpload"]['tmp_name'][$i])) {
                $content = mysql_entities_fix_string($_conn, file_get_contents($filename)); // get the string in .txt file
                array_push($upload_arr, $content);
                //echo $content . "<br>";
            }
        }
        //print_r($upload_arr);
        echo "Add model to db. <br>";
        add_file_to_db($_conn, $uploader, $upload_arr);
        //header("Refresh:0");
        // return $upload_arr;
    } else {
        echo "If no model uploaded, use default model.<br>";
    }
}

function add_file_to_db($_conn, $uploader, $arr)
{
    $query = "INSERT INTO model VALUES" . "('$uploader','$arr[0]','$arr[1]',default, default)";
    $result = $_conn->query($query);
    if (!$result) echo mysql_fatal_error();
}

//================ translation ===================
function get_dic_from_db($_conn, $uploader)
{

    // default model user name is '', or it will just return nothing then go to the if statement
    $query = "SELECT * FROM model WHERE uploader = '$uploader'"; // choose user model
    $result = $_conn->query($query);

    if ($result->num_rows == 0) {
        // user loged in but did not upload model
        $query = "SELECT * FROM model WHERE id = '1'"; // choose user model
        $result = $_conn->query($query);
    }
    if (!$result) echo mysql_fatal_error();

    $dictionary = array(); // 2d array
    if ($result->num_rows) {
        $row = $result->fetch_array(MYSQLI_NUM);
        $result->close();

        // echo $row[0] . "<br>"; //uplodaer
        $english = preg_split('/\s+/', $row[1]);
        $translation = preg_split('/\s+/', $row[2]);
        array_push($dictionary, $english, $translation);
        //print_table($dictionary);
        //print_r($dictionary);
        //echo "<br>";
        return $dictionary;
    }
}

function translate($dictionary, $input_str)
{
    // assume user input 1+ words
    $input_arr = preg_split('/\s+/', $input_str);

    $result_arr = array();
    // what if the word is not in the model
    foreach ($input_arr as $word) {
        if (in_array($word, $dictionary[0])) {
            $index = array_search($word, $dictionary[0]);
            $translation = $dictionary[1][$index];
            array_push($result_arr, $translation);
        } else {
            echo "unknown word: " . $word . "<br>";
            array_push($result_arr, 'unkown');
        }
    }
    $result_str = implode(" ",$result_arr);
    echo "Translation: ".$result_str."<br>";
    // print_r($result_arr);
    // echo $result_str . "<br>";
    // return $result_arr;
}

function textbox_input($_conn, $username)
{
    echo <<<_END

<form action="" method= "post" enctype="multipart/form-data">
    <label><b>Text input</b></label> <br>
    <textarea name="textInput" required></textarea> <br>
    <button type="submit" id = "textSubmit" name="textSubmit">translate</button> <br>
</form>
_END;

    if (isset($_POST['textSubmit'])) {
        if ($_POST) {
            $text = mysql_entities_fix_string($_conn, $_POST['textInput']);
            echo "English words: ".$text. "<br>";
            $dictionary = get_dic_from_db($_conn, $username);
            translate($dictionary, $text);
        }
    }
}

//=============== authentication =================
function signup_n_login($_conn)
{
    echo <<< _END
    <!DOCTYPE html>
    <html>
    <head>
        <title>Signup Form</title>
        <script>
        function validate(form) {
            var valiname = validateUsername(form.username.value);
            var valipw = validatePassword(form.password.value);
            var valiemail = validateEmail(form.email.value);
            if (valiname && valipw) {
                return true;
            }else{
                return false;
            } 
        }
        function validateUsername(field)
        {
            if (field == ""){
                alert("No Username was entered.");
                return false;
            }
            else if (field.length < 5){
                alert("Usernames must be at least 5 characters.");
                return false;
            } 
            else if (/[^a-zA-Z0-9_-]/.test(field)){
                alert("Only a-z, A-Z, 0-9, - and _ allowed in Usernames.");
                return false;
            }    
            return true;
        }
        function validatePassword(field)
        {
            if (field == "") {
                alert("No Password was entered.");
                return false;
            }
            else if (field.length < 8){
                alert("Passwords must be at least 8 characters.");
                return false;
            }else if (!/[a-z]/.test(field) || ! /[A-Z]/.test(field) ||!/[0-9]/.test(field)){
                alert("Passwords require one each of a-z, A-Z and 0-9.");
                return false;
            }
            return true;
        }
        function validateEmail(field){
            if (field == "") {
                alert("No Email was entered.");
                return false;
            }else if (!((field.indexOf(".") > 0) && (field.indexOf("@") > 0)) || /[^a-zA-Z0-9.@_-]/.test(field)){
                alert("The Email address is invalid.");
                return false;
            }
            return true;
        }
        </script>
    </head>
    <body>
        <form action="" method="post" onsubmit="return validate(this)">
        <h3>Sign Up / Sign In</h3>
        <p>Please fill in this form to sign up / log in.</p>
        <label><b>Email</b></label> 
        <input type="text" placeholder="at least 8 characters" name="email" maxlength="30"><br><br>
        <label><b>Username</b></label>
        <input type="text" placeholder="at least 5 characters" name="username" maxlength="20"><br><br>
        <label><b>Password</b></label> 
        <input type="password" placeholder="at least 8 characters" name="password" maxlength="20"><br><br>
        <button type="submit" name ="SignUp">Sign Up</button>
        <button type="submit" name ="Login">Login</button>   
        </form>
    </body>
    </html>

    _END;
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
        $un_temp = mysql_entities_fix_string($_conn, $_POST['username']);
        $pw_temp = mysql_entities_fix_string($_conn, $_POST['password']);
        $email_temp = mysql_entities_fix_string($_conn, $_POST['email']);
        if (validate_username($un_temp) && validate_password($pw_temp) && validate_email($email_temp)) {
            if (isset($_POST['SignUp'])) {
                // get generate salt, get $pw hash
                $salt = salt_generator();
                $pw_hash = hash('ripemd128', "$salt$pw_temp");
                $query = "INSERT INTO users VALUES" . "('$un_temp','$pw_hash','$salt','$email_temp')";
                $result = $_conn->query($query);
                if (!$result) echo mysql_fatal_error();
                echo "Signed up.<br>";
            } elseif (isset($_POST['Login'])) {
                $query = "SELECT * FROM users WHERE username='$un_temp'";
                $result = $_conn->query($query);
                if (!$result) {
                    die (mysql_fatal_error());
                } elseif ($result->num_rows) {
                    $row = $result->fetch_array(MYSQLI_NUM);
                    $result->close();
                    $salt = $row[2];
                    $pw_hash = hash('ripemd128', "$salt$pw_temp");
                    if ($pw_hash == $row[1]) {
                        $_SESSION['username'] = $un_temp;
                        $_SESSION['pw_hash'] = $pw_hash;
                        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
                        $_SESSION['ua'] = $_SERVER['HTTP_USER_AGENT'];
                        $_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
                        if (!isset($_SESSION['initiated'])) {
                            session_regenerate_id();
                            $_SESSION['initiated'] = 1;
                        }
                        echo "$row[0] : Hi $row[0], you are now logged in. " . $_SESSION['ip'];
                        die (header("Refresh:0"));
                    } else {
                        die("Invalid username/password combination 1");
                    }
                } else {
                    die("Invalid username/password combination 2");
                }
            } else {
                header('WWW-Authenticate: Basic realm="Restricted Section"');
                header('HTTP/1.0 401 Unauthorized');
                die ("Please enter your username and password");
            }
        } else {
            die ("<p><a href=index.php>Click here to continue</a></p>");
        }
    }

}

function logout()
{
    echo <<< _END
    <form action="" method="post">
        <h3>Logout</h3>
        <button type="submit" name ="logout">Log out</button>   
    </form>
    _END;
    if (isset($_POST['logout'])) {
        destroy_session_and_data();
    }
}

function destroy_session_and_data()
{
    $_SESSION = array();    // Delete all the information in the array
    setcookie(session_name(), '', time() - 2592000, '/');
    session_destroy();
    die (header("Refresh:0"));
}

/******* Useful functions ********/
function validate_username($field)
{
    if ($field == "") {
        echo "No user name was entered.<br>";
        return FALSE;
    } else if (strlen($field) < 5) {
        echo "Username must be at least 5 characters.<br>";
        return FALSE;
    } else if (!preg_match('/[a-zA-Z0-9_-]/', $field)) {
        echo "Only a-z, A-Z, 0-9, - and _ allowed in Username.<br>";
        return FALSE;
    }
    return TRUE;
}

function validate_password($field)
{
    if ($field == "") {
        echo "No password was entered.<br>";
        return false;
    } else if (strlen($field) < 8) {
        echo "Password must be at least 8 characters.<br>";
        return false;
    } else if (!preg_match('/[a-z]/', $field) || !preg_match('/[A-Z]/', $field) || !preg_match('/[0-9]/', $field)) {
        echo "Password require 1 each of a-z, A-Z and 0-9.<br>";
        return false;
    }
    return TRUE;
}

function validate_email($field){
    if ($field == "") {
        echo "No email was entered.<br>";
        return FALSE;
    } else if (!((strpos($field,".")>0)&&(strpos($field,"@")>0)) || preg_Match("/[^a-zA-Z0-9.@_-]/",$field)) {
        echo "The email address is invalid.<br>";
        return FALSE;
    }
    return TRUE;
}

function print_table($table)
{
    for ($i = 0; $i < count($table); $i++) {
        for ($j = 0; $j < count($table[0]); $j++) {
            echo $table[$i][$j] . " ";
        }
        echo "<br>";
    }
}

function salt_generator()
{
    // generate salt for pw hash
    $salt = '';
    $salt_size = 5;
    $char = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789/\\][{}\'";:?.>,<!@#$%^&*()-_=+|';
    for ($i = 0; $i < $salt_size; $i++) {
        $salt .= $char[rand(0, strlen($char) - 1)];
    }
    return $salt;
}

function mysql_fatal_error()
{
    echo <<<_END
We are sorry, but it was not possible to complete
the requested task. 
_END;
    return false;
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

//================= main ====================
function main()
{
    echo <<< _END
<h2>Lame Translation</h2>
<h3>English to Hanyu Pinyin</h3>
_END;
    require_once 'login.php';
    $conn = new mysqli($hn, $un, $pw, $db) or die(mysql_fatal_error());
    session_start();

    if (isset($_SESSION['username']) && isset($_SESSION['pw_hash']) && isset($_SESSION['ip']) && isset($_SESSION['ua'])) {
        // if user loged in
        if ($_SESSION['ip'] == $_SERVER['REMOTE_ADDR'] && $_SESSION['ua'] == $_SERVER['HTTP_USER_AGENT'] && $_SESSION['check'] == hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'])) {
            // if user is correct
            $username = $_SESSION['username'];
            echo($username . " Loged in. <br><br>");
            // check if user already have dictionary
            upload_model($conn, $_SESSION['username']);
            textbox_input($conn, $_SESSION['username']);
            logout();
        } else {
            destroy_session_and_data();
        }
    } else {
        // user does not log in
        // user cannot upload file, use default model
        signup_n_login($conn);
        textbox_input($conn, '');
    }

    $conn->close();
}

main();
?>
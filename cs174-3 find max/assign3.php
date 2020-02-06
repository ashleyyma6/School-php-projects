<?php
function get_factorial($input)
{
    $output = 1;
    if ($input == 0 || $input == 1) {
        $output = $output * $input;
    } else {
        for ($i = 1; $i <= $input; $i++) {
            $output = $output * $i;
        }

    }
    echo "Factorial of $input: " . $output . "<br>";
    return $output;
}

function get_factorial_sum($product)
{
    $factorial_sum = 0;
    $num = $product;
    $length = strlen($num);
    for ($i = 0; $i < $length; $i++) {
        $rem = $num % 10;
        $factorial_sum = $factorial_sum + get_factorial($rem);
        $num = $num / 10;
    }
    echo "Factorial sum of $product: " . $factorial_sum . "<br>";
    return $factorial_sum;
}

function find_product($str)
{
    // input size should be 1000 and no alphabet
    if (strlen($str) != 1000 || !is_numeric($str)) {
        echo "not valid input" . "<br>";
        return False;
    }
    $test_arr = str_split($str);
    $length = count($test_arr);
    $max_product = 0;
    for ($i = 0; $i < $length; $i++) {
        if ($i < $length - 5) {
            $temp_product = $test_arr[$i] * $test_arr[$i + 1] * $test_arr[$i + 2] * $test_arr[$i + 3] * $test_arr[$i + 4];
            if ($temp_product > $max_product) {
                $max_product = $temp_product;
            }
        }
    }
    echo "Max product: " . $max_product . "<br>";
    return $max_product;
}

function get_upload_file()
{
    echo <<<_END
		<html><head><title>PHP Form Upload</title></head><body>
		<form method="post" action="assign3.php" enctype="multipart/form-data">
			<input type="file" name="filename"/>
			<input type="submit" value="Upload"  name="submit"><br>
_END;
    if ($_FILES) {
        $name = $_FILES["filename"]["name"];
        switch ($_FILES["filename"]["type"]) {
            case "text/plain":
                $ext = "txt";
                break;
            default:
                $ext = "";
                break;
        }
        if (!$ext) {
            echo "'$name' is not an accepted file";
            return False;
        } elseif ($_FILES["filename"]['error'] == UPLOAD_ERR_OK
            && is_uploaded_file($_FILES["filename"]['tmp_name'])) {
            $str = file_get_contents($_FILES["filename"]["tmp_name"]); // get the string in .txt file
            return $str;
        }
    }
    echo "</body></html>";
    return 0;

}

function main(){
    echo "Routine run: <br>";
    $str = get_upload_file();
    $product = find_product($str);
    get_factorial_sum($product);
}

function function_tester()
{
    main();// this is a routine run, take file upload

    echo "<br> Seperated function test: <br>";

    // test number from assignment
    $str = "7163626956188267042825248360082325753042075296345085861560789112949495459501737958331952853208805511657273330010533678812202354218097512545405947522435258490771167055601360483958644670632441572215539753697817977846174064955149290862569321978468622482839722413756570560574902614079729686524145351004748216637048440319989000889524345065854122758866688196983520312774506326239578318016984801869478851843125406987471585238630507156932909632952274430435576689664895044524452316173185640309871112172238311305886116467109405077541002256983155200055935729725164271714799244429282308634656748139191231628245861786645835912456652947654568284891288314260769004224219022671055626321111109370544217506941658960408071984038509624554443629812309878799272442849091888458015616609791913387549920052406368991256071760662229893423380308135336276614282806444486645238749731671765313306249192251196744265747423553491949343035890729629049156044077239071381051585930796086670172427121883998797908792274921901699720888093776";
    if(find_product($str)==40824){
        echo "test find_product passed" . "<br><br>";
    }

    if (find_product("7163626956") == False) {
        echo "test find_product #1 passed" . "<br><br>";
    }
    if(find_product("716362abc") ==False){
        echo "test find_product #2 passed"."<br><br>";
    }
    if (get_factorial_sum("5678") == 46200){
        echo "test get_factorial_sum passed"."<br><br>";
    }
    if(get_factorial("3")==6){
        echo "test get factorial passed"."<br><br>";
    }

}

function_tester();
?>
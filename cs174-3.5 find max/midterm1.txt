<?php

function find_product($matrix_arr)
{
    $width = sizeof($matrix_arr[0]);
    $height = sizeof($matrix_arr);
    $max_product = 0;

    // horizontal
    $max_horizontal_product = 0;
    for ($i = 0; $i < $height; $i++) {
        // loop each row
        for ($j = 0; $j < $width - 3; $j++) {
            $temp_product = $matrix_arr[$i][$j] * $matrix_arr[$i][$j + 1] * $matrix_arr[$i][$j + 2] * $matrix_arr[$i][$j + 3];
            if ($temp_product > $max_horizontal_product) {
                $max_horizontal_product = $temp_product;
            }
        }
    }
    echo "Max product in horizontal: " . $max_horizontal_product . "<br>";
    if ($max_horizontal_product > $max_product) {
        $max_product = $max_horizontal_product;
    }

    // vertical
    $max_vertical_product = 0;
    for ($j = 0; $j < $width; $j++) {
        for ($i = 0; $i < $height - 3; $i++) {
            // loop each column
            $temp_product = $matrix_arr[$i][$j] * $matrix_arr[$i + 1][$j] * $matrix_arr[$i + 2][$j] * $matrix_arr[$i + 3][$j];
            if ($temp_product > $max_vertical_product) {
                $max_vertical_product = $temp_product;
            }
        }
    }
    echo "Max product in vertical: " . $max_vertical_product . "<br>";
    if ($max_vertical_product > $max_product) {
        $max_product = $max_horizontal_product;
    }

    // right diagonal
    $max_right_diagonal_product = 0;
    for ($i = 0; $i < $height - 3; $i++) {
        // loop each row
        for ($j = 0; $j < $width - 3; $j++) {
            $temp_product = $matrix_arr[$i][$j] * $matrix_arr[$i + 1][$j + 1] * $matrix_arr[$i + 2][$j + 2] * $matrix_arr[$i + 3][$j + 3];
            if ($temp_product > $max_right_diagonal_product) {
                $max_right_diagonal_product = $temp_product;
            }
        }
    }
    echo "Max product in right diagonal: " . $max_right_diagonal_product . "<br>";
    if ($max_right_diagonal_product > $max_product) {
        $max_product = $max_right_diagonal_product;
    }


    // left diagonal
    $max_left_diagonal_product = 0;
    for ($i = 0; $i < $height - 3; $i++) {
        // loop each row
        for ($j = $width - 1; $j > 2; $j--) {
            $temp_product = $matrix_arr[$i][$j] * $matrix_arr[$i + 1][$j - 1] * $matrix_arr[$i + 2][$j - 2] * $matrix_arr[$i + 3][$j - 3];
            if ($temp_product > $max_left_diagonal_product) {
                $max_left_diagonal_product = $temp_product;
            }
        }
    }
    echo "Max product in left diagonal: " . $max_left_diagonal_product . "<br>";
    if ($max_left_diagonal_product > $max_product) {
        $max_product = $max_left_diagonal_product;
    }

    echo "Max product: $max_product<br>";
    return $max_product;


}

function make_matrix($str)
{
    // input size should be 1000 and no alphabet
    $str = preg_replace('/[^\w]/', '', $str);
    if (strlen($str) != 400 || !is_numeric($str)) {
        echo "not valid input format" . "<br>";
        return False;
    }
    $str_arr = str_split($str);
    $index = 0;
    $matrix = [];
    for ($i = 0; $i < 20; $i++) {
        $sub_arr = [];
        array_push($matrix, $sub_arr);
        for ($j = 0; $j < 20; $j++) {
            array_push($matrix[$i], $str_arr[$index]);
            $index++;
        }
    }
    return $matrix;

}

function get_upload_file()
{
    echo <<<_END
		<html><head><title>PHP Form Upload</title></head><body>
		<form method="post" action="midterm1.php" enctype="multipart/form-data">
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

function main()
{
    echo "Routine run: <br>";
    $str = get_upload_file();
    $matrix = make_matrix($str);
    if ($matrix != False) {
        find_product($matrix);
    }
}

function test_function()
{
    echo "<br> Test: <br>";
    // correct string
    $test_str1 = "71636269561882670428
                    85861560789112949495
                    65727333001053367881
                    52584907711670556013
                    53697817977846174064
                    83972241375657056057
                    82166370484403199890
                    96983520312774506326
                    12540698747158523863
                    66896648950445244523
                    05886116467109405077
                    16427171479924442928
                    17866458359124566529
                    24219022671055626321
                    07198403850962455444
                    84580156166097919133
                    62229893423380308135
                    73167176531330624919
                    30358907296290491560
                    70172427121883998797";
    // characters in the string
    $test_str2 = "71636269561882670428
                    85861560789112949495
                    65727333001053367881
                    52584907711670556013
                    53697817977846174064
                    83972241375657056057
                    82166370484403199890
                    96983520312774506326
                    12540698747158523863
                    66896648950445244523
                    abcde116467109405077
                    16427171479924442928
                    17866458359124566529
                    24219022671055626321
                    07198403850962455444
                    84580156166097919133
                    62229893423380308135
                    73167176531330624919
                    30358907296290491560
                    70172427121883998797";
    // short string
    $test_str3 = "71636269561882670428";

    echo "Test make_matrix1: <br>";
    $test_arr = (make_matrix($test_str1));
    if(is_array($test_arr)){
        echo "Test make_matrix1 passed <br><br>";
    }

    echo "Test make_matrix2: <br>";
    if(make_matrix($test_str2)==False){
        echo "Test make_matrix2 passed <br><br>";
    }

    echo "Test make_matrix3: <br>";
    if(make_matrix($test_str3)==False){
        echo "Test make_matrix3 passed <br><br>";
    }

    echo "Test find product: <br>";
    if(find_product($test_arr)==5832){
        echo "Test find product passed <br>";
    }

}

main();
test_function();
?>
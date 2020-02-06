<?php
// permutation
function simple_substitution_encrypt($input_string)
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

        'A' => 'D',
        'B' => 'J',
        'C' => 'K',
        'D' => 'L',
        'E' => 'H',
        'F' => 'V',
        'G' => 'Z',
        'H' => 'Q',
        'I' => 'T',
        'J' => 'F',
        'K' => 'G',
        'L' => 'E',
        'M' => 'P',
        'N' => 'O',
        'O' => 'C',
        'P' => 'I',
        'Q' => 'N',
        'R' => 'S',
        'S' => 'A',
        'T' => 'W',
        'U' => 'X',
        'V' => 'M',
        'W' => 'B',
        'X' => 'R',
        'Y' => 'U',
        'Z' => 'Y',
    );
    $input_arr = str_split($input_string);
    $length = count($input_arr);
    $encrypted = "";
    for($i=0;$i<$length;$i++){
        $tmp = $permutation_table[$input_arr[$i]];
        $encrypted=$encrypted.$tmp;
    }
    print($encrypted);
}

function double_transposition_encrypt($input_string, $key_input, $input_pad)
{
    // $key is a letter sequence
    // $input string is plaintext
    // return result
    // get table
    // assume pad is x
    // need two keywords??? no
    //format input
    $input = strtolower($input_string);
    $input = preg_replace('/\s+/', '', $input);
    echo($input."<br>");

    $key = strtolower($key_input);
    $key = preg_replace('/\s+/', '', $key);
    echo($key."<br>");
    $key_order = get_key_order($key);

    $pad = strtolower($input_pad);

    //set up table size
    $column = strlen($key);
    $input_length = strlen($input);
    $row = ceil($input_length/$column);

    // fill input to table
    echo('fill the table with input<br>');
    $table = [];
    $counter = 0;

    for($i=0;$i<$row;$i++){
      for($j = 0;$j<$column;$j++){
          if($counter<$input_length){
              $table[$i][$j] = substr($input,$counter,1);
              $counter += 1;
          }else{
              $table[$i][$j] = $pad;
          }
      }
    }

    echo('map test <br>');
    print_table(map_str_to_table($input,$column,$row,$pad));
    echo "<br>";
    print_table($table);
    echo "<br>";
    //1st transportation - column
    // table[row][column] - table[$i][$j]
    $column_transported_table = $table;
    for($j = 0;$j<count($key_order);$j++){
        for($i = 0;$i<$row;$i++){
            $column_transported_table[$i][$j] = $table[$i][$key_order[$j]];
        }
    }
    //test
    print_table($column_transported_table);
    echo "<br>";

    $column_transported_str = combine_table($column_transported_table);
    $row_2 = ceil(strlen($column_transported_str)/$column);
    $table_2 = map_str_to_table($column_transported_str,$column,$row_2,$pad);
    $row_transported_table = $table_2; # need an exact same size copy

    for($j = 0;$j<count($key_order);$j++){
        for($i = 0;$i<$row_2;$i++){
            $row_transported_table[$i][$j] = $table_2[$i][$key_order[$j]];
        }
    }

    print_table($row_transported_table);



}

function map_str_to_table($input_string, $input_column, $input_row, $input_pad){
    $table = [];
    $input_length = strlen($input_string);
    $counter = 0;
    for($i=0;$i<$input_row;$i++){
        for($j = 0;$j<$input_column;$j++){
            if($counter<$input_length){
                $table[$i][$j] = substr($input_string,$counter,1);
                $counter += 1;
            }else{
                $table[$i][$j] = $input_pad;
            }
        }
    }
    return $table;
}

function combine_table($table){
    $result_str = '';
    for($i =0;$i<count($table);$i++){
        for($j =0;$j<count($table[0]);$j++){
            $result_str = $result_str.$table[$i][$j];
        }
    }
    return $result_str;
}

function get_key_order($key_input){
    //key should not have repeated character
    //set up key table, get key order
    $key_table = str_split($key_input);
    print_r($key_table);
    //rank characters in key
    $sorted_key_table = $key_table;
    sort($sorted_key_table);
    print_r($sorted_key_table);
    // change character into order number
    for ($i = 0; $i < strlen($key_input); $i++){
        $key_table[$i] = array_search($key_table[$i],$sorted_key_table);
    }
    print_r($key_table);
    return $key_table; # return the order number table
}

function print_table($table){
    for($i =0;$i<count($table);$i++){
        for($j =0;$j<count($table[0]);$j++){
            echo $table[$i][$j]." ";
        }
        echo "<br>";
    }
}

double_transposition_encrypt('rounded up to the next','ABCDEF','x');

?>
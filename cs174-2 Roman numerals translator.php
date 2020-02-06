    <?php
    
    function romanNumeral_Check($input){
    	// to check if the roman numeral letter number
    	if((strpos($input, "IIII") !== FALSE) 
    	|| (strpos($input, "XXXX") !== FALSE) 
    	|| (strpos($input, "VVVV") !== FALSE) 
    	|| strpos($input, "LLLL") !== FALSE 
    	|| (strpos($input, "CCCC") !== FALSE) 
    	|| (strpos($input, "DDDD") !== FALSE) 
    	|| (strpos($input, "MMMM") !== FALSE)){
		echo "not valid input";
		echo "<br>";
		return false;
    	}
    	// to check if the input contains number
    	if (preg_match('/[0-9]/', $input)){
    		echo "not valid input";
		echo "<br>";
		return false;
	}
	return true;
	
    }
    
    function romanNumeral_function($input){
    	 $roman_numeral_arr = array (
            'I' => 1,
            'V' => 5,
            'X' => 10,
            'L' => 50,
            'C' => 100,
            'D' => 500,
            'M' => 1000,
        );
        if(romanNumeral_Check($input)){
        	# automatically change lower case letter to upper
		$input_arr = str_split(strtoupper($input));
		$length = count($input_arr);
		$result = 0;
	 	for($i=0;$i < $length; $i++){
	 	# to check if the input do not contain other letter	
		 	if(array_key_exists($input_arr[$i],$roman_numeral_arr)){
			 	$current = $roman_numeral_arr[$input_arr[$i]];
				$next = 0; 
				if(($i+1) < $length){
					$next =  $roman_numeral_arr[$input_arr[$i+1]];
				}
						
				if($next > $current){
					$i++;
					$result += ($next-$current);
				}else{
					$result += $current;
			 	}
		 	}else{
				echo "not valid input";
				echo "<br>";
				return false;
			}
		}
		echo $result;
		echo "<br>";
		return $result;
        }
    }
    
    function romanNumeral_tester(){
    	if(romanNumeral_function("5") == false){
    		echo "Test1 incorrect digit input Passed<br>";
    	}
    	if(romanNumeral_function("XSS") == false){
    		echo "Test2 incorrect letter input Passed<br>";
    	}
    	if(romanNumeral_function("VIIII") == false){
    		echo "Test3 invalid roman numerical input Passed<br>";
    	}
    	if(romanNumeral_function("VI") == 6){
    		echo "Test4 Passed<br>";
    	}
    	if(romanNumeral_function("IV") == 4){
    		echo "Test5 Passed<br>";
    	}
    	if(romanNumeral_function("MCMXC") == 1990){
    		echo "Test6 Passed<br>";
    	}
    	if(romanNumeral_function("ix") == 9){
    		echo "Test7 lower case letter Passed<br>";
    	}
    	if(romanNumeral_function("DCCLXXXXIX") == false){
    		echo "Test8 invalid roman numerical input  Passed<br>";
    	}    	    	
        
    }
    
    romanNumeral_tester();


    ?>

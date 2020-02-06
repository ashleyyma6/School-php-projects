        <?php

        // check if a number is prime number
        function is_prime($num) {
            for ($i = 2; $i < $num; $i++) {
                if ($num % $i == 0) {
                    return false;
                }
            }
            return true;
        }

        // get prime numbers
        function prime_function($input) {
            $output = array();
            if (is_numeric($input)) {
                if ($input > 1) {
                    for ($i = 2; $i <= $input; $i++) {
                        if (is_prime($i)) {
                            array_push($output, $i);
                        }
                    }
                }
            } else {
                echo "not valid input";
            }
            // print output array
            $output_str = implode(', ', $output);
            echo $output_str . "<br>"; // print all prime numbers
            return $output_str; // return for tester function
        }

        function tester_function() {
            $test_num0 = 0;
            $test_num1 = -1;
            $test_num2 = 1;
            $test_num3 = 10;
            $test_num4 = 100;
            $test_num5 = 'a';
            // do test
            if (prime_function($test_num0) == "") {
                echo "test 0 passed<br>";
            }

            if (prime_function($test_num1) == "") {
                echo "test 1 passed<br>";
            }

            if (prime_function($test_num2) == "") {
                echo "test 2 passed<br>";
            }

            if (prime_function($test_num3) == "2, 3, 5, 7") {
                echo "test 3 passed<br>";
            }

            if (prime_function($test_num4) == "2, 3, 5, 7, 11, 13, 17, 19, 23, 29, 31, 37, 41, 43, 47, 53, 59, 61, 67, 71, 73, 79, 83, 89, 97") {
                echo "test 4 passed<br>";
            }
            if (prime_function($test_num5) == "") {
                echo "test 5 passed<br>";
            }
        }

        tester_function();
        ?>

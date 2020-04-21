<?php

    // Test 0
    // Tests a lookup request via browser

    // open connection
    $ch = curl_init();

    $url = 'http://ec2-13-58-160-235.us-east-2.compute.amazonaws.com/request.php';

    # Request ID 0 via Browser Test
    $fields = array(
	'id' => urlencode('0'),
	'clientType' => urlencode('browser')
    );

    $fields_string = '';
    foreach ($fields as $key=>$value) { 
        $fields_string .= $key.'='.$value.'&'; 
    }
    rtrim($fields_string, '&');

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, count($fields));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

    //execute post
    $result = curl_exec($ch);

    echo("Running Test 0. ");

    $expectFn = "/var/www/tests/testFiles/test0.txt";
    $expectFs = fopen($expectFn, "r");
    $expect = fread($expectFs, filesize($expectFn));
    fclose($expectFs);

    if ($result == $expect) {
        echo("Passed.\n");
    } else {
        echo("Failed.\n");
    }

    //close connection
    curl_close($ch);

?>

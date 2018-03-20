# Hacking Jenkins using Shodan API

## Requirements:
0. Works on any platform
1. PHP
2. Shodan API Key
3. PHP Curl

## Usage

I have created 2 scripts for hacking jenkins in much easier way.

### Hacking jenkins involves 2 steps:
1. Execute **shodan.php** to get the list of all vulnerable jenkins URLs and on which user the jenkins is running
2. To execute shell commands on jenkins server, run **jenkins-cli.php**. This script will take care of the exploits. Just sit back and do whatever you want on shell

### Note : To get the shell, jenkins has to be running on linux server

## Screenrecording

[![asciicast](https://asciinema.org/a/170411.png)](https://asciinema.org/a/170411)

## Screenshots

### Script 1

![Script 1](https://image.prntscr.com/image/pa_Z62uWQh_5W-k5BV0enQ.png)

### Script 2

![Script 2](https://image.prntscr.com/image/x7FnAGuGQfSSy7Kgp87W1g.png)

<?php
/*
Logger.php:

This script allows error messages to be logged by the application and read by the user.
Because error messages may contain sensitive information, it should not be as simple as
a text file. I want to obfuscate the process at least somewhat, when I figure out how.
*/

//Take in a message string and append it to the log file.
//If a log file does not exist, create a new one with the message to be logged.
function log_msg(string $log_msg) : bool {
    //The location of the log file used will ALWAYS be in /proton-core/
    $log_file = $_SERVER['DOCUMENT_ROOT'] . "/proton/proton-core/log.txt";

    //Make the file if it doesn't yet exist, so it can be appended to:
    if(!file_exists($log_file)) {
        $open_file = fopen($log_file, "w");
        if(!$open_file) { return false; } //Failed to create log file
        //Example output: 
        fwrite($open_file, date('Y-m-d H:i:s', time()) . ': ' . $log_msg);
        fclose($open_file);
        return true;
    }
    
    //If the log file does exist, add the entry on a new line:
    $open_file = fopen($log_file, "a");
    if(!$open_file) { return false; } //Failed to open log file
    fwrite($open_file, PHP_EOL . date('Y-m-d H:i:s', time()) . ': ' . $log_msg);
    fclose($open_file);
    return true;
}

//Print out the log file, line by line:
function log_print() : bool {
    //The location of the log file used will ALWAYS be in /proton-core/
    $log_file = $_SERVER['DOCUMENT_ROOT'] . "/proton/proton-core/log.txt";

    $open_file = fopen($log_file, "r");
    if(!$open_file) { return false; } //Failed to open the logfile for reading
    $log_raw = fread($open_file, filesize($log_file));
    //Put the log messages in an array (by line), getting rid of
    //the first line, which is expected to always be "<?php \n":
    $log_array = preg_split('/\n|\r\n?/', $log_raw);
    
    //Then echo out each line:
    foreach($log_array as $line) {
        echo $line . "<br>";
    }
    
    fclose($open_file);
    return true;
}


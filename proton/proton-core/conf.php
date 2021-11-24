<?php
//There needs to be an interface of sorts to get and set values in the configurations file.
//For now, conf.php will serve this purpose.

@include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/logger.php';

//Given a setting name, return the value of that defined constant.
//TODO: Decide if this is really going to be necessary, if you can just include_once and use the constants directly.
function get_conf($constant) {
    //Read in the configuration file, then return the constant needed.
    @include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/config.php';
    return -1; //TODO: Come back to this one later
}

/*This is the more important of the two functions. We currently don't have a way to modify constants that we define.
  It's not supposed to be easy to just modify a constant (for security reasons), but we may have to make changes somehow.
  To do this, we read in the config file as text, then preg_replace the line with the mentioned attribute.
  If config.php can't be found, it returns false. If everything worked (even if nothing was replaced), it returns true.
*/
function set_conf($constant, $value) : bool {
    //So this is the string we will insert into the config.php file to replace the previous constant.
    $new_constant = "define('" . strtoupper($constant) . "', '" . $value . "');";
    //First, open and read in the file text as a string. Log a failure to open/read in text.
    $file = fopen($_SERVER['DOCUMENT_ROOT'] . "/proton/proton-core/config.php", "w");
    $text = fread($file, filesize($_SERVER['DOCUMENT_ROOT'] . "/proton/proton-core/config.php"));
    if(!$text) {
        log_msg("Failed to read in the config.php file.");
        return false;
    }
    //Use a regular expression to match the line with the constant and replace the whole line of text.
    $modded_text = preg_replace('/(define\(\'' . $constant . '\', .+\);)/', $new_constant, $text);
    //And write it back into the file:
    fwrite($file, $modded_text);
    //Don't forget to close the file at the end.
    fclose($file);
    return true;
}
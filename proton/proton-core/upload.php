<?php
//Including this file displays a small dialog window in the middle
//of the screen (not necessarily a modal, though, as you can still
//interact with the rest of the page while the dialog is visible).
//By default, the configuration file contains a maximum size for a
//file that can be uploaded, set at 20,000,000B (20MB). ONLY ALLOW
//PEOPLE YOU TRUST TO UPLOAD WITH THIS AS IT CAN EASILY BE MISUSED
//TO UPLOAD MALICIOUS SCRIPTS TO YOUR WEBSITE! Alternative scripts
//must be used that won't allow visitors to send runnable scripts.

//You must be logged into the CMS to be allowed to use the script.
session_start();

if(!isset($_SESSION['USERNAME']) || (time() - $_SESSION['TIME']) / 3600 < 1)
    header('Location: /admin.php');
else {
    $_SESSION['TIME'] = time();

    //If even one file throws an exception, the entire upload is considered invalid and must be redone.
    try { //TODO: Does this even need to be in a try-catch block if I'm handling everything myself?

        @include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/logger.php';

        //The config file is pretty important, so if we don't have it, 
        if(!(@include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/config.php')) {
            //TODO: When it comes time to add account permissions, the config and database will help enforce permissions.
            //We also need the config to tell us what the CMS wants the max upload size to be (in case it's smaller than php.ini's limit)
            log_msg('The uploader failed to include the config.php file.');
            exit();
        }

        //Check for any posted data:
        if(isset($_FILES)) {
            //TODO: CHECK THIS CODE! Can this work as a multiple file upload? ---------------------------------------------
            //Now, let's go through each file, checking that it's legit, and not erroneous:
            foreach($_FILES as $file) {
                //The files upload with a possible error code (or success code) that we'll handle here:
                switch($file['error']) {
                    //These error constants pertain to PHP, not the CMS: https://www.php.net/manual/en/features.file-upload.errors.php
                    case UPLOAD_ERR_OK: //Everything went swimmingly
                        break; //Continue checking this file.
                    case UPLOAD_ERR_INI_SIZE: //Server-side filesize limit was exceeded:
                        throw new RuntimeException('An uploaded file was larger than upload_max_filesize in php.ini');
                    case UPLOAD_ERR_FORM_SIZE: //Client-side filesize limit exceeded:
                        throw new RuntimeException('A file exceeded the MAX_FILE_SIZE directive.');
                    case UPLOAD_ERR_PARTIAL: //Incomplete file upload (maybe from interrupted connection or corrupted file?)
                        throw new RuntimeException('A file was only partially uploaded.');
                    case UPLOAD_ERR_NO_FILE: //$file doesn't have a file in it.
                        throw new RuntimeException('No file was sent.');
                    case UPLOAD_ERR_NO_TMP_DIR: //No /tmp directory. Maybe fixable by changing sys_temp_dir in php.ini?
                        throw new RuntimeException('Server is missing a /tmp folder.');
                    case UPLOAD_ERR_CANT_WRITE: //If the disk is full or maybe insufficient write permissions, this may happen?
                        throw new RuntimeException('A file could not be written to disk.');
                    case UPLOAD_ERR_EXTENSION: //What PHP extension could make this happen?
                        throw new RuntimeException('An unknown PHP extension stopped the upload.');
                    default: //I genuinely have no idea if it's even possible to hit the default case.
                        throw new RuntimeException('The file upload seems to have failed, somehow.');
                }

                //The error is separate from the filesize attribute, so we'll check it:
                if($file['size'] > FILESIZE_LIMIT)
                    throw new RuntimeException('A file is bigger than what the config allows for.');
                elseif($file['size'] === 0) //I forgot how you make this happen. It was somewhere in a Stackoverflow answer...
                    throw new RuntimeException('The size of an uploaded file was 0.');
                
                //TODO: I don't believe there is a need to restrict file types at this time. I will want to know at some point if I should.

                //TODO: PICK UP HERE NEXT TIME:------------------------------
                //We need to validate the name and ensure it is unique. We need to put it into a folder, but must first verify that the folder exists.
            }
            //------------------------------------------------------------------------------------------------------------
        }
    } catch(RuntimeException $e) {
        log_msg("File upload exception: : " . $e->getMessage());
}
    //TODO: Rewrite images ourselves to strip out EXIF data (if applicable)

    //TODO: Put each file into a relevant subdir? Images go into '/img', .js goes into '/js', and so on...

    //TODO: Convert non-webp images to webp format automatically? Perhaps make it a setting in the config?
}

/*
For all the supported image types, we opt to rewrite the images (unless told not to):

.webp
.jpg/.jpeg
.png
.tiff/.tif
.raw
.bmp
.ico
.gif
*/
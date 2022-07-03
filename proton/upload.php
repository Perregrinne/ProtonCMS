<?php
//This file is a partial view for uploading files.
//It is set up to allow multiple files to be uploaded
//at once. It also has no restrictions on what types
//of files can be uploaded. The main intent is to
//allow users to upload PHP, HTML, CSS, JS, and any
//images(.png, jpg, .ico, .webp, etc). If there's a
//way too dangerous way for this to be abused by some
//malicious outsiders, I may restrict uploadable file
//types in the future. See /proton-core/upload.php to
//see how the POST stuff gets read into the project.

@include_once '/proton/proton-core/logger.php';
if(!(@include_once '/proton/proton-core/upload.php')) {
    log_msg('The uploader was not able to include the backend script.');
    exit(); //TODO: Display an error message on the page instead of exiting?
}
?>
<form action="upload.php" method="post">
    <div class="file-upload" ondrop="filesDropped(event)" ondragover="return false">
        <button id="upload-close" onclick="closeUpload()">x</button>
        <p>Drag and drop files here</p>
        <p>or</p>
        <input type="file" id="file-input" onclick="filesDropped" multiple/>
    </div>
</form>
<script>
    const formFiles;
    const selected = document.querySelector('input[type="file"][multiple]');
    function filesDropped(event) {
        event.preventDefault();
        if(selected.files.length >= 1) {
            for(let i = 0; i < selected.files.length; i++) {
                formFiles.append(`file_${i}`, selected.files[i]);
            }
            fetch('/proton/proton-core/upload.php', {
                method: 'POST',
                mode: 'cors',
                cache: 'no-cache',
                credentials: 'same-origin',
                body: formFiles,
                }
            ); //TODO: Use ".then().catch()" to check result/response from server to see if upload was successful.
        }
        else {
            console.log("Error: You tried uploading no files");
        }
    }
</script>
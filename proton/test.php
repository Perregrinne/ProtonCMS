<?php
/*=======================================================
What am I trying to achieve here?
The goal is to setup a built-in framework for client-side
and server-side testing. PHP tests are run on the backend
and Javascript tests are run on the frontend (duh). Users
will come to this page and it will read in a list of pages
for the "dev" side of the site. The user picks a page and
sets which tests to run for that page. In the future, we
could consider running a list of tests on a list of pages,
but I'll need to come back to that.

Where to pick up next time:
Pull in a list of PHP and Javascript tests and pull in a
list of "dev" pages. This is where we need to start
separating the "dev" and "prod" areas of the website. The
lists we pull in will be populated into the pages and test
inputs in the form below. Once the user selects a page,
chooses their desired tests, and hits the submit button,
the user is redirected to that page which will attempt
to be rendered while the PHP, and later, Javascript, tests
will be run. Results from the PHP tests are passed along
into the Javascript on the frontend, and all results after
the Javascript tests are passed to this page (check for $_POST[] data)
and are displayed.
=======================================================*/
    @include_once $_SERVER['DOCUMENT_ROOT'] . '/proton/proton-core/config.php';
?>
<!doctype html>
<head>
    <title>Website Testing</title>
</head>
<?php
//We'll only include this on specific pages we want to test:
//@include_once $_SERVER["DOCUMENT_ROOT"] . "/proton/proton-core/testing/test.php";
?>
<body>
    <div class="column">
        <form action="/proton/proton-core/testing/test.php" method="post"></form>
        <p id="page-p">Test which page:</p>
        <select name="page-select" id="page-select"></select>
        <p id="test-p">Select which tests to run for this page:</p>
        <div id="test-select">
            <input type="checkbox" name="testExample1" id="test-example1" checked>
            <input type="checkbox" name="testExample2" id="test-example2" checked>
        </div>
        <input type="submit" value="Go">
    </div>
<hr>
<div id="proton-javascript-tests">
    Javascript Tests:
</div>
<!-- Print out our Javascript tests here: -->
<script src="/proton/proton-core/testing/test.js" defer>
    protonTests.forEach(listProtonTests);

    function listProtonTests(test) {
        
    }
</script>
</body>
</html>
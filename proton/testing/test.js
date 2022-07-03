/*
This file serves to allow you to write tests for
the frontend of your application. It should help
you figure out if your website may have problems
rendering on mobile devices, if the page loads a
little too slowly, or if there are a11y problems
*/

//For each test you write, at the end of each test,
//you will append an instance of this class, with
//all of its fields filled out, to protonTests[].
class ProtonTest {
    //Describes what the test is testing:
    testText    = "";
    //Whether the test passed or failed:
    testPass    = false;
    //What specifically failed, or that nothing failed:
    testMessage = "";
    constructor(stringPurpose, boolPassed, stringMessage) {
        this.testText    = stringPurpose;
        this.testPass    = boolPassed;
        this.testMessage = stringMessage;
    }
}

//For every test we write, we append that to the
//list. This way, at the end of testing, we will
//print out all the tests, and which ones passed
//and which failed. The list is a dictionary and
//the key/value pairs that we append to the list
//are a string, saying what we are attempting to
//test, and a bool for if it passes, respectively
let protonTests = [];

protonTests.append(mobileScreenHorizontalTest());

//Check the page at different screen resolutions
//because on mobile screen resolutions you might
//not want users to have a horizontal scrollbar.
//protonTestFunction = mobileScreenHorizontalTest();
//protonTestText = "Screens fit horizontally on mobile screens";
//protonTests[protonTest] = false; //Always start with false!

//This test is meant to check that for all listed
//mobile screen resolutions there's no horizontal
//scrollbar shown, so users only scroll up/ down.
//The "pass" case (true) is if no scrollbar shows
//up for any listed resolutions and fails (false)
//if the horizontal scrollbar shows up even once.
function mobileScreenHorizontalTest()
{
    let test = ProtonTest();
}




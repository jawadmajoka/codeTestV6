Do at least ONE of the following tasks: refactor is mandatory. Write tests is optional, will be good bonus to see it. 
Please do not invest more than 2-4 hours on this.
Upload your results to a Github repo, for easier sharing and reviewing.

Thank you and good luck!



Code to refactor
=================
1) app/Http/Controllers/BookingController.php
2) app/Repository/BookingRepository.php

Code to write tests (optional)
=====================
3) App/Helpers/TeHelper.php method willExpireAt
4) App/Repository/UserRepository.php, method createOrUpdate


----------------------------

What I expect in your repo:

X. A readme with:   Your thoughts about the code. What makes it amazing code. Or what makes it ok code. Or what makes it terrible code. How would you have done it. Thoughts on formatting, structure, logic.. The more details that you can provide about the code (what's terrible about it or/and what is good about it) the easier for us to assess your coding style, mentality etc
There are a few best code practices which can be followed in "app/Http/Controllers/BookingController.php" and "app/Repository/BookingRepository.php"
1- You are using Models and helper function at the start of the controller by using 'use keyword' which can be handled in the composer autoload file, in that way we can don't need to use the model directory statically in every controller file, it will automatically be declared by autoloading file.
2- namespace is wrong
3- Validation is not used anywhere to validate the application's incoming data
4- try catch not used for error handling
5- In most functions else condition does not apply after the if statement. which can cause code error
6- In some places getting variable value from config/app.php and in some places getting variable value direct from env, it's better to get it from config/app.ph with default value.
7- name convention of model and classes are not proper
8- Variable name should use camel case for names with more than one word variable Like instead of $user_id, it can be $userId
9- Some functions return null at the end which is not a good practice to return null;
10- use response not handle
11- Some method's name is not meaningful, which can be good enough to describe the functionality of the method
12- Logs and comments are not handled anywhere, For a description of the method's functionality need to comment and also need to use in method
13- In some methods, just Query is applied to fetch the data from database but that one is not using anywhere which is not a good practice to load query without using it anywhere.

And 

Y.  Refactor it if you feel it needs refactoring. The more love you put into it. The easier for us to asses your thoughts, code principles etc


IMPORTANT: Make two commits. First commit with original code. Second with your refactor so we can easily trace changes. 


NB: you do not need to set up the code on local and make the web app run. It will not run as its not a complete web app. This is purely to assess you thoughts about code, formatting, logic etc


===== So expected output is a GitHub link with either =====

1. Readme described above (point X above) + refactored code 
OR
2. Readme described above (point X above) + refactored core + a unit test of the code that we have sent

Thank you!



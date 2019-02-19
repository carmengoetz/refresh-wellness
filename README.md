Project Name - Refresh Wellness Application
Client - Naqsh Kochar

Description - A social media web application with an emphasis on mental health and awareness. To be similar to Facebook or instagram, but with the ability to connect with wellness professionals. users can also aggrgate data about their mental wellness through the web application. When a user has created an account, every time they log on they are asked to enter their current mood, which they can see the histort of on their profile page. Users can also create or join events and groups. They can also write in a private journal, or comment on forum posts. The application would be marketed to organizations (like Saskpolytech) for them to see how their staff/students arae doing. Naqsh's main priority with the application is for users to be able to see their statistics and know what kinds of triggers there are. The purpose of the web application is to provide information and resources to the users to help with their mental health. 

Team Members - Abigail Williamson, Carmen Goetz, Graham Pyett, Graham Saufert, Draden Sawkey, Chris Pahl

Naming Convention - CST standards, Class names will be capitalized, attribute names will be camelcase, folder structure names will be all lowercase with _ for spaces
Unit Tests - test case classes will end with *Test where * is the name of the class you are writing a test for. Test methods will be named test* where * is the name of the method you are testing. All test classes extend TestCase

Folder Structure - The default folder structure given by Symfony. All php will be kept in the src folder, unit tests are in the test folder, app will hold HTML




!IMPORTANT
RUNNING PHPUNIT FOR FRONTEND TESTS
!IMPORTANT

Close all chrome windows then run chrome from command line (or external tools) with below arguments
--remote-debugging-address=127.0.0.1 --remote-debugging-port=9222


UPDATING SYMFONY VERSION TO 3.4.*

Open composer.json

Edit the line "symfony/symfony" under "require" to say 3.4.* instead of 3.3.*

Right-click on References and choose "Update Composer Packages" (This will take a long time)

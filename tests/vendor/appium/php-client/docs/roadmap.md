Appium PHP Client Plan
======================

Appium's PHP client will extend PHPUnit_Selenium, allowing users to do anything
that the `PHPUnit_Extensions_Selenium2TestCase` can do. The simplest way to do
this will be to subclass that TestCase class (like Sausage does in
`WebDriverTestCase`), which is appropriate for some of the functionality
(i.e., application installing, uninstalling, etc.).

Other functionality is implemented as methods in stand-alone classes extending/
implementing `PHPUnit_Extensions_Selenium2TestCase_Command` that the Autoloader
loads. So the Appium package will add the necessary functions and then update
the Autoloader configuration within the Appium package, followed by the subclassing
of the PHPUnit_Selenium element and session classes to allow for the loading of
these methods (one would wish the implementation could do it without such, but
it does not seem possible in my experimentation).

Usage will change only insofar as the test cases will need to extend the Appium
`TestCase` class rather than `PHPUnit_Extensions_Selenium2TestCase`. Differences
in elements will then be transparently abstracted away. As with the others,
functionality returned to the "official" libraries should be easily removed from
the Appium PHP client.

<?php

class PHPUnit_Extensions_AppiumTestCase_SessionStrategy_Shared
    implements PHPUnit_Extensions_Selenium2TestCase_SessionStrategy
{
    private $original;
    private $session;
    private $mainWindow;
    private $lastTestWasNotSuccessful = FALSE;

    public function __construct(PHPUnit_Extensions_Selenium2TestCase_SessionStrategy $originalStrategy)
    {
        $this->original = $originalStrategy;
    }

    public function session(array $parameters)
    {
        if ($this->lastTestWasNotSuccessful) {
            if ($this->session !== NULL) {
                $this->session->stop();
                $this->session = NULL;
            }
            $this->lastTestWasNotSuccessful = FALSE;
        }
        if ($this->session === NULL) {
            $this->session = $this->original->session($parameters);
            $this->mainWindow = $this->session->windowHandle();
        } else {
            $this->session->window($this->mainWindow);
        }
        return $this->session;
    }

    public function notSuccessfulTest()
    {
        $this->lastTestWasNotSuccessful = TRUE;
    }

    public function endOfTest(PHPUnit_Extensions_Selenium2TestCase_Session $session = NULL)
    {
    }
}

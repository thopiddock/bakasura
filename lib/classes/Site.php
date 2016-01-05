<?php

/**
 * Class Site
 */
class Site
{
    /**
     * @var \ErrorHandler
     */
    public static $errorHandler;

    /**
     * @var IAuthenticator
     * */
    private static $authenticator;

    /**
     * @var IPage
     */
    public $selectedPage;

    /**
     * @var \CreatePage IPage[]
     */
    public $activePages;

    /**
     * @var \DefaultTemplate|IPage
     */
    public $selectedTemplate;

    /**
     * @return BaseAuthenticator
     */
    public static function GetAuthenticator()
    {
        if (!isset(self::$authenticator))
        {
            self::$authenticator = new FacebookAuthenticator();
        }

        return self::$authenticator;
    }

    public function printSite()
    {
        print '<!DOCTYPE html>'.$this->selectedTemplate->getHtml();
    }

    /**
     * Initialises a new instance of the @see Site class.
     */
    public function __construct()
    {
        self::$errorHandler = new ErrorHandler();

        self::GetAuthenticator()->reauthenticate();

        $navigationLinks = Config::GetGroup('navigation');

        // Set the default page to the first set navigation link
        $pageName      = reset($navigationLinks);
        $pageClassName = $pageName . 'Page';

        if (!empty($_GET['v']))
        {
            $pageName      = $_GET['v'];
            $pageClassName = $pageName . 'Page';
        }

        if (class_exists($pageClassName) && in_array('IPage', class_implements($pageClassName)))
        {
            $this->selectedPage = new $pageClassName();
        }
        else
        {
            $dynamicPages = PageHandler::ReadPages();
            if ($dynamicPages != null)
            {
                // Resolve the page type
                // If it exists in SQL then load it
                if (isset($dynamicPages[$pageName]))
                {
                    $databasePage       = $dynamicPages[$pageName];
                    $this->selectedPage = new DynamicPage($databasePage);
                }
                else
                {
                    $this->selectedPage = new CreatePage($pageName);
                }
            }
        }

        $cookieName = 'template';
        if (isset($_GET['template']))
        {
            $template    = $_GET['template'] . 'Template';
            $cookieValue = $template;
            setcookie($cookieName, $cookieValue, time() + (86400 * 30), "/");
        }
        $template = isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : Config::GetValue('template', 'style') . 'Template';
        if (class_exists($template))
        {
            $this->selectedTemplate = new $template($this->selectedPage);
        }
        else
        {
            $this->selectedTemplate = new DefaultTemplate($this->selectedPage);
        }
    }
}
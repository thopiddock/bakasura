<?php

/**
 * Class BaseTemplate
 */
abstract class BaseTemplate implements ITemplate
{
    /**
     * @var IPage
     */
    protected $selectedPage;
    /**
     * @var array
     */
    protected $styleSheets;
    /**
     * @var array
     */
    protected $scripts;

    /**
     * @return string
     */
    public function getHtml()
    {
        $head = '<head>' . $this->getMeta() . $this->getHead() . '</head>';
        $body = '<body><div id="wrapper" class="clearfix">' . $this->getBody() . '</div></body>';
        $html = '<html>' . $head . $body . '</html>';

        return $html;
    }

    /**
     * @return mixed
     */
    protected abstract function getHead();

    /**
     * @return string
     */
    protected function getMeta()
    {
        $metaHtml = '<meta name="viewport" content="width=device-width, initial-scale=1" />';

        $metaHtml .= '<title>' . $this->selectedPage->getTitle() . '</title>';
        $styleSheetsHtml = $this->getStyleSheets();
        $scriptsHtml = $this->getScripts();

        return $metaHtml . $scriptsHtml . $styleSheetsHtml;
    }

    /**
     * @return string
     */
    final function getStyleSheets()
    {
        $styleSheetsHtml = '';
        $styleSheetsValue = $this->selectedPage->getStyleSheets();
        if (is_array($styleSheetsValue))
        {
            foreach ($styleSheetsValue as $styleSheet)
            {
                $this->styleSheets[] = $styleSheet;
            }
        }
        elseif (is_string($styleSheetsValue))
        {
            $this->styleSheets[] = $styleSheetsValue;
        }

        foreach ($this->styleSheets as $styleSheet)
        {
            if ($styleSheet)
            {
                $styleSheetsHtml .= '<link rel="stylesheet" href="' . $styleSheet . '">';
            }
        }

        return $styleSheetsHtml;
    }

    /**
     * @return string
     */
    final function getScripts()
    {
        $scriptsHtml = '';
        $scriptsValue = $this->selectedPage->getScripts();
        if (is_array($scriptsValue))
        {
            foreach ($scriptsValue as $script)
            {
                $this->scripts[] = $script;
            }
        }
        elseif (is_string($scriptsValue))
        {
            $this->scripts[] = $scriptsValue;
        }

        foreach ($this->scripts as $script)
        {
            if ($script)
            {
                $scriptsHtml .= '<script type="text/javascript" src="' . $script . '"></script>';
            }
        }

        return $scriptsHtml;
    }

    /**
     * @return mixed
     */
    protected abstract function getBody();

    /**
     * @param IPage $page
     */
    protected function __construct(IPage $page)
    {
        $this->selectedPage = $page;
        $this->styleSheets = [];
        $this->scripts = [];

        $this->styleSheets[] = '/res/css/normalize.css';
        $this->styleSheets[] = '/res/css/base.css';
        $this->styleSheets[] = 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css';
        $this->scripts[] = 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js';
        $this->scripts[] = 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js';
        $this->scripts[] = '/res/js/core.js';
        if (Site::GetAuthenticator()->getAuthenticationLevel() >= AuthenticationLevelEnum::Editor)
        {
            $this->scripts[] = '/res/js/admin.js';
        }
    }
}
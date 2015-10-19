<?php

/**
 * Class DefaultTemplate
 */
class DefaultTemplate extends BaseTemplate
{
    /**
     * @return mixed
     */
    protected function getHead()
    {
        $head = '<script> $(function() {    $("#content").sortable().disableSelection();  });</script>';

        if (isset($_GET['debug']) && is_int($_GET['debug']) && $_GET['debug'] == 1)
        {
            $head .= '<style>* {outline: 1px rgba(100, 0, 0, 0.25) solid !important;}</style>';
        }

        return $head;
    }

    /**
     * @return mixed
     */
    protected function getBody()
    {
        $headerFragment = new HeaderFragment($this->selectedPage);
        $footerFragment = new FooterFragment();
        $errorFragment = new ErrorFragment($this->selectedPage);
        $finalFragment = new FinalFragment($this->selectedPage);

        $header = $headerFragment->getContent();
        $content = '<div id="content">' . $this->selectedPage->getContent();

        $selectedPage = $this->selectedPage;

        $content .= '</div>';
        $content .= '<div id="editor">';
        if (Site::GetAuthenticator()->getAuthenticationLevel() >= AuthenticationLevelEnum::Editor
            && $selectedPage instanceof IEditor)
        {
            /* @var $selectedPage IEditor */
            $content .= $selectedPage->getEditor();
        }
        $content .= '</div>';
        $footer = $footerFragment->getContent();
        $errors = $errorFragment->getContent();
        $final = $finalFragment->getContent();

        $body = $header . $errors . $content . $footer . $final;

        return $body;
    }

    /**
     * @param IPage $page
     */
    public function __construct(IPage $page)
    {
        parent::__construct($page);
        $this->styleSheets[] = 'http://fonts.googleapis.com/css?family=Raleway:400,200,500,700';
        $this->styleSheets[] = '/res/css/default.css';
        $this->scripts[] = 'http://malsup.github.com/chili-1.7.pack.js';
    }
}
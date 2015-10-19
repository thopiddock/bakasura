<?php

class HeaderFragment implements  IFragment
{
    private $selectedPage;

    function getContent()
    {
        $siteName        = Config::GetValue('siteName');
        $siteLink        = Config::GetValue('base', 'urls');
        $navigationLinks = Config::GetGroup('navigation');

        $html = '<header id="site-header">';
        $logo = file_exists(RES_DIR . 'img/logo.png') ? RES_DIR . 'img/logo.png' : '';
        $html .= '<h1><a href="' . $siteLink . '">' . (empty($logo) ? $siteName : ('<img src="' . $logo . '"/>')) . '</a></h1>';
        $html .= '<div class="menu-icon"><a href="#menu"><img class="svg" src="/res/img/icon_menu.svg" alt="Menu"/></a></div>';
        $html .= '<nav id="header-menu"><ul class="horizontal-list">';

        while ($navigationLink = current($navigationLinks))
        {
            $active = $navigationLink == $this->selectedPage->getShortName();

            $html .= '<li class="' . ($active ? 'active-page large' : 'large') . '"><a href="' . $navigationLink . '">';
            $html .= key($navigationLinks);
            $html .= '</a></li>';

            next($navigationLinks);
        }

        $html .= '</ul></nav>';
        $html .= '</header>';

        ob_start();
        ?>
        <script>
            $(function () {
                $("a[href=#menu]").click(function (e) {
                    $("#header-menu").toggleClass("menu-open");
                    e.preventDefault();
                });
            });
        </script>
        <?php
        $html .= ob_get_contents();
        ob_end_clean();

        return $html;
    }

    public function __construct($selectedPage)
    {
        $this->selectedPage = $selectedPage;
    }
}
<?php

class FooterFragment implements IFragment
{
    function getContent ()
    {
        $copyright = Config::GetValue('copyright');

        $html = '<footer>';
        $html .= $copyright;
        $html .= '<br>Powered by the Bakasura Framework created by <a href="http://www.tompiddock.com">Tom Piddock</a>';
        $html .= '<br><a href="/login">Login</a>';
        $html .= '</footer>';

        return $html;
    }
}
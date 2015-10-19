<?php

class FinalFragment implements IFragment
{
    function getContent ()
    {
        $html  = '<script src="//my.stats2.com/js" type="text/javascript"></script>';
        $html .= '<script type="text/javascript">try{ stats2.init(100848947); }catch(e){}</script>';
        $html .= '<noscript><p><img alt="Stats2" width="1" height="1" src="//my.stats2.com/100848947ns.gif" /></p></noscript>';
        return $html;
    }
}
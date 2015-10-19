<?php
const comicLib = 'lib/';
include_once 'ComicPage.php';
include_once comicLib . 'ChapterModel.php';
include_once comicLib . 'ComicModel.php';
include_once comicLib . 'ComicDatabaseHandler.php';
include_once comicLib . 'PageModel.php';
class ComicBookPlugin implements IPlugin{


    function canGeneratePage(){}
    function canGenerateFragment(){}

    function getPage(){}
    function getFragment(){}
    
    function getPrefix(){}
    
    function install(){}
}
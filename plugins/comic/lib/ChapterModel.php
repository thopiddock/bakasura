<?php

class ChapterModel
{
    public $name;
    public $number;
    public $description;
    
    public $pages;
    
    function addPage($page){
        $this->pages[] = $page;        
    }

    function __construct()
    {
        $this->pages = array();
    }
}
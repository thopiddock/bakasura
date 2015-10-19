<?php

class PageModel
{
    public $id;
    public $chapter;
    public $number;
    public $orderNumber;
    public $title;
    public $description;
    public $image;

    public $firstPage;
    public $prevPage;
    public $nextPage;
    public $lastPage;

    private function getPageFromDatabase()
    {
        $result = ComicDatabaseHandler::ReadPage($this->chapter, $this->number);
        if ($result)
        {
            $this->id = $result['id'];
            $this->title = $result['title'];
            $this->description = $result['description'];
            $this->image = $result['image'];
            $this->orderNumber = $result['orderNumber'];
        }
    }

    public function getPageNavigation()
    {
        $result = ComicDatabaseHandler::ReadPageNavigation($this->orderNumber);
        if ($result)
        { 
            while (list ($key, $val) = each($result))
            {
                switch ($key)
                {
                    case 'first':
                        $this->firstPage = new PageModel($val['chapter'], $val['number']);
                        break;
                    case 'prev':
                        $this->prevPage = new PageModel($val['chapter'], $val['number']);
                        break;
                    case 'next':
                        $this->nextPage = new PageModel($val['chapter'], $val['number']);
                        break;
                    case 'last':
                        $this->lastPage = new PageModel($val['chapter'], $val['number']);
                        break;
                }
            }
        }
    }

    public function updatePageDetails($title, $description, $image)
    {
        $result = ComicDatabaseHandler::UpdatePageDetails($this->id, $title, $description, $image );
    }

    public function initialise()
    {
        $this->getPageFromDatabase();
        $this->getPageNavigation();
    }

    public function __construct($chapter, $number)
    {
        $this->chapter = $chapter;
        $this->number = $number;
    }
}
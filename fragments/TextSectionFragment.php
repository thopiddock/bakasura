<?php

/**
 * Created by PhpStorm.
 * User: tpidd
 * Date: 27/08/2015
 * Time: 13:54
 */
class TextSectionFragment extends BaseSectionFragment implements IFragment, IDatabaseLoadable, IEditor, IAction
{
    /**
     * @var string HTML content of the fragment.
     */
    private $text;
    /**
     * @var null string Header of the fragment.
     */
    private $header;
    /**
     * @var null int ID of the fragment if pulled from the database.
     */
    private $id;

    /**
     * @param array $databaseValue The database content for a fragment.
     * @return TextSectionFragment
     */
    static function generateFromDatabase($databaseValue)
    {
        return new TextSectionFragment($databaseValue['content'], $databaseValue['header'], $databaseValue['id']);
    }

    /**
     * @return string
     */
    function getContent()
    {
        $content = '<section class="text-section"';
        if (isset($this->id))
        {
            $content .= ' data-id="' . $this->id . '"';
        }

        $content .= '>';

        if (isset($this->header))
        {
            $content .= '<h3>' . $this->header . '</h3>';
        }

        $content .= '<p>';
        $content .= $this->text;
        $content .= '</p></section>';

        return $content;
    }

    /**
     * @return string
     */
    function getDisplayName()
    {
        return "Text Section";
    }

    /**
     * @param string        $text
     * @param string | null $header
     * @param int | null    $id
     */
    public function __construct($text, $header = null, $id = null)
    {
        $this->text = $text;
        $this->header = $header;
        $this->id = $id;
    }
}
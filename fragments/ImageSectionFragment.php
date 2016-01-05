<?php

/**
 * Created by PhpStorm.
 * User: tpidd
 * Date: 27/08/2015
 * Time: 14:00
 */
class ImageSectionFragment extends BaseSectionFragment implements IFragment, IDatabaseLoadable, IAction, IEditor
{
    /**
     * @var string The image source.
     */
    private $_imageSrc;
    /**
     * @var string The image class.
     */
    private $_imageClass;
    /**
     * @var string | null The content text.
     */
    private $_text;
    /**
     * @var string | null The header.
     */
    private $_header;
    /**
     * @var int | null The id.
     */
    private $_id;

    /**
     * @param $databaseValue
     * @return ImageSectionFragment
     */
    public static function generateFromDatabase($databaseValue)
    {
        return new ImageSectionFragment($databaseValue['media'], $databaseValue['options'], $databaseValue['content'], $databaseValue['header'], $databaseValue['id']);
    }

    /**
     * @param string       $action
     * @param array|string $vars
     * @return string|void
     */
    public static function processAction($action, $vars)
    {
        $content = '';
        $success = true;
        switch ($action)
        {
            case 'create':
                $content = 'Stuff';
                break;
            case 'update':

                break;
        }

        header("content-type:application/json");
        $result = json_encode(array('returnHtml' => $content, 'success' => $success));
        echo $result;
    }

    /**
     * @return string
     */
    function getContent()
    {
        $content = '<section class="image-section""';
        if (isset($this->_id))
        {
            $content .= ' data-id="' . $this->_id . '"';
        }

        $content .= '>';
        $content .= '<img src="' . $this->_imageSrc . '" ';

        if (isset($this->_imageClass))
        {
            $content .= 'class="' . $this->_imageClass . '" ';
        }

        $content .= '/>';

        if (isset($this->_header))
        {
            $content .= '<h3>' . $this->_header . '</h3>';
        }

        if (isset($this->_text))
        {
            $content .= '<p>' . $this->_text . '</p>';
        }

        $content .= '</section>';

        return $content;
    }

    function getDisplayName()
    {
        return "Image Section";
    }

    public function __construct($imageSrc, $imageClass, $text = null, $header = null, $id = null)
    {
        $this->_imageSrc = $imageSrc;
        $this->_imageClass = $imageClass;
        $this->_text = $text;
        $this->_header = $header;
        $this->_id = $id;
    }
}
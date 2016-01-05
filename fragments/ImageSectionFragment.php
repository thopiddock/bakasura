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
    private $imageSrc;
    /**
     * @var string The image class.
     */
    private $imageClass;
    /**
     * @var string | null The content text.
     */
    private $text;
    /**
     * @var string | null The header.
     */
    private $header;
    /**
     * @var int | null The id.
     */
    private $id;
    /**
     * @var null
     */
    private $index;

    /**
     * @param $databaseValue
     * @return ImageSectionFragment
     */
    public static function generateFromDatabase($databaseValue)
    {
        return new ImageSectionFragment(
            $databaseValue['media'],
            $databaseValue['options'],
            $databaseValue['id'],
            $databaseValue['index'],
            $databaseValue['header'],
            $databaseValue['content']);
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
                self::processActionCreate($vars, $response);
                break;

            case 'update':
                self::processActionUploadFile();
                self::processActionUpdate($vars, $response);
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
        $content = '<section class="fragment-content image-section">';
        $content .= '<img src="' . $this->imageSrc . '" ';

        if (isset($this->imageClass))
        {
            $content .= 'class="' . $this->imageClass . '" ';
        }

        $content .= '/>';

        if (isset($this->header))
        {
            $content .= '<h3>' . $this->header . '</h3>';
        }

        if (isset($this->text))
        {
            $content .= '<p>' . $this->text . '</p>';
        }

        $content .= '</section>';

        return $content;
    }

    /**
     * @return string
     */
    function getDisplayName()
    {
        return "Image Section";
    }

    /**
     * Get HTML to represent an editor section.
     *
     * @return string
     */
    function getEditor()
    {
       return $this->getContent();
    }

    /**
     * ImageSectionFragment constructor.
     *
     * @param      $imageSrc
     * @param      $imageClass
     * @param null $id
     * @param null $index
     * @param null $header
     * @param null $text
     */
    public function __construct($imageSrc, $imageClass, $id = null, $index = null, $header = null, $text = null)
    {
        $this->imageSrc = $imageSrc;
        $this->imageClass = $imageClass;
        $this->id = $id;
        $this->index = $index;
        $this->header = $header;
        $this->text = $text;
    }
}
<?php

/**
 * Created by PhpStorm.
 * User: tpidd
 * Date: 08/11/2015
 * Time: 01:09
 */
class BaseSectionFragment
{
    /**
     * @param $vars
     * @param $response
     */
    protected static function processActionCreate($vars, &$response){
        $result = FragmentHandler::CreateFragment($vars['pageId'],
            $vars['index'],
            $vars['type'],
            $vars['header'],
            $vars['content'],
            $vars['media'],
            $vars['options']);
        if ($result)
        {
            $success = true;
            $fragmentData = FragmentHandler::ReadFragment($result);
            $fragmentClass = $fragmentData['type'] . 'Fragment';
            if (class_exists($fragmentClass) && in_array('IDatabaseLoadable', class_implements($fragmentClass)))
            {
                /* @@var $fragmentClass IDatabaseLoadable */
                /* @@var $returnFragment IFragment */
                $returnFragment = $fragmentClass::generateFromDatabase($fragmentData);
                $response = ['success' => $success, 'fragment' => $returnFragment->getContent()];
            }
        }
    }

    /**
     * @param $vars
     * @param $response
     * @return array
     */
    protected static function processActionUpdate($vars, &$response)
    {
        $id = $vars['id'];
        $result = FragmentHandler::UpdateFragment($id,
            $vars['index'],
            $vars['type'],
            $vars['header'],
            $vars['content'],
            $vars['media'],
            $vars['options']);

        if ($result)
        {
            $success = true;
            $fragmentData = FragmentHandler::ReadFragment($id);
            $fragmentClass = $fragmentData['type'] . 'Fragment';
            if (class_exists($fragmentClass) && in_array('IDatabaseLoadable', class_implements($fragmentClass)))
            {
                /* @@var $fragmentClass IDatabaseLoadable */
                /* @@var $returnFragment IFragment */
                $returnFragment = $fragmentClass::generateFromDatabase($fragmentData);
                $response = ['success' => $success, 'fragment' => $returnFragment->getContent()];
            }
        }
    }

    protected static function processActionUploadFile(){
        try {
            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
            if (
                !isset($_FILES['upfile']['error']) ||
                is_array($_FILES['upfile']['error'])
            ) {
                throw new RuntimeException('Invalid parameters.');
            }

            // Check $_FILES['upfile']['error'] value.
            switch ($_FILES['upfile']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Exceeded filesize limit.');
                default:
                    throw new RuntimeException('Unknown errors.');
            }

            // You should also check filesize here.
            if ($_FILES['upfile']['size'] > 1000000) {
                throw new RuntimeException('Exceeded filesize limit.');
            }

            // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
            // Check MIME Type by yourself.
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search(
                    $finfo->file($_FILES['upfile']['tmp_name']),
                    array(
                        'jpg' => 'image/jpeg',
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                    ),
                    true
                )) {
                throw new RuntimeException('Invalid file format.');
            }

            // You should name it uniquely.
            // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
            // On this example, obtain safe unique name from its binary data.
            if (!move_uploaded_file(
                $_FILES['upfile']['tmp_name'],
                sprintf('./uploads/%s.%s',
                    sha1_file($_FILES['upfile']['tmp_name']),
                    $ext
                )
            )) {
                throw new RuntimeException('Failed to move uploaded file.');
            }

            echo 'File is uploaded successfully.';

        } catch (RuntimeException $e) {

            echo $e->getMessage();

        }

    }
}
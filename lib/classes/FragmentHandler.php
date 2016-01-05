<?php

/**
 * Fragment Handler class. Helps retrieve page information from the database.
 * User: tpidd
 * Date: 04/11/2015
 * Time: 01:13
 */
class FragmentHandler
{
    /**
     * @param $pageId
     * @param $index
     * @param $type
     * @param $header
     * @param $content
     * @param $media
     * @param $options
     *
     * @return bool
     */
    public static function CreateFragment($pageId, $index, $type, $header, $content, $media, $options)
    {
        $result = false;

        if (Site::GetAuthenticator()->getAuthenticationLevel() >= AuthenticationLevelEnum::Editor)
        {
            $conn = SqlConnection::getConnection(Site::$errorHandler);
            if ($conn)
            {
                $query = "CALL `createFragment`(?,?,?,?,?,?,?);";

                if ($stmt = $conn->prepare($query))
                {
                    $stmt->bind_param('iisssss', $pageId, $index, $type, $header, $content, $media, $options);

                    $success = $stmt->execute();
                    if ($success)
                    {
                        $stmt->bind_result($id);
                        while ($stmt->fetch())
                        {
                            $result = $id;
                        }
                    }

                    $stmt->close();
                }
            }
        }

        return $result;
    }

    /**
     * Read the page fragments for a given page id.
     *
     * @param $pageId int The page id.
     *
     * @return array|null Returns an array of page fragments or null.
     */
    public static function ReadPageFragments($pageId)
    {
        $returnArray = null;
        $conn = SqlConnection::getConnection(Site::$errorHandler);
        if ($conn)
        {
            $query = "CALL `readPageFragments`(?);";

            if ($stmt = $conn->prepare($query))
            {
                $stmt->bind_param('i', $pageId);
                $stmt->execute();
                $stmt->bind_result(
                    $id,
                    $index,
                    $type,
                    $header,
                    $content,
                    $media,
                    $options);

                $returnArray = [];
                while ($stmt->fetch())
                {
                    $returnArray[$index] = ['id'      => $id,
                                            'index'   => $index,
                                            'type'    => $type,
                                            'header'  => $header,
                                            'content' => $content,
                                            'media'   => $media,
                                            'options' => $options];
                }

                $stmt->close();
            }
        }

        return $returnArray;
    }

    /**
     * Read the fragments for a given page id.
     *
     * @param $id int The fragment id.
     *
     * @return array|null Returns an array of page fragments or null.
     */
    public static function ReadFragment($id)
    {
        $returnArray = null;
        $conn = SqlConnection::getConnection(Site::$errorHandler);
        if ($conn)
        {
            $query = "CALL `readFragment`(?);";

            if ($stmt = $conn->prepare($query))
            {
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $stmt->bind_result(
                    $id,
                    $pageId,
                    $index,
                    $type,
                    $header,
                    $content,
                    $media,
                    $options);

                $returnArray = [];
                while ($stmt->fetch())
                {
                    $returnArray = ['id'      => $id,
                                    'pageId'  => $pageId,
                                    'index'   => $index,
                                    'type'    => $type,
                                    'header'  => $header,
                                    'content' => $content,
                                    'media'   => $media,
                                    'options' => $options];
                }

                $stmt->close();
            }
        }

        return $returnArray;
    }

    /**
     * Update the fragment for a given id.
     *
     * @param $id int The fragment id.
     * @param $index int The fragment index on the page.
     * @param $type string The type of fragment.
     * @param $header string The header.
     * @param $content string The content.
     * @param $media string The media.
     * @param $options array The options to apply.
     *
     * @return array|null Returns an array of page fragments or null.
     */
    public static function UpdateFragment($id, $index, $type, $header, $content, $media, $options)
    {
        $result = false;

        if (Site::GetAuthenticator()->getAuthenticationLevel() >= AuthenticationLevelEnum::Editor)
        {
            $conn = SqlConnection::getConnection(Site::$errorHandler);
            if ($conn)
            {
                $query = "CALL `updateFragment`(?,?,?,?,?,?,?);";

                if ($stmt = $conn->prepare($query))
                {
                    $stmt->bind_param('iisssss', $id, $index, $type, $header, $content, $media, $options);

                    $success = $stmt->execute();
                    if ($success)
                    {
                        $result = true;
                    }

                    $stmt->close();
                }
            }
        }

        return $result;
    }
}
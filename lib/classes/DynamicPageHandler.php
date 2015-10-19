<?php

/**
 * Dynamic Page Handler class. Helps retrieve page information from the database.
 * User: tpidd
 * Date: 31/08/2015
 * Time: 13:34
 */
class DynamicPageHandler
{

    /**
     * @param $name
     * @param $shortName
     * @param $scriptLink
     * @param $styleLink
     * @param $parentPageId
     * @param $summary
     *
     * @return bool
     */
    public static function CreatePage($name, $shortName, $scriptLink, $styleLink, $parentPageId, $summary)
    {
        $success = false;

        if (Site::GetAuthenticator()->getAuthenticationLevel() >= AuthenticationLevelEnum::Editor) {
            $conn = ConnectionHandler::getConnection(Site::$errorHandler);
            if ($conn) {
                $query = "CALL `createPage`(?,?,?,?,?,?);";

                if ($stmt = $conn->prepare($query)) {
                    $stmt->bind_param('ssssis', $name, $shortName, $scriptLink, $styleLink, $parentPageId, $summary);
                    $success = $stmt->execute();
                    $stmt->close();
                }
            }
        }

        return $success;
    }

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

        if (Site::GetAuthenticator()->getAuthenticationLevel() >= AuthenticationLevelEnum::Editor) {
            $conn = ConnectionHandler::getConnection(Site::$errorHandler);
            if ($conn) {
                $query = "CALL `createFragment`(?,?,?,?,?,?,?);";

                if ($stmt = $conn->prepare($query)) {
                    $stmt->bind_param('iisssss', $pageId, $index, $type, $header, $content, $media, $options);

                    $success = $stmt->execute();
                    if ($success) {
                        $stmt->bind_result($id);
                        while ($stmt->fetch()) {
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
     * Read the custom pages available in the database.
     * @return array|null Returns a list of pages or null.
     */
    static function ReadPages()
    {
        $returnArray = null;
        $conn = ConnectionHandler::getConnection(Site::$errorHandler);
        if ($conn) {
            $query = "CALL `readPages`();";

            if ($stmt = $conn->prepare($query)) {
                $stmt->execute();
                $stmt->bind_result(
                    $id,
                    $name,
                    $shortName,
                    $styleLink,
                    $scriptLink,
                    $parentPageId,
                    $summary);

                $returnArray = [];
                while ($stmt->fetch()) {
                    $returnArray[$shortName] =
                        ['id' => $id,
                            'name' => $name,
                            'shortName' => $shortName,
                            'styleLink' => $styleLink,
                            'scriptLink' => $scriptLink,
                            'parentPageId' => $parentPageId,
                            'summary' => $summary];
                }

                $stmt->close();
            }
        }

        return $returnArray;
    }

    /**
     * Read the page fragments for a given page id.
     *
     * @param $pageId int The page id.
     *
     * @return array|null Returns an array of page fragments or null.
     */
    static function ReadPageFragments($pageId)
    {
        $returnArray = null;
        $conn = ConnectionHandler::getConnection(Site::$errorHandler);
        if ($conn) {
            $query = "CALL `readPageFragments`(?);";

            if ($stmt = $conn->prepare($query)) {
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
                while ($stmt->fetch()) {
                    $returnArray[$index] = ['id' => $id,
                        'index' => $index,
                        'type' => $type,
                        'header' => $header,
                        'content' => $content,
                        'media' => $media,
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
        $conn = ConnectionHandler::getConnection(Site::$errorHandler);
        if ($conn) {
            $query = "CALL `readFragment`(?);";

            if ($stmt = $conn->prepare($query)) {
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
                while ($stmt->fetch()) {
                    $returnArray = ['id' => $id,
                        'pageId' => $pageId,
                        'index' => $index,
                        'type' => $type,
                        'header' => $header,
                        'content' => $content,
                        'media' => $media,
                        'options' => $options];
                }

                $stmt->close();
            }
        }

        return $returnArray;
    }
}
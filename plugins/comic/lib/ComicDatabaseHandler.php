<?php

/**
 * Class ComicDatabaseHandler
 */
class ComicDatabaseHandler
{
    /**
     * Read a comic page from the database.
     * @param $chapter
     * @param $number
     *
     * @return array|null
     */
    static function ReadPage($chapter, $number)
    {
        $returnArray = null;
        $conn        = ConnectionHandler::getConnection(Site::$errorHandler);

        if ($conn)
        {
            $query = "CALL `comic_readPage`(?, ?);";

            if ($stmt = $conn->prepare($query))
            {
                $stmt->bind_param('ii', $chapter, $number);
                $stmt->execute();
                $stmt->bind_result($id, $title, $description, $image, $orderNumber);
                $stmt->fetch();

                $returnArray                = [];
                $returnArray['id']          = $id;
                $returnArray['title']       = $title;
                $returnArray['description'] = $description;
                $returnArray['image']       = $image;
                $returnArray['orderNumber'] = $orderNumber;

                $stmt->close();
            }
        }

        return $returnArray;
    }

    /**
     * Read the 4 navigation pages for a particular comic number.
     * @param $orderNumber
     *
     * @return array|null
     */
    static function ReadPageNavigation($orderNumber)
    {
        $returnArray = null;
        $conn        = ConnectionHandler::getConnection();

        if ($conn)
        {
            $query = "CALL `comic_readPageNavigation`(?);";

            if ($stmt = $conn->prepare($query))
            {
                $stmt->bind_param('i', $orderNumber);
                $stmt->execute();
                $stmt->bind_result($key, $chapter, $number);

                $returnArray = [];
                while ($stmt->fetch())
                {
                    $returnArray[$key] = ['chapter' => $chapter, 'number' => $number];
                }

                $stmt->close();
            }
        }

        return $returnArray;
    }

    /**
     * Read the latest comic page.
     * @return array|null
     */
    static function ReadLatestPage()
    {
        $returnPage = null;
        $conn       = ConnectionHandler::getConnection();

        if ($conn)
        {
            $query = "CALL `comic_readLatestPage`();";

            if ($stmt = $conn->prepare($query))
            {
                $stmt->execute();
                $stmt->bind_result($chapter, $number);

                while ($stmt->fetch())
                {
                    $returnPage = ['chapter' => $chapter, 'number' => $number];
                }

                $stmt->close();
            }
        }

        return $returnPage;
    }

    /**
     * Update the comic page details.
     * @param $pageId
     * @param $title
     * @param $description
     * @param $image
     *
     * @return bool
     */
    static function UpdatePageDetails($pageId, $title, $description, $image)
    {
        $success = false;
        if (Site::GetAuthenticator()->getAuthenticationLevel() >= AuthenticationLevelEnum::Editor)
        {
            $conn = ConnectionHandler::getConnection();
            if ($conn)
            {
                $query = "CALL `comic_updatePageDetails`(?, ?, ?, ?);";

                if ($stmt = $conn->prepare($query))
                {
                    $stmt->bind_param('isss', $pageId, $title, $description, $image);
                    $success = $stmt->execute();
                    $stmt->close();
                }
            }
        }

        return $success;
    }

    /**
     * Delete the comic page.
     * @param $pageId
     *
     * @return bool
     */
    static function DeletePage($pageId)
    {
        $success = false;
        if (Site::GetAuthenticator()->getAuthenticationLevel() >= AuthenticationLevelEnum::Editor)
        {
            $conn = ConnectionHandler::getConnection();
            if ($conn)
            {
                $query = "CALL `comic_deletePage`(?);";

                if ($stmt = $conn->prepare($query))
                {
                    $stmt->bind_param('i', $pageId);
                    $success = $stmt->execute();
                    $stmt->close();
                }
            }
        }

        return $success;
    }
}
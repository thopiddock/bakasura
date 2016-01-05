<?php

/**
 * Page Handler class. Helps retrieve page information from the database.
 * User: tpidd
 * Date: 31/08/2015
 * Time: 13:34
 */
class PageHandler
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
            $conn = SqlConnection::getConnection(Site::$errorHandler);
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
     * Read the custom pages available in the database.
     * @return array|null Returns a list of pages or null.
     */
    static function ReadPages()
    {
        $returnArray = null;
        $conn = SqlConnection::getConnection(Site::$errorHandler);
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
}
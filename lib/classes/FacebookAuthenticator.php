<?php

/**
 * Created by PhpStorm.
 * User: Porcupine
 * Date: 31/07/2015
 * Time: 18:12
 */
class FacebookAuthenticator extends BaseAuthenticator
{
    function authenticate()
    {
        $newFacebookId = $this->getFacebookId();
        $newFullname   = $this->getFullname();
        $newEmail      = $this->getEmail();

        Site::$errorHandler->addError(new SimpleError("ID: $newFacebookId NAME: $newFullname EMAIL: $newEmail ", ErrorSeverityEnum::Message));

        $exists  = false;
        $success = false;
        $conn    = SqlConnection::getConnection();
        if ($conn)
        {
            $query = "CALL `readUser`(?);";

            if ($stmt = $conn->prepare($query))
            {
                $stmt->bind_param('i', $newFacebookId);
                $stmt->execute();
                $stmt->bind_result($uid, $facebookId, $fullname, $email, $authLevel);
                $stmt->fetch();

                if ($facebookId == $newFacebookId)
                {
                    $exists = true;
                    if (AuthenticationLevelEnum::isValidValue($authLevel))
                    {
                        $this->setAuthenticationLevel($authLevel);
                    }
                    else
                    {
                        Site::$errorHandler->addError(new SimpleError("Could not authenticate the user.", ErrorSeverityEnum::Error));
                    }
                }

                $stmt->close();
            }

            if ($exists)
            {
                // If Returned user, update the user record
                $query = "CALL `updateUser`(?, ?, ?)";

                if ($stmt = $conn->prepare($query))
                {
                    $stmt->bind_param('iss', $facebookId, $fullname, $newEmail);
                    $success = $stmt->execute();

                    if ($stmt->errno > 0)
                    {
                        Site::$errorHandler->addError(new SimpleError("SQL SimpleError: $stmt->error", ErrorSeverityEnum::Error));
                    }

                    $stmt->close();

                    if (!$success)
                    {
                        Site::$errorHandler->addError(new SimpleError("Could not update the user.", ErrorSeverityEnum::Error));
                    }
                }
            }
            else
            {
                // If new user, create a new record
                $query = "CALL `createUser`(?, ?, ?)";
                if ($stmt = $conn->prepare($query))
                {
                    $stmt->bind_param('iss', $newFacebookId, $newFullname, $newEmail);
                    $success = $stmt->execute();

                    if ($stmt->errno > 0)
                    {
                        Site::$errorHandler->addError(new SimpleError("SQL SimpleError: $stmt->error", ErrorSeverityEnum::Error));
                    }

                    $stmt->close();

                    if (!$success)
                    {
                        Site::$errorHandler->addError(new SimpleError("Could not create the user.", ErrorSeverityEnum::Error));
                    }
                }
            }
        }

        return $success;
    }

    function getFacebookId()
    {
        FacebookAuthenticator::startSession();

        if (isset($_SESSION['FBID']))
        {
            return $_SESSION['FBID'];
        }
        else
        {
            return false;
        }
    }

    function getFullname()
    {
        FacebookAuthenticator::startSession();
        if (isset($_SESSION['FULLNAME']))
        {
            return $_SESSION['FULLNAME'];
        }
        else
        {
            return '';
        }
    }

    function getEmail()
    {
        FacebookAuthenticator::startSession();

        if (isset($_SESSION['EMAIL']))
        {
            return $_SESSION['EMAIL'];
        }
        else
        {
            return '';
        }
    }

    function reauthenticate()
    {
        if ($this->getFacebookId())
        {
            $newFacebookId = $this->getFacebookId();
            $conn          = SqlConnection::getConnection();
            if ($conn)
            {
                $query = "CALL `readUser`(?);";

                if ($stmt = $conn->prepare($query))
                {
                    $stmt->bind_param('i', $facebookId);
                    $stmt->execute();
                    $stmt->bind_result($uid, $facebookId, $fullname, $email, $authLevel);
                    $stmt->fetch();

                    if ($facebookId == $newFacebookId)
                    {
                        if (AuthenticationLevelEnum::isValidValue($authLevel))
                        {
                            $this->setAuthenticationLevel($authLevel);
                        }
                        else
                        {
                            Site::$errorHandler->addError(new SimpleError("Could not reauthenticate the user.", ErrorSeverityEnum::Error));
                        }
                    }

                    $stmt->close();
                }
            }
        }
    }

    function deauthenticate()
    {
        session_start();
        session_unset();
        $_SESSION['FBID']     = null;
        $_SESSION['FULLNAME'] = null;
        $_SESSION['EMAIL']    = null;
        $this->setAuthenticationLevel(AuthenticationLevelEnum::Visitor);
        header("Location: index.php");
    }
}
<?php

class LoginPage implements IPage
{
    /**
     * Get the title of the page.
     *
     * @return string
     */
    function getTitle()
    {
        return 'Login Page';
    }

    /**
     * Get the pages short name, as used to define it's URL.
     * i.e. Return 'about' for http://example.com/about
     *
     * @return mixed
     */
    function getShortName()
    {
        return 'login';
    }

    /**
     * Get the page summary.
     *
     * @return string
     */
    function getSummary()
    {
        return '<p>I\'m the login page</p>';
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    function getContent()
    {
        FacebookAuthenticator::startSession();
        $facebookAuth = new FacebookAuthenticator();

        $content = '<div class="login-fragment">';
        $facebook = new \Facebook\Facebook([
            'app_id'                => Config::GetValue('appId', 'facebook'),
            'app_secret'            => Config::GetValue('appSecret', 'facebook'),
            'default_graph_version' => Config::GetValue('graphVersion', 'facebook'),
        ]);
        $helper = $facebook->getRedirectLoginHelper();

        switch (isset($_GET['a']) ? $_GET['a'] : null)
        {
            case 'callback':
                try
                {
                    $accessToken = $helper->getAccessToken();
                }
                catch (Facebook\Exceptions\FacebookResponseException $e)
                {
                    // When Graph returns an error
                    Site::$errorHandler->addError(new SimpleError('Graph returned an error: ' .
                                                                  $e->getMessage(), ErrorSeverityEnum::Error));

                    $content .= 'Could not login.';
                }
                catch (Facebook\Exceptions\FacebookSDKException $e)
                {
                    // When validation fails or other local issues
                    Site::$errorHandler->addError(new SimpleError('Facebook SDK returned an error: ' .
                                                                  $e->getMessage(), ErrorSeverityEnum::Error));

                    $content .= 'Could not login.';
                }

                if (!isset($accessToken))
                {
                    if ($helper->getError())
                    {
                        header('HTTP/1.0 401 Unauthorized');
                        Site::$errorHandler->addError(new SimpleError('SimpleError: ' .
                                                                      $helper->getError(), ErrorSeverityEnum::Error));
                        Site::$errorHandler->addError(new SimpleError('SimpleError Code: ' .
                                                                      $helper->getErrorCode(), ErrorSeverityEnum::Error));
                        Site::$errorHandler->addError(new SimpleError('SimpleError Reason: ' .
                                                                      $helper->getErrorReason(), ErrorSeverityEnum::Error));
                        Site::$errorHandler->addError(new SimpleError('SimpleError Description: ' .
                                                                      $helper->getErrorDescription(), ErrorSeverityEnum::Error));
                    }
                    else
                    {
                        header('HTTP/1.0 400 Bad Request');
                        Site::$errorHandler->addError(new SimpleError('Bad request', ErrorSeverityEnum::Critical));
                    }
                    break;
                }
                else
                {
                    try
                    {
                        // Returns a `Facebook\FacebookResponse` object
                        $response = $facebook->get('/me?fields=id,name,email', $accessToken);
                    }
                    catch (Facebook\Exceptions\FacebookResponseException $e)
                    {
                        Site::$errorHandler->addError(new SimpleError('Graph returned an error: ' .
                                                                      $e->getMessage(), ErrorSeverityEnum::Error));
                        break;
                    }
                    catch (Facebook\Exceptions\FacebookSDKException $e)
                    {
                        Site::$errorHandler->addError(new SimpleError('Facebook SDK returned an error: ' .
                                                                      $e->getMessage(), ErrorSeverityEnum::Error));
                        break;
                    }

                    $user = $response->getGraphUser();

                    $_SESSION['FBID'] = $user->getId();
                    $_SESSION['FULLNAME'] = $user->getName();
                    $_SESSION['EMAIL'] = $user->getField('email');

                    $authenticated = $facebookAuth->authenticate();
                    if (!$authenticated)
                    {
                        Site::$errorHandler->addError(new SimpleError("Could not authenticate the user.", ErrorSeverityEnum::Error));
                    }
                }

                break;
            case 'logout':
                Site::GetAuthenticator()->deauthenticate();
                $content .= '<p>You successfully logged out.</p>';

                break;
        }

        if ($facebookAuth->getAuthenticationLevel() > AuthenticationLevelEnum::Visitor)
        {
            // After login

            ob_start();
            ?>
            <h3>You are currently logged in as <?= $facebookAuth->getFullname() ?>!</h3>
            <p>Return to the <a href="/home">Home Page</a>.</p>
            <div>
                <ul>
                    <li class="large">Image</li>
                    <li><img src="https://graph.facebook.com/<?= $facebookAuth->getFacebookId() ?>/picture?type=large">
                    </li>
                    <li class="large">Facebook ID</li>
                    <li><?= $facebookAuth->getFacebookId() ?></li>
                    <li class="large">Facebook fullname</li>
                    <li><?= $facebookAuth->getFullname() ?></li>
                    <li><a href="/login?a=logout">Logout</a></li>
                </ul>
            </div>
            <?php

            $content .= ob_get_contents();
            ob_end_clean();
        }
        else
        {
            // Before login
            $permissions = ['email']; // Optional permissions

            $loginUrl = $helper->getLoginUrl(Config::GetValue('base', 'urls') . '/login?a=callback', $permissions);

            $content .= '<h3>';
            $content .= '<a href="' . $loginUrl . '">Login with Facebook</a>';
            $content .= '</h3>';
        }

        $content .= '</div>';

        return $content;
    }

    /**
     * Get the style sheets for this page.
     *
     * @return string | array
     */
    function getStyleSheets()
    {
        return null;
    }

    /**
     * Get the scripts for this page.
     *
     * @return string | array
     */
    function getScripts()
    {
        return null;
    }
}
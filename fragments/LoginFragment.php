<?php

class LoginFragment implements IFragment
{
    function getContent()
    {
        FacebookAuthenticator::startSession();
        $facebookAuth = new FacebookAuthenticator();

        $content  = '<div class="login-fragment">';
        $facebook = new \Facebook\Facebook([
                                               'app_id'                => Config::GetValue('appId', 'facebook'),
                                               'app_secret'            => Config::GetValue('appSecret', 'facebook'),
                                               'default_graph_version' => Config::GetValue('graphVersion', 'facebook'),
                                           ]);
        $helper   = $facebook->getRedirectLoginHelper();

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
                    Site::$errorHandler->addError(new SimpleError('Graph returned an error: ' . $e->getMessage(), ErrorSeverityEnum::Error));

                    $content .= 'Could not login.';
                }
                catch (Facebook\Exceptions\FacebookSDKException $e)
                {
                    // When validation fails or other local issues
                    Site::$errorHandler->addError(new SimpleError('Facebook SDK returned an error: ' . $e->getMessage(), ErrorSeverityEnum::Error));

                    $content .= 'Could not login.';
                }

                if (!isset($accessToken))
                {
                    if ($helper->getError())
                    {
                        header('HTTP/1.0 401 Unauthorized');
                        Site::$errorHandler->addError(new SimpleError('SimpleError: ' . $helper->getError(), ErrorSeverityEnum::Error));
                        Site::$errorHandler->addError(new SimpleError('SimpleError Code: ' . $helper->getErrorCode(), ErrorSeverityEnum::Error));
                        Site::$errorHandler->addError(new SimpleError('SimpleError Reason: ' . $helper->getErrorReason(), ErrorSeverityEnum::Error));
                        Site::$errorHandler->addError(new SimpleError('SimpleError Description: ' . $helper->getErrorDescription(), ErrorSeverityEnum::Error));
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
                        Site::$errorHandler->addError(new SimpleError('Graph returned an error: ' . $e->getMessage(), ErrorSeverityEnum::Error));
                        break;
                    }
                    catch (Facebook\Exceptions\FacebookSDKException $e)
                    {
                        Site::$errorHandler->addError(new SimpleError('Facebook SDK returned an error: ' . $e->getMessage(), ErrorSeverityEnum::Error));
                        break;
                    }

                    $user = $response->getGraphUser();

                    $_SESSION['FBID']     = $user->getId();
                    $_SESSION['FULLNAME'] = $user->getName();
                    $_SESSION['EMAIL']    = $user->getField('email');

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
            $content .= '<h3>You are currently logged in as ' . $facebookAuth->getFullname() . '!</h3>';
            $content .= '<p>Return to the <a href="/home">Home Page</a>.</p>';
            $content .= '<div>';
            $content .= '<ul>';
            $content .= '<li class="large">Image</li>';
            $content .= '<li><img src="https://graph.facebook.com/' . $facebookAuth->getFacebookId() . '/picture?type=large"></li>';
            $content .= '<li class="large">Facebook ID</li>';
            $content .= '<li>' . $facebookAuth->getFacebookId() . '</li>';
            $content .= '<li class="large">Facebook fullname</li>';
            $content .= '<li>' . $facebookAuth->getFullname() . '</li>';
            $content .= '<div><a href="/login?a=logout">Logout</a></div>';
            $content .= '</ul></div>';
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
}
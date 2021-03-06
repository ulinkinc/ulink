<?php
/*********************************************************************************
 * Copyright (C) 2012 uLink, Inc. All Rights Reserved.
 *
 * Created On: Mar 22, 2012
 * Description: This class is the parent of all Controller classes.  It contains
 *              functions that will be used throughout the application
 ********************************************************************************/
//App::import('Vendor', 'facebook/facebook/facebook.php');
//require_once("../vendors/facebook/facebook/facebook.php");   <-- 7/30/2012 - Bennie- removing FB for now --too slow

if (inDevMode()) {
    // Dev Plugin library paths
    require_once("../Lib/Twitter/tmhOAuth.php");
    require_once("../Lib/Twitter/tmhUtilities.php");
    require_once("../Lib/Wideimage/WideImage.php");
    require_once("../Lib/S3/S3.php");
} else {
    // PROD Plugin library paths
    require_once("/var/www/vhosts/www/app/Lib/Twitter/tmhOAuth.php");
    require_once("/var/www/vhosts/www/app/Lib/Twitter/tmhUtilities.php");
    require_once("/var/www/vhosts/www/app/Lib/Wideimage/WideImage.php");
    require_once("/var/www/vhosts/www/app/Lib/S3/S3.php");
}

define('_UNIT_MILES', 'm');
define('_UNIT_KILOMETERS', 'k');

// constants for passing $sort to get_zips_in_range()
define('_ZIPS_SORT_BY_DISTANCE_ASC', 1);
define('_ZIPS_SORT_BY_DISTANCE_DESC', 2);
define('_ZIPS_SORT_BY_ZIP_ASC', 3);
define('_ZIPS_SORT_BY_ZIP_DESC', 4);

// constant for miles to kilometers conversion
define('_M2KM_FACTOR', 1.609344);

/**
 * Overriden
 */
class AppController extends Controller {

    var $uses = array('School', 'User', 'Review', 'Article');
    var $helpers = array('Html', 'Js',  'Session', 'Form');
    var $components = array('RequestHandler', 'Security', 'Session', 'Cookie', 'Auth');
    var $last_error = "";            // last error message set by this class
    var $units = _UNIT_MILES;        // miles or kilometers
    var $decimals = 2;               // decimal places for returned distance
    var $facebook;
    var $__fbApiKey = FACEBOOK_APP_ID;
    var $__fbSecret = FACEBOOK_APP_SECRET;
    var $youtube_dev_key = YOUTUBE_DEV_KEY;
    var $youtube_username = YOUTUBE_USERNAME;
    var $youtube_password = YOUTUBE_PASSWORD;
    var $emailCheckExpression = '/^\s*[0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9}\s*$/';
    /**
     * Default constructor, will instantiate a facebook client
     */
    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        // Prevent the 'Undefined index: facebook_config' notice from being thrown.
        $GLOBALS['facebook_config']['debug'] = NULL;
        // Create a Facebook client API object.
        //$this->facebook = new Facebook($this->__fbApiKey, $this->__fbSecret);  <-- 7/30/2012 - Bennie- removing FB for now --too slow
        S3::setAuth(AWS_ACCESS_KEY, AWS_SECRET_KEY);
    }

    /**
     * This function is called before every controller action
     */
    public function beforeFilter() {
        // to set the table to use with authentication
        $this->Auth->userModel = 'User';

        // set the fields to be used for authentication
        $this->Auth->fields = array('username' => 'username', 'password' => 'password');

        //check to see if user is signed in with facebook
        // $this->__checkFBStatus();

        // now check to see if this is admin site authentication
        if (isset($this->request->params['admin'])) {
            if ($this->request->params['admin'] == 1 && $this->request->action != 'admin_forgot_password') {
                if ($this->request->action != 'admin_login' && $this->request->action != 'admin_logout') {
                    if (!$this->Session->read('admin_id')) {
                        $this->redirect(array('controller' => 'admins', 'action' => 'login'));
                    }
                }
            }
        } else { // handle basic uLink authentication

            try {
                // disable automatic redirects
                $this->Auth->autoRedirect = false;

                // set the cookie name to Ulink
                $this->Cookie->name = 'Ulink';

                // if the username as password was posted, set in request on User
                if (isset($_POST['username']) && isset($_POST['password'])) {
                    $this->Auth->logout(); // keep this here for mobile, we need to make sure this is a clean login
                    $this->request->data['User']['username'] = $_POST['username'];
                    $this->request->data['User']['password'] = $_POST['password'];
                    $this->Auth->login();
                }

                // set some variables that can be accessed in all views
                $this->set('loggedInId', $this->Auth->user('id'));
                $this->set('loggedInName', $this->Auth->user('firstname').' '.$this->Auth->user('lastname'));
                $this->set('userSchoolId', $this->Auth->user('school_id'));
                $this->set('loggedInUserName', $this->Auth->user('username'));
                $this->set('loggedInFacebookId', $this->Auth->user('fbid'));
                $this->set('profileImgURL', $this->Auth->user('image_url'));

                // this section will load any reviews from the user
              /*  if ($this->Auth->user('id')) {
                    $this->PermitModel = ClassRegistry::init("School");
                    $Shoolreview = $this->PermitModel->find('all', array('conditions' => array('School.id' => $this->Auth->user('school_id'))));
                    $this->set('Shoolreview', $Shoolreview);
                    $this->PermitModel = ClassRegistry::init("Review");
                    $usertextreview = $this->PermitModel->find('all', array('conditions' => array('Review.school_id' => $this->Auth->user('school_id'), 'Review.type' => 'text', 'Review.user_id' => $this->Auth->user('id'))));

                    foreach ($usertextreview as $userReview) {
                        if ($userReview['Review']['status'] == 0) {
                            $this->set('ApprovalPending', 1);
                        }
                    }

                    $this->set('usertextreview', $usertextreview);
                    $randCaptcha = rand(10000, 20000);
                    $this->set('RandCaptcha', $randCaptcha);
                }*/

                // login user from cookie if they are not logged in
                if (!$this->Auth->user('id')) {
                    $cookie = $this->Cookie->read('User');
                    if ($cookie) {
                        $this->Auth->login($cookie);
                    }
                }
            } catch (Exception $e) {
                $this->log("{AppController#beforeFilter}-An exception was thrown: " . $e->getMessage() ."::STACKTRACE::".$e->getTraceAsString());
            }
        } // end basic uLink auth
    } // beforefilter

    /**
     * This function will generate a random String based on the passed in parameters
     * @param int $minLength
     * @param int $maxLength
     * @param bool $useUpper
     * @param bool $useSpecial
     * @param bool $useNumbers
     * @return string
     */
    function getRandomString($minLength = 20, $maxLength = 20, $useUpper = true, $useSpecial = false, $useNumbers = true) {
        $charset = "abcdefghijklmnopqrstuvwxyz";
        $key = '';

        if ($useUpper) {
            $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        }
        if ($useNumbers) {
            $charset .= "0123456789";
        }
        if ($useSpecial) {
            $charset .= "~@#$%^*()_+-={}|][";
        }
        if ($minLength > $maxLength) {
            $length = mt_rand($maxLength, $minLength);
        } else {
            $length = mt_rand($minLength, $maxLength);
        }

        /*
         *  iterate over the desired length of the string
         *  appending a random char from the charset.
         */
        for ($i = 0; $i < $length; $i++) {
            $key .= $charset[(mt_rand(0, (strlen($charset) - 1)))];
        }
        return $key;
    } // getRandomString

    /**
     * This function will set the error layout before
     * any page loads
     */
    function beforeRender() {
        //to set the not found page
        $this->_setErrorLayout();
    } // beforeRender

    /*
     * This function will set the not found layout and render it
     */
    function _setErrorLayout() {
        if ($this->name == 'CakeError') {
            $this->layout = 'v2_not_found';
        }
    } // _setErrorLayout

    /**
     * Helper function that will read in the information
     * from the image and determine if the orientation
     * is not upright.  If the orientation has been
     * changed from the original, it will then rotate
     * it back to it's original orientation.  This
     * is mainly for devices that tend to cause
     * orientation issues.
     * @param $sourceURL
     * @param $image
     * @return $retVal
     */
    private function maintainImageOrientation($sourceURL, $image) {
        $exif = exif_read_data($sourceURL);
        if(!empty($exif['Orientation'])) {
            switch($exif['Orientation']) {
                case 8:
                    $image = $image->rotate(-90);
                    break;
                case 3:
                    $image = $image->rotate(180);
                    break;
                case 6:
                    $image = $image->rotate(90);
                    break;
            }
        }
        return $image;
    }

    /**
     * This function will search a string for a specific
     * model name.  If that model name is found it will 
     * return the string name of the model.  This is mainly
     * used to build an upload name for the S3 cloud.
     * @param $originalFilePath
     * @param $sliceType
     * @return string $retVal
     */
    private function getS3UploadName($originalFilePath, $sliceType) {
        $retVal = 'img/files/';
        $tokens = explode(DIRECTORY_SEPARATOR, $originalFilePath);
        if(strpos($originalFilePath, 'users') !== FALSE) {
            $retVal .= 'users/';
        } else if(strpos($originalFilePath, 'snaps') !== FALSE) {
            $retVal .= 'snaps/';
        } else if (strpos($originalFilePath, 'events') !== FALSE) {
            $retVal .= 'events/';
        } else if (strpos($originalFilePath, 'schools') !== FALSE) {
            $retVal .= 'schools/';
        }
        if($sliceType == 'thumbs' || $sliceType == 'medium') {
            $retVal .= $sliceType .'/';
        } 
        $retVal .= basename($originalFilePath);
        return $retVal;
    }

    /**
     * This function will compress the image until the desired file
     * size is reached.  This will mainly be used for the web images
     * @param $sourceURL
     * @param $destinationURL
     * @param $quality
     * @return string $destinationURL
     */
    protected function compressOriginalImage($sourceURL, $destinationURL, $quality) {
        // load the image resource
        $image = WideImage::load($sourceURL);
        $info = getimagesize($sourceURL);
        if ($info['mime'] == 'image/png') {
            $image->saveToFile($destinationURL, 9, PNG_NO_FILTER);
        } else {
             /*
              * check to see if the image needs to be rotated to preserve
              * original orientation
              */
            $image = $this->maintainImageOrientation($sourceURL, $image);
            $image->saveToFile($destinationURL, $quality);
        }
	   return $destinationURL;
    }

    /**
     * This function will compress/resize the image until the desired file
     * size is reached.  This will be the "medium" sized image,
     * with these specifications: 400x400, filesize ~25k.
     * @param $sourceURL
     * @param $destinationURL
     * @param $quality
     * @return string $destinationURL
     */
    protected function createMediumImage($sourceURL, $destinationURL, $quality=8) {
        $image = WideImage::load($sourceURL);
        $resizedImage = $image->resize('450', '450', 'inside', 'any');

        $info = getimagesize($sourceURL);
        if ($info['mime'] == 'image/png') {
            $resizedImage->saveToFile($destinationURL, $quality, PNG_NO_FILTER);
        } else {
            $resizedImage->saveToFile($destinationURL, $quality*10);
        } 
       
        // upload the thumb image to s3 if NOT in DEV MODE
        if(!inDevMode()) {
            // get the upload name
            $uploadName = $this->getS3UploadName($destinationURL, 'medium');
            // upload the file to S3
            if (S3::putObjectFile($destinationURL, S3_IMAGE_BUCKET, $uploadName, S3::ACL_PUBLIC_READ)) {
                if(file_exists($destinationURL)) {
                    // delete the medium image on the local server
                    unlink($destinationURL);
                }
            } else {
                 $this->log("{AppController#index} - S3 Failed to upload medium image with URL: ". $uploadName.".  You need to manually upload it to S3.");
            }
        }
	   return $destinationURL;
    }

     /**
     * This function will compress the image until the desired file
     * size is reached.  This will be the "medium" sized image,
     * with these specifications: 400x400, filesize ~25k.
     * @param $sourceURL
     * @param $destinationURL
     * @param $quality
     * @return string $destinationURL
     */
    protected function createThumbImage($sourceURL, $destinationURL, $quality=8) {
        $image = WideImage::load($sourceURL);
        $resizedImage = $image->resize('80', '80', 'inside', 'any');

        $info = getimagesize($sourceURL);
        if ($info['mime'] == 'image/png') {
            $resizedImage->saveToFile($destinationURL, $quality, PNG_NO_FILTER);
        } else {
            $resizedImage->saveToFile($destinationURL, $quality*10);
        }

        // upload the thumb image to s3 if NOT in DEV MODE
        if(!inDevMode()) {
            // get the upload name
            $uploadName = $this->getS3UploadName($destinationURL, 'thumbs');
            // upload the file to S3
            if (S3::putObjectFile($destinationURL, S3_IMAGE_BUCKET, $uploadName, S3::ACL_PUBLIC_READ)) {
                if(file_exists($destinationURL)) {
                    // delete the thumbs image on the local server
                    unlink($destinationURL);
                }
            } else {
                 $this->log("{AppController#index} - S3 Failed to upload thumbs image with URL: ". $uploadName.".  You need to manually upload it to S3.");
            }
            // upload the original image
            // get the upload name
            $uploadName = $this->getS3UploadName($sourceURL, FALSE);
            // upload the file to S3
            if (S3::putObjectFile($sourceURL, S3_IMAGE_BUCKET, $uploadName, S3::ACL_PUBLIC_READ)) {
                if(file_exists($sourceURL)) {
                // delete the original image on the local server
                    unlink($sourceURL);
                }
            } else {
                 $this->log("{AppController#index} - S3 Failed to upload original image with URL: ". $uploadName.".  You need to manually upload it to S3.");
            }
        }
	   return $destinationURL;
    }

    /**
     * This function will delete an image from either the
     * local server (IF DEV MODE) or the S3 server (PROD)
     * @param $serverPath
     * @param $s3Path
     */
    protected function deleteImage($serverPath, $s3Path) {
        if(inDevMode()) {
            if(file_exists($serverPath)) {
                // delete the image from the server if there was one
                unlink($serverPath);
            } else {
                $this->log("{AppController#index} - Failed to delete the image with URL: ". $serverPath.".  You need to manually delete it on your local server.");
            }
        } else { 
            if (!S3::deleteObject(S3_IMAGE_BUCKET, $s3Path)) {
                $this->log("{AppController#index} - S3 Failed to delete the image with URL: ". $s3Path.".  You need to manually delete it on S3.");
            }
        }
    }

    /**
     * This function will resize an image to the
     * desired width.
     * @param $image
     * @param $newWidth
     * @return $resizedImg
     */
    private function resizeImage($image, $newWidth) {
        $width = imagesx( $image );
        $height = imagesy( $image );

        // calculate thumbnail size
        $new_width = $newWidth;
        $new_height = floor( $height * ( $newWidth / $width ) );

        // create a new temporary image
        $resizedImg = imagecreatetruecolor( $new_width, $new_height );

        // copy and resize old image into new image
        imagecopyresized($resizedImg, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

        return $resizedImg;
    }

    /**
     * This function uploads files to the server and will return
     * an array with the success of each file upload
     * @param $folder
     * @param $formdata
     * @param $itemId
     * @return array
     */
    protected function uploadFiles($folder, $formdata, $itemId = null) {
        // setup dir names absolute and relative
        $folder_url = WWW_ROOT . $folder;
        $rel_url = $folder;

        try {
            // create the folder if it does not exist
            if (!is_dir($folder_url)) {
                mkdir($folder_url);
            }

            // if itemId is set create an item folder
            if ($itemId) {
                // set new absolute folder
                $folder_url = WWW_ROOT . $folder . '/' . $itemId;
                // set new relative folder
                $rel_url = $folder . '/' . $itemId;
                // create directory
                if (!is_dir($folder_url)) {
                    mkdir($folder_url);
                }
            }

            // list of permitted file types, this is only images but documents can be added
            $permitted = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png');

            // replace spaces with underscores
            $filename = str_replace(' ', '_', $formdata['name']);
            // assume filetype is false
            $typeOK = false;

            // check filetype is ok
            foreach ($permitted as $type) {
                if ($type == $formdata['type']) {
                    $typeOK = true;
                    break;
                }
            }

            // if file type ok upload the file
            if ($typeOK) {
                // switch based on error code
                switch ((int)$formdata['error']) {
                    case 0:
                        // check filename already exists
                        if (!file_exists($folder_url . '/' . $filename)) {
                            // create full filename
                            $full_url = $folder_url . '/' . $filename;
                            // create medium and thumb urls
                            $medium_url = $folder_url . '/medium/' . $filename;
                            $thumb_url = $folder_url . '/thumbs/' . $filename;
                            $url = $filename;
                            // upload the file
                          //  $success = move_uploaded_file($formdata['tmp_name'], $full_url);
                             $this->compressOriginalImage($formdata['tmp_name'], $full_url, 95);
                             $success = true;
                        } else {
                            // create unique filename and upload file
                            ini_set('date.timezone', 'America/New_York');
                            $now = date('Y-m-d-His');
                            $full_url = $folder_url . '/' . $now . '-' . $filename;
                            $medium_url = $folder_url . '/medium/' . $now . '-' . $filename;
                            $thumb_url = $folder_url . '/thumbs/' . $now . '-' . $filename;
                            $url = $now . '-'.$filename;
                           // $success = move_uploaded_file($formdata['tmp_name'], $full_url);
                            $this->compressOriginalImage($formdata['tmp_name'], $full_url, 95);
                            $success = true;
                        }
                        // if upload was successful
                        if ($success) {
                            $this->createMediumImage($full_url, $medium_url);
                            $this->createThumbImage($full_url, $thumb_url);                            
                            // save the url of the file
                            $result['urls'][] = $url;
                        } else {
                            $result['errors'][] = "There was a problem uploading $filename, please try again or contact help@theulink.com.";
                        }
                        break;
                    case 3:
                        // an error occured
                        $result['errors'][] = "There was a problem uploading $filename, please try again or contact help@theulink.com.";
                        break;
                    default:
                        // an error occured
                        $result['errors'][] = "There was a problem uploading $filename, please try again or contact help@theulink.com.";
                        break;
                }
            } else if ((int)$formdata['error'] == 4) {
                // no file was selected for upload
                $result['nofiles'][] = "No file Selected";
            } else {
                // unacceptable file type
                $result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
            }
        } catch (Exception $e) {
            $this->log("{AppController#uploadFiles} - An exception was thrown: " . $e->getMessage());
        }
        return $result;
    } // uploadFiles

    /**
     * This function will check to see if the user has an auto generated
     * password.
     */
    public function chkAutopass() {
        try {
            // grab the user off the session
            $sessVar = $this->Auth->User();
            if($sessVar != null) {
                $userDetails = ClassRegistry::init('User')->find('first', array('conditions' => array('User.id' => $sessVar['id'])));

                if ($userDetails['User']['autopass'] == 1) {
                    $this->redirect(array('controller' => 'users', 'action' => 'password', '1'));
                }
            }
        } catch (Exception $e) {
            $this->log("{AppController#chkAutopass} - An exception was thrown: " . $e->getMessage());
        }
    } // chkAutopass

    /**
     * This function will create cookies for the admin site.
     * Defaults to 72 hours
     *
     * @param $user_id
     */
    function createCookies($user_id) {
        $this->Cookie->write('admin_id', $user_id, false, '72 hour');
    } // createCookies

    /**
     *  This function is used to get the distance in miles
     *  between two geographical points, converting it as necessary
     *
     *  @param $zip1Lat
     *  @param $zip2Lat
     *  @param $zip1Long
     *  @param $zip2Long
     *  @return int
     */
    function get_distance($zip1Lat, $zip2Lat, $zip1Long, $zip2Long) {
        $retVal = null;
        try {
            // first calculate the mileage
            $miles = AppController::calculate_mileage($$zip1Lat, $zip2Lat, $zip1Long, $zip2Long);

            // if the system is returning km, convert
            if ($this->units == "k") {
                $retVal = round($miles * (1.609344), $this->decimals);
            } else { // else return the miles
                $retVal = round($miles, $this->decimals);
            }
        } catch (Exception $e) {
            $this->log("{AppController#get_distance} - An exception was thrown: " . $e->getMessage());
        }
        return $retVal;
    } // get_distance


    /**
     *  This helper function will calculate the mileage between
     *  to lat/long points.
     *
     *  @param $lat1
     *  @param $lat2
     *  @param $lon1
     *  @param $lon2
     *  @return int
     */
    function calculate_mileage($lat1, $lat2, $lon1, $lon2) {
        $distance = 0;
        try {
            // Convert lattitude/longitude (degrees) to radians for calculations
            $lat1 = deg2rad($lat1);
            $lon1 = deg2rad($lon1);
            $lat2 = deg2rad($lat2);
            $lon2 = deg2rad($lon2);

            // Find the deltas
            $delta_lat = $lat2 - $lat1;
            $delta_lon = $lon2 - $lon1;

            // Find the Great Circle distance
            $temp = pow(sin($delta_lat / 2.0), 2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon / 2.0), 2);
            $distance = 3956 * 2 * atan2(sqrt($temp), sqrt(1 - $temp));

        } catch (Exception $e) {
            $this->log("{AppController#calculate_mileage} - An exception was thrown: " . $e->getMessage());
        }
        return $distance;
    } // calculate_mileage

   /* private function __checkFBStatus() {

        $this->facebook->get_loggedin_user();

        //check to see if a user is not logged in, but a facebook user_id is set
        if (!$this->Auth->User() && $this->facebook->get_loggedin_user()):

            //see if this facebook id is in the User database; if not, create the user using their fbid hashed as their password
            $user_record =
                    $this->User->find('first', array(
                        'conditions' => array('fbid' => $this->facebook->get_loggedin_user()),
                        'fields' => array('User.fbid', 'User.fbpassword', 'User.password', 'User.activation'),
                        'contain' => array()
                    ));

            //create new user
            if (empty($user_record)):

                /* $friends = $facebook->api_client->friends_get();
                  $fields = array('name','pic_square');
                  $info = $facebook->api_client->users_getInfo($friends, $fields);
                  print_r($info);exit; */


           /*     $user_record['User']['fbid'] = $this->facebook->get_loggedin_user();
                $fc = new FacebookRestClient(FACEBOOK_APP_ID, FACEBOOK_APP_SECRET, $facebook->api_client->session_key);

                $fUserData = $fc->users_getInfo($user_record['User']['fbid'], 'last_name,first_name,sex,current_location,email');
                $user_record['User']['fbpassword'] = $this->getRandomString();
                $user_record['User']['password'] = $this->Auth->password($user_record['User']['fbpassword']);
                $user_record['User']['lastname'] = $fUserData[0]['last_name'];
                $user_record['User']['firstname'] = $fUserData[0]['first_name'];
                $user_record['User']['activation'] = '0';
                $user_record['User']['username'] = $fUserData[0]['first_name'] . " " . $fUserData[0]['last_name'];
                // print_r($user_record);exit;
                $this->User->create();
                //print_r($user_record);exit;
                $this->User->save($user_record);
            endif;

            if ($user_record['User']['activation'] == '0') {

                //change the Auth fields
                $this->Auth->fields = array('username' => 'fbid', 'password' => 'password');

                $this->Auth->login($user_record);
                //$this->Session->setFlash('Please enter a valid email id');

                $this->redirect(array('controller' => 'users', 'action' => 'register/' . $user_record['User']['fbid']));
            } else {
                $this->Auth->fields = array('username' => 'fbid', 'password' => 'password');

                $this->Auth->login($user_record);



                // redirect to the previous page, else forward to homepage
                $redirect = $this->Session->read('Auth.redirect');
                if ($redirect) {
                    $this->redirect($redirect);
                } else {
                    $this->redirect(array('controller' => 'pages', 'action' => 'home'));
                }
            }
        endif;
    } */
}
?>

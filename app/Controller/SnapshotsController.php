<?php
/*********************************************************************************
 * Copyright (C) 2012 uLink, Inc. All Rights Reserved.
 *
 * Created On: Oct 17, 2012
 * Description: This controller handles all page
 *              actions for the snapshott module
 ********************************************************************************/
class SnapshotsController extends AppController {

    var $name = 'Snapshots';
    var $uses = array('Snapshot', 'User', 'SnapshotCategory', 'SnapshotComment');
    var $helpers = array('Html', 'Form', 'Js');
    public $components = array('Email', 'Session', 'RequestHandler', 'Security', 'Cookie','Auth');

    /**
     * Function that is called before every action
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index');
        $this->Security->csrfCheck = false;
        $this->Security->validatePost = false;
    } // beforeFilter

    /**
     * Handles the Snapshots splash page load
     */
    public function index() {
        // check to see if the user is logged in, if not, redirect them to login page
        if (!$this->Auth->User()) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
        $this->layout = "v2_ucampus";
        $this->set('title_for_layout', 'Your college everything.');

        try {
            // make sure the user doesn't have a temp password
            $this->chkAutopass();

            // grab the logged in user off the session
            $activeUser = $this->Auth->User();

            // load the categories
            $categories = array();
            $categoryObjs = $this->SnapshotCategory->getAll();
             // use all the categories in the system  for the form
            foreach ($categoryObjs as $category) {
                $categories[$category['SnapshotCategory']['_id']] = $category['SnapshotCategory']['name'];
            }
            $this->set('categories', $categories);
            $this->set('categoryObjs', $categoryObjs);
            // load four random pictures from random categories
            $snapshots = $this->Snapshot->getSplashSnapsBySchoolId($activeUser['school_id']);
            $this->set('splashsnaps', $snapshots);
        } catch (Exception $e) {
            $this->log("{SnapshotsController#index}-An exception was thrown when loading the index page: " . $e->getMessage());
        }
    } // index

     /**
     * This function handles the category action for
     * Snapshots
     * @param $id the category id
     * @param $snapshotId the snapshot that will be featured
     */
    public function category($id=null, $snapshotId=null) {
        // check to see if the user is logged in, if not redirect them to login screen
        if (!$this->Auth->User()) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
        // grab the logged in user off the session
        $activeUser = $this->Auth->User();
        $this->layout = "v2_ucampus";
        $this->set('title_for_layout', 'Your college everything.');

        // grab the category name and set for UI
        $category = $this->SnapshotCategory->findById($id);
        $this->set('category', $category['SnapshotCategory']['name']);

        // return the categorie images for the user's school
        $jsonSnaps = $this->snaps($activeUser["school_id"], $id, 100);
        // set the json object on the UI
        $snaps = json_decode($jsonSnaps);

        // if there was a snapshotId passed in as a param, load the snap and set on UI
        if($snapshotId != null) {
            $snap = json_decode($this->snap($snapshotId));
            $this->set('featuredSnap', $snap->Snapshot);
        } else {
            // grab a random snap from the list and remove it from list (pop)
            //set as featured snap
            if($snaps != null) {
                $index = array_rand($snaps);
                $fSnap = $snaps[$index];
                unset($snaps[$index]);
                $this->set('featuredSnap', $fSnap->Snapshot);
            }
        }
        $this->set('snaps', $snaps);
    }  // category

    /**
     * This function will load the logged in
     * user's snaps
     */
    public function mysnaps() {
        $this->set('title_for_layout', 'My Snaps');
        $this->layout = 'v2';

        try {
            // grab the logged in user off the session
            $activeUser = $this->Auth->User();
            $snaps = $this->Snapshot->find('all', array('fields' => array('caption','imageURL', '_id', 'userId'),'order'=>array('Snapshot.created'=>'DESC'),'conditions' => array('userId' => $activeUser['id'])));
            $this->set('snaps', $snaps);
        }  catch (Exception $e) {
            $this->log("{SnapshotsController#mysnaps} - An exception was thrown:" . $e->getMessage());
        }
    } // mysnaps

    /**
     * This function will handle the deletion of snaps
     * @param $id
     */
    public function deletesnap($id = null) {
        $this->layout = 'v2';
        $this->set('title_for_layout', 'My Snaps');

        try {
            $json = $this->delete_snapshot($id);
            $data = json_decode($json);
            $response = '';
            switch($data->result) {
                case 'false': $response = 'There was a problem deleting the snapshot. Please try again later, or contact help@theulink.com';
                    break;
                case 'true': $response = '<span class="profile-success">' . $data->response . '</span>';
                    break;
                default: $response = 'There was a problem deleting the snapshot. Please try again later, or contact help@theulink.com';
                    break;
            }
            $this->Session->setFlash($response);
        } catch (Exception $e) {
            $this->log("{SnapshotsController#delete} - An exception was thrown: " . $e->getMessage());
        }
        $this->redirect('/snapshots/mysnaps');
    } // delete


    /******************************************************/
    /*          SNAPSHOT API FUNCTIONS                    */
    /******************************************************/
    /**
     * GET API function for returning snap category
     * images in a JSON array
     * @param $schoolId the schoold id
     * @param $categoryId the category id
     * @param $limit the number of snaps to return
     * @return JSON array
     */
    public function snaps($schoolId=null, $categoryId=null, $limit=50) {
        // load the snaps based on the school id and the category id, and limit
        $retVal = $this->Snapshot->getSnapsBySchoolIdAndCategory($schoolId, $categoryId, $limit);
        // return as a JSON object array
        return json_encode($retVal);
    } // snaps

    /**
     * GET API function that will return a Snapshot object
     * based on the passed in id.
     * @param $snapshotId
     * @return JSON Snapshot object
     */
    public function snap($snapshotId=null) {
        $retVal = $this->Snapshot->findById($snapshotId);
        return json_encode($retVal);
    } //snap

    /**
     * POST API Function used for the submit snap component
     * @return string
     */
    public function insert_snap() {
        $this->autoRender = false;
        $this->layout = null;
        Configure:: write('debug', 0);

        /*
         * WEB - grab the logged in user off the session
         * MOBILE - TODO: check auth token
         */
        $activeUser = $this->Auth->User();

        if(!empty($this->data)) {
            $snap = $this->data;
            $snap['Snapshot']['created'] = date("F j, Y, g:i a");
            $snap['Snapshot']['userId'] =  $activeUser['id'];
            $snap['Snapshot']['schoolId'] = $activeUser['school_id'];
            // save the original image to the server
            $fileOK = $this->uploadFiles('img/files/snaps', $this->data['Snapshot']['image']);

            if (array_key_exists('urls', $fileOK)) {
                // save the url in the form data
                $snap['Snapshot']['imageURL'] = $fileOK['urls'][0];
            } else {
                throw new Exception('The snap image did not save correctly to the server.');
            }
            unset($snap['Snapshot']['image']);
            $this->data = $snap;
            try {
                if($this->Snapshot->save($this->data)) {
                    $snap['response'] = "true";
                    echo json_encode($snap);
                    exit;
                } else {
                    $snap['response'] = "false";
                    echo json_encode($snap);
                    exit;
                }
            } catch (Exception $e) {
                $this->log("{SnapshotsController#insert_snap} - An exception was thrown:" . $e->getMessage());
                $snap['response'] = "false";
                echo json_encode($snap);
                exit;
            }
        } else {
          $this->log('{SnapshotsController#insert_snap} - Empty data object.');
          $snap['response'] = "false";
          echo json_encode($snap);
          exit;
        }
    } // insertSnap

    /**
     * POST API function will upload the filtered image
     * to the server and save the new image URL to the
     * parent snapshot.  The original image is then deleted.
     */
    public function apply_snap_filter() {
        $this->autoRender = false;
        $this->layout = null;
        Configure:: write('debug', 0);
        $retVal = array();
        if(!empty($this->data)) {
            try {
                // save the filteered image to the server
                $oldImageURL = $this->data['Snapshot']['imageURL'];
                $imageData = $this->data['Snapshot']['image'];
                $filteredData=substr($imageData, strpos($imageData, ",")+1);
                $unencodedData=base64_decode($filteredData);
                $now = date('Y-m-d-His');
                $fileTokens = explode("/", $oldImageURL);
                $origFileName = $fileTokens[6];
                $tokens = explode("[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]-[0-9][0-9][0-9][0-9][0-9][0-9]-", $fileTokens[6]);
                if(count($tokens)>1) {
                    $fileName = $now . '-' . $tokens[4];
                } else {
                    $fileName = $now . '-' . $tokens[0];
                }
                $fp = fopen( WWW_ROOT . 'img/files/snaps' . '/' . $fileName, 'wb' );
                fwrite( $fp, $unencodedData);
                fclose( $fp );
                // grab the snap with the old image URL
                $snap = $this->Snapshot->find('first', array('conditions' => array('imageURL' => $origFileName)));
                // update the snapshot based on the old image url with the new image url
                $snap['Snapshot']['imageURL'] = $fileName;
                $this->Snapshot->save($snap);
                // delete the old image
                unlink("" . WWW_ROOT . "/img/files/snaps/" . $origFileName);
            } catch (Exception $e) {
                $this->log("{SnapshotsController#apply_snap_filter} - An exception was thrown: " . $e->getMessage());
                $retVal['response'] = "false";
                echo json_encode($retVal);
                exit;
            }
            $retVal['response'] = "true";
            echo json_encode($retVal);
            exit;
        } else {
            $this->log('{SnapshotsController#apply_snap_filter} - Empty data object.');
            $retVal['response'] = "false";
            echo json_encode($retVal);
            exit;
        }
    } // apply_snap_filter

    /**
     * POST API Function used for submitting snap comments
     * @return string
     */
    public function insert_snap_comment() {
        $this->autoRender = false;
        $this->layout = null;
        Configure:: write('debug', 0);

        /*
         * WEB - grab the logged in user off the session
         * MOBILE - TODO: check auth token
         */
        $activeUser = $this->Auth->User();

        if(!empty($this->data)) {
            $comment = $this->data;
            $comment['SnapshotComment']['created'] = date("F j, Y, g:i a");
            $comment['SnapshotComment']['userId'] =  $activeUser['id'];
            $comment["SnapshotComment"]["userImageURL"] = $activeUser['image_url'];
            $comment['SnapshotComment']['created_short'] = date('M j, Y', strtotime($comment['SnapshotComment']['created']));
            $this->data = $comment;
            try {
                if($this->SnapshotComment->save($this->data)) {
                    $comment['response'] = "true";
                    echo json_encode($comment);
                    exit;
                } else {
                    $comment['response'] = "false";
                    echo json_encode($comment);
                    exit;
                }
            } catch (Exception $e) {
                $this->log("{SnapshotsController#insert_snap_comment} - An exception was thrown:" . $e->getMessage());
                $comment['response'] = "false";
                echo json_encode($comment);
                exit;
            }
        } else {
          $this->log('{SnapshotsController#insert_snap_comment} - Empty data object.');
          $comment['response'] = "false";
          echo json_encode($comment);
          exit;
        }
    } // insert_snap_comment

    /**
     * GET API function will handle the deletion of snap comments
     * @param $id
     * @return json object
     */
    public function delete_snap_comment($id = null) {
        $this->autoRender = false;
        $this->layout = null;
        Configure:: write('debug', 0);
        $retVal = array();
        try {
            if($id == NULL) {
                $retVal['result'] = "false";
                $retVal['response'] = 'No id parameter was provided for this request.';
                $this->log("{SnapshotController#delete_snap_comment} - No id parameter was provided for this request.");
                echo json_encode($retVal);
                exit;
            }
            // grab the comment from the db
            $comment = $this->SnapshotComment->read(null, $id);
            /*
            * WEB - grab the logged in user off the session
            * MOBILE - TODO: check auth token
            */
            $activeUser = $this->Auth->User();

            // validate the comment to make sure the user can delete the event
            if($comment['SnapshotComment']['userId'] != $activeUser['id']) {
                $this->log("{SnapshotController#delete_snap_comment} - User id mismatch when deleting snap comment: supplied(". $activeUser['id']."), commentId(". $comment['SnapshotComment']['userId'].")");
                $retVal['result'] = "false";
                $retVal['response'] = 'That was not your comment to delete.';
                echo json_encode($retVal);
                exit;
            }

            if($this->SnapshotComment->delete($id)) {
                $retVal['result'] = "true";
                $retVal['response'] = 'Your snapshot comment was successfully deleted.';
                echo json_encode($retVal);
                exit;
            }
        } catch (Exception $e) {
                $this->log("{SnapshotController#delete_snap_comment} - An exception was thrown: " . $e->getMessage());
                $retVal['result'] = "false";
                $retVal['response'] = 'There was a problem deleting the snapshot comment. Please try again later, or contact help@theulink.com';
                echo json_encode($retVal);
                exit;
        }
    } // delete_snap_comment

    /**
     * GET API function will handle the deletion of snapshots
     * @param $id
     * @return json object
     */
    public function delete_snapshot($id = null) {
        $this->autoRender = false;
        $this->layout = null;
        Configure:: write('debug', 0);
        $retVal = array();

        try {
            if($id == null) {
                $retVal['result'] = 'false';
                $retVal['response'] = 'No id parameter was provided for this request.';
                return json_encode($retVal);
                exit;
            }

            // grab the snapshot from the db
            $snap = $this->Snapshot->read(null, $id);

            /*
             * WEB - grab the logged in user off the session
             * MOBILE - TODO: check auth token
             */
            $activeUser = $this->Auth->User();

            // validate the comment to make sure the user can delete the event
            if($snap['Snapshot']['userId'] != $activeUser['id']) {
                $retVal['result'] = "false";
                $retVal['response'] = 'That was not your snapshot to delete.';
                return json_encode($retVal);
                exit;
            }

            if($this->Snapshot->deleteSnapshot($id)) {
                $retVal['result'] = 'true';
                $retVal['response'] = 'Your snapshot was successfully deleted.';
                return json_encode($retVal);
            }
        } catch (Exception $e) {
                $this->log("{SnapshotController#delete_snapshot} - An exception was thrown: " . $e->getMessage());
                $retVal['result'] = 'false';
                $retVal['response'] = 'There was a problem deleting the snapshot. Please try again later, or contact help@theulink.com';
                return json_encode($retVal);
                exit;
        }
    } // delete_snapshot
}
?>
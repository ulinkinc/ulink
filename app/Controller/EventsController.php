<?php

class EventsController extends AppController {
        
        var $name = 'Events';
		var $components = array('RequestHandler');
		var $helpers = array('Html', 'Form', 'Js');

        public function index() {
				$this->layout = 'v2';
				$this->pageTitle = 'Your college everything.';
				$this->chkAutopass();
                $this->set('events', $this->Event->find('all'));
				
				//$this->Event->create();
				//$data = array("Event" => array('userID' => '10', 'collegeID' => '12', 'eventInfo' => 'This is from Cake'));
				//$this->Event->save($data);
        }

}


?>
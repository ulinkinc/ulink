<?php


echo "<pre>";

echo $this->Form->create('Event', array('action' => 'edit'));
echo $this->Form->input('eventTitle');
echo $this->Form->input('eventInfo', array('type'=>'textarea','rows' => '3','cols'=>'40'));
//echo $this->Form->dateTime('eventDate', 'YMD','24');
echo $this->Form->input('_id', array('type' => 'hidden'));
echo $this->Form->end('Update Event');

/*echo "<b>School: </b>" . $event["Event"]["collegeName"] . "<br/>";
echo "<b>Title:</b> " . $event["Event"]["eventTitle"] . "<br/>";
echo "<b>Date and time:</b> " . $this->Time->nice($this->Time->fromString($event["Event"]["eventDate"])) . "<br/>";
echo "<b>Details</b></br>";
echo $event["Event"]["eventInfo"] . "<br/><br/>";
*/
echo "</pre>";

?>
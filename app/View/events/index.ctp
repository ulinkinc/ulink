<?php


echo "<pre>";
foreach($events as $event)
{
	echo "<b>School: </b>" . $event["Event"]["collegeName"] . "<br/>";
	echo "<b>Title:</b> " . $event["Event"]["eventTitle"] . "<br/>";
	echo "<b>Date and time:</b> " . $this->Time->format('l F jS, Y', $this->Time->fromString($event["Event"]["eventDate"])) . "<br/>";
	echo "<b>Details</b></br>";
	echo $event["Event"]["eventInfo"] . "<br/>";
	echo $this->Html->link('Delete Event', array('controller'=>'events', 'action'=>'delete', $event["Event"]["_id"])) . "<br/>";
	echo $this->Html->link('Edit Event', array('controller'=>'events', 'action'=>'edit', $event["Event"]["_id"])) . "<br/><br/>";
}
echo "</pre>";

?>
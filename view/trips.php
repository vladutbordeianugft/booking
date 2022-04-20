<?php


$trip_obj = new Trip($db);


$get_trips = $trip_obj->getTrips();
$get_trips = $get_trips->fetchAll();


?>

<table cellpadding="5" cellspacing="0" width="100%">
	<thead style="background: #ccc;">
		<tr>
			<td style="text-align:left;">Title</td>
			<td style="text-align:left;">Location</td>
			<td>Start Date</td>
			<td>End Date</td>
			<td style="text-align:right;">Price</td>
			<td>Option</td>
		</tr>
	</thead>
<?php
foreach($get_trips as $trip){
?>
	
	<tr>
		<td style="text-align:left;"><?=htmlspecialchars($trip['title']);?><br><small><?=htmlspecialchars($trip['description']);?></small></td>
		<td style="text-align:left;"><?=htmlspecialchars($trip['location']);?></td>
		<td><?=date('d-m-y H:i',strtotime($trip['start_date']));?></td>
		<td><?=date('d-m-y H:i',strtotime($trip['end_date']));?></td>
		<td style="text-align:right;"><?=$trip['price'];?></td>
		<td>
			<?=($logged_in ? '<input type="button" class="book-trip" data-trip-id="'.$trip['id'].'" value="Book this trip"/>' : '<small>Please login to book this trip</small>');?>
			
		
		</td>
	</tr>


<?php
}
?>

</table>
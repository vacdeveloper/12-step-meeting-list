<?php

//get assets for page
tsml_assets();

get_header();

//parse query string
$search	= isset($_GET['sq']) ? sanitize_text_field($_GET['sq']) : null;
$region	= isset($_GET['r']) && array_key_exists($_GET['r'], $tsml_regions) ? $_GET['r'] : null;
$type	= isset($_GET['t']) && array_key_exists($_GET['t'], $tsml_types[$tsml_program]) ? $_GET['t'] : null;
$time	= isset($_GET['i']) ? sanitize_text_field(strtolower($_GET['i'])) : null;

//need later
$times		= array(
	'morning' => 'Morning',
	'day' => 'Day',
	'evening' => 'Evening',
	'night' => 'Night',
);

if (!isset($_GET['d'])) {
	$day = intval(current_time('w')); //if not specified, day is current day
} elseif ($_GET['d'] == 'any') {
	$day = false;
} else {
	$day = intval($_GET['d']);
}

//labels
$day_default = 'Any Day';
$day_label = ($day === false) ? $day_default : $tsml_days[$day];
$time_default = 'Any Time';
$time_label = $time ? $times[$time] : $time_default;
$region_default = 'Everywhere';
$region_label = ($region && array_key_exists($region, $tsml_regions)) ? $tsml_regions[$region] : $region_default;
$type_default = 'Any Type';
$type_label = ($type && array_key_exists($type, $tsml_types[$tsml_program])) ? $tsml_types[$tsml_program][$type] : $type_default;

//need this later
$locations	= array();

//run query
$meetings	= tsml_get_meetings(compact('search', 'day', 'time', 'region', 'type'));
//dd($meetings);

class Walker_Regions_Dropdown extends Walker_Category {
	function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0) {
		//die('args was ' . var_dump($args));
		$output .= '<li' . ($args['value'] == esc_attr($category->term_id) ? ' class="active"' : '') . '><a href="#" data-id="' . esc_attr($category->term_id) . '">' . esc_attr($category->name) . '</a>';
	}
	function end_el(&$output, $item, $depth=0, $args=array()) {
		$output .= '</li>';
	}
}

?>
<div id="meetings" data-type="list" class="container">
	<div class="row controls hidden-print">
		<div class="col-md-2 col-sm-6">
			<form id="search">
				<div class="input-group">
					<input type="text" name="query" class="form-control" value="<?php echo $search?>" placeholder="Search">
					<span class="input-group-btn">
						<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
					</span>
				</div>
			</form>
		</div>
		<div class="col-md-2 col-sm-6">
			<div class="dropdown" id="day">
				<a data-toggle="dropdown" class="btn btn-default btn-block">
					<span class="selected"><?php echo $day_label?></span>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li<?php if ($day === false) echo ' class="active"'?>><a href="#"><?php echo $day_default?></a></li>
					<li class="divider"></li>
					<?php foreach ($tsml_days as $key=>$value) {?>
					<li<?php if (intval($key) === $day) echo ' class="active"'?>><a href="#" data-id="<?php echo $key?>"><?php echo $value?></a></li>
					<?php }?>
				</ul>
			</div>
		</div>
		<div class="col-md-2 col-sm-6">
			<div class="dropdown" id="time">
				<a data-toggle="dropdown" class="btn btn-default btn-block">
					<span class="selected"><?php echo $time_label?></span>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li<?php if (empty($time)) echo ' class="active"'?>><a href="#"><?php echo $time_default?></a></li>
					<li class="divider"></li>
					<?php foreach ($times as $key=>$value) {?>
					<li<?php if ($key === $time) echo ' class="active"'?>><a href="#" data-id="<?php echo $key?>"><?php echo $value?></a></li>
					<?php }?>
				</ul>
			</div>
		</div>
		<div class="col-md-2 col-sm-6">
			<div class="dropdown" id="region">
				<a data-toggle="dropdown" class="btn btn-default btn-block">
					<span class="selected"><?php echo $region_label?></span>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li<?php if (empty($region)) echo ' class="active"'?>><a href="#"><?php echo $region_default?></a></li>
					<li class="divider"></li>
					<?php wp_list_categories(array(
						'taxonomy' => 'region',
						'hierarchical' => true,
						'orderby' => 'name',
						'title_li' => '',
						'hide_empty' => false,
						'walker' => new Walker_Regions_Dropdown,
						'value' => $region,
						'show_option_none' => '',
					)); ?>
				</ul>
			</div>
		</div>
		<div class="col-md-2 col-sm-6">
			<?php if (count($tsml_types_in_use)) {?>
			<div class="dropdown" id="type">
				<a data-toggle="dropdown" class="btn btn-default btn-block">
					<span class="selected"><?php echo $type_label?></span>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li<?php if (empty($type)) echo ' class="active"'?>><a href="#"><?php echo $type_default?></a></li>
					<li class="divider"></li>
					<?php 
					$types_to_list = array_intersect_key($tsml_types[$tsml_program], array_flip($tsml_types_in_use));
					foreach ($types_to_list as $key=>$thistype) {?>
					<li<?php if ($key == $type) echo ' class="active"'?>><a href="#" data-id="<?php echo $key?>"><?php echo $thistype?></a></li>
					<?php } ?>
				</ul>
			</div>
			<?php }?>
		</div>
		<div class="col-md-2 col-sm-12 visible-md visible-lg visible-xl">
			<div class="btn-group btn-group-justified" id="action">
				<a class="btn btn-default toggle-view active" data-id="list">
					List
				</a>
				<div class="btn-group">
					<a class="btn btn-default toggle-view dropdown-toggle" data-toggle="dropdown" data-id="map">
						Map
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu pull-right" role="menu">
						<li><a href="#fullscreen">Expand</a></li>
						<li><a href="#geolocator">Find Me</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="row results">
		<div class="col-xs-12">
			<div id="alert" class="alert alert-warning<?php if (count($meetings)) {?> hidden<?php }?>">
				No results matched those criteria
			</div>
			
			<div id="map"></div>
			
			<div id="table-wrapper">
				<table class="table table-striped<?php if (!count($meetings)) {?> hidden<?php }?>">
					<thead class="hidden-print">
						<th class="time">Time</th>
						<th class="name">Meeting</th>
						<th class="location">Location</th>
						<th class="address">Address</th>
						<th class="region">Region</th>
					</thead>
					<tbody>
						<?php
						foreach ($meetings as $meeting) {
							$meeting['name'] = htmlentities($meeting['name'], ENT_QUOTES);
							$meeting['location'] = htmlentities($meeting['location'], ENT_QUOTES);
							$meeting['address'] = htmlentities($meeting['address'], ENT_QUOTES);
							$meeting['city'] = htmlentities($meeting['city'], ENT_QUOTES);
							$meeting['region'] = (!empty($meeting['sub_region'])) ? htmlentities($meeting['sub_region'], ENT_QUOTES) : htmlentities($meeting['region'], ENT_QUOTES);
							$meeting['link'] = tsml_link($meeting['url'], tsml_format_name($meeting['name'], $meeting['types']), 'post_type');
							
							if (!isset($locations[$meeting['location_id']])) {
								$locations[$meeting['location_id']] = array(
									'name' => $meeting['location'],
									'latitude' => $meeting['latitude'] - 0,
									'longitude' => $meeting['longitude'] - 0,
									'link' => tsml_link($meeting['location_url'], $meeting['location'], 'post_type'),
									'address' => $meeting['address'],
									'city_state' => $meeting['city'] . ', ' . $meeting['state'],
									'meetings' => array(),
								);
							}
		
							$locations[$meeting['location_id']]['meetings'][] = array(
								'time'=>$meeting['time_formatted'],
								'day'=>$meeting['day'],
								'name'=>$meeting['name'],
								'link'=>$meeting['link'],
								'types'=>$meeting['types'],
							);

							//apply search highlighter if needed
							if ($search) {
								$meeting['name'] = highlight($meeting['name'], $search);
								$meeting['location'] = highlight($meeting['location'], $search);
								$meeting['address'] = highlight($meeting['address'], $search);
							}
							?>
						<tr>
							<td class="time"><?php 
								if (($day === false) && !empty($meeting['time'])) {
									echo tsml_format_day_and_time($meeting['day'], $meeting['time']);
								} else {
									echo '<time>' . $meeting['time_formatted'] . '</time>';
								}
								?></td>
							<td class="name">
								<?php echo $meeting['link']?>
								<div class="visible-print-block"><?php echo $meeting['region']?></div>
							</td>
							<td class="location">
								<?php echo $meeting['location']?>
								<?php if ($meeting['location'] != $meeting['address']) {?>
								<div class="visible-print-block"><?php echo $meeting['address']?></div>
								<?php }?>
							</td>
							<td class="address hidden-print"><?php echo $meeting['address']?></td>
							<td class="region hidden-print"><?php echo $meeting['region']?></td>
						</tr>
						<?php }?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
jQuery(document).ready(function($) {
	var locations = <?php echo json_encode($locations)?>;
	loadMap(locations);
});
</script>

<?php
get_footer();
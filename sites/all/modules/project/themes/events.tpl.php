<?php global $base_url;?>
<?php $color = $project->field_color['und'][0]['value'];?>
<section class="calendar_events">
	<article class="blank_slate">
	  <header>
		<img width="234" height="182" alt="Text Documents" src="https://d1kwjg6ihle0hs.cloudfront.net/assets/blank_slates/blank_slate_icon_events-5bc013653d9bdf5e2e851a0d4a4c301d.png">
	  </header>
	  <div class="blank_slate_body">
		  <h1>Let's add the first event to the project.</h1>
		<h3>Events help you keep track of deadlines, vacations, and important milestones to keep your project humming along.</h3>
	  </div>
	</article>
	<?php $todays = $weeks = $futures = $pasts = array();?>
	<?php $comments = array();?>
	<?php if($events){?>
	<?php 	$todays = array();?>
	<?php 	$today = date('Y-m-d');?>
	<?php 	$result = NULL;?>
	<?php 	if(!$with_clients || !$is_client){?>
	<?php 		$result = db_query("SELECT e.*,a.subject as title,a.description,a.type,a.send_client FROM tbl_events e INNER JOIN tbl_project_attribute a ON e.aid = a.aid WHERE e.calendar = ".$project->nid." AND e.start_date <= '".$today."' AND e.end_date >= '".$today."' AND a.deleted = 0 ORDER BY e.end_date ASC");?>
	<?php 	} else { ?>
	<?php 		$result = db_query("SELECT e.*,a.subject as title,a.description,a.type,a.send_client FROM tbl_events e INNER JOIN tbl_project_attribute a ON e.aid = a.aid WHERE send_client = 1 AND e.calendar = ".$project->nid." AND e.start_date <= '".$today."' AND e.end_date >= '".$today."' AND a.deleted = 0 ORDER BY e.end_date ASC");?>
	<?php 	} ?>
	<?php 	foreach($result as $object){?>
	<?php 		$object->alias = 'discuss/event/'.$object->aid;?>
	<?php 		$todays[] = $object;?>
	<?php 		if(!isset($comments[$object->aid])){?>
	<?php 			$res = db_query("SELECT COUNT(cid) as total FROM tbl_project_comment WHERE aid = ".$object->aid." AND nid = ".$project->nid);?>
	<?php 			foreach($res as $obj) $comments[$object->aid] = $obj->total;?>
	<?php 		} ?>
	<?php 	} ?>
	<?php 	$result = NULL;?>
	<?php 	if(!$with_clients || !$is_client){?>
	<?php 		$result = db_query("SELECT a.aid, a.subject as title, a.description, a.type, l.name, a.tid, a.assign_date, a.assign_to,a.send_client FROM tbl_project_attribute a INNER JOIN tbl_todolist l ON a.tid = l.tid WHERE a.type = 2 AND a.deleted = 0 AND a.completed = 0 AND a.assign_date = '".$today."' AND a.nid = ".$project->nid);?>
	<?php 	} else { ?>
	<?php 		$result = db_query("SELECT a.aid, a.subject as title, a.description, a.type, l.name, a.tid, a.assign_date, a.assign_to,a.send_client FROM tbl_project_attribute a INNER JOIN tbl_todolist l ON a.tid = l.tid WHERE a.send_client = 1 AND a.type = 2 AND a.deleted = 0 AND a.completed = 0 AND a.assign_date = '".$today."' AND a.nid = ".$project->nid);?>
	<?php 	} ?>
	<?php 	foreach($result as $object){?>
	<?php 		list($y,$m,$d) = explode('-',$object->assign_date);?>
	<?php 		$object->start_time = mktime(0,0,0,$m,$d,$y);?>
	<?php 		$object->end_time = mktime(0,0,0,$m,$d,$y);?>
	<?php 		if(!isset($comments[$object->aid])){?>
	<?php 			$res = db_query("SELECT COUNT(cid) as total FROM tbl_project_comment WHERE aid = ".$object->aid." AND nid = ".$project->nid);?>
	<?php 			foreach($res as $obj) $comments[$object->aid] = $obj->total;?>
	<?php 		} ?>
	<?php 		$todays[] = $object;?>
	<?php 	} ?>
	<?php 	$weeks = array();?>
	<?php 	$tomorrow = date('Y-m-d',time()+86400);?>
	<?php 	$w = date('w'); $end_week = date('Y-m-d',time()+(6-$w)*86400);?>
	<?php 	$result = NULL;?>
	<?php 	if(!$with_clients || !$is_client){?>
	<?php 		$result = db_query("SELECT e.*,a.subject as title,a.description,a.type,a.send_client FROM tbl_events e INNER JOIN tbl_project_attribute a ON e.aid = a.aid WHERE  e.calendar = ".$project->nid." AND e.start_date <= '".$end_week."' AND e.end_date >= '".$tomorrow."' AND a.deleted = 0 ORDER BY e.end_date ASC");?>
	<?php 	} else { ?>
	<?php 		$result = db_query("SELECT e.*,a.subject as title,a.description,a.type,a.send_client FROM tbl_events e INNER JOIN tbl_project_attribute a ON e.aid = a.aid WHERE a.send_client = 1 AND e.calendar = ".$project->nid." AND e.start_date <= '".$end_week."' AND e.end_date >= '".$tomorrow."' AND a.deleted = 0 ORDER BY e.end_date ASC");?>
	<?php 	} ?>
	<?php 	foreach($result as $object){?>
	<?php 		list($y,$m,$d) = explode('-',$object->start_date);?>
	<?php 		$object->start_time = mktime(0,0,0,$m,$d,$y);?>
	<?php 		list($y,$m,$d) = explode('-',$object->end_date);?>
	<?php 		$object->end_time = mktime(0,0,0,$m,$d,$y);?>
	<?php 		$object->alias = 'discuss/event/'.$object->aid;?>
	<?php 		$weeks[] = $object;?>
	<?php 		if(!isset($comments[$object->aid])){?>
	<?php 			$res = db_query("SELECT COUNT(cid) as total FROM tbl_project_comment WHERE aid = ".$object->aid." AND nid = ".$project->nid);?>
	<?php 			foreach($res as $obj) $comments[$object->aid] = $obj->total;?>
	<?php 		} ?>
	<?php 	} ?>
	<?php 	$result = NULL;?>
	<?php 	if(!$with_clients || !$is_client){?>
	<?php 		$result = db_query("SELECT a.aid, a.subject as title, a.description, a.type, l.name, a.tid, a.assign_date, a.assign_to,a.send_client FROM tbl_project_attribute a INNER JOIN tbl_todolist l ON a.tid = l.tid WHERE a.type = 2 AND a.deleted = 0 AND a.completed = 0 AND a.assign_date >= '".$tomorrow."' AND a.assign_date <= '".$end_week."' AND a.nid = ".$project->nid);?>
	<?php 	} else {?>
	<?php 		$result = db_query("SELECT a.aid, a.subject as title, a.description, a.type, l.name, a.tid, a.assign_date, a.assign_to,a.send_client FROM tbl_project_attribute a INNER JOIN tbl_todolist l ON a.tid = l.tid WHERE a.send_client = 1 AND a.type = 2 AND a.deleted = 0 AND a.completed = 0 AND a.assign_date >= '".$tomorrow."' AND a.assign_date <= '".$end_week."' AND a.nid = ".$project->nid);?>
	<?php 	} ?>
	<?php 	foreach($result as $object){?>
	<?php 		list($y,$m,$d) = explode('-',$object->assign_date);?>
	<?php 		$object->start_time = mktime(0,0,0,$m,$d,$y);?>
	<?php 		$object->end_time = mktime(0,0,0,$m,$d,$y);?>	
	<?php 		if(!isset($comments[$object->aid])){?>
	<?php 			$res = db_query("SELECT COUNT(cid) as total FROM tbl_project_comment WHERE aid = ".$object->aid." AND nid = ".$project->nid);?>
	<?php 			foreach($res as $obj) $comments[$object->aid] = $obj->total;?>
	<?php 		} ?>
	<?php 		$weeks[] = $object;?>
	<?php 	} ?>
	<?php 	$futures = array();?>
	<?php 	$result = NULL;?>
	<?php 	if(!$with_clients || !$is_client){?>
	<?php 		$result = db_query("SELECT e.*,a.subject as title,a.description,a.type,a.send_client FROM tbl_events e INNER JOIN tbl_project_attribute a ON e.aid = a.aid WHERE e.calendar = ".$project->nid." AND e.end_date > '".$end_week."' AND a.deleted = 0 ORDER BY e.end_date ASC");?>
	<?php 	} else { ?>
	<?php 		$result = db_query("SELECT e.*,a.subject as title,a.description,a.type,a.send_client FROM tbl_events e INNER JOIN tbl_project_attribute a ON e.aid = a.aid WHERE a.send_client = 1 AND e.calendar = ".$project->nid." AND e.end_date > '".$end_week."' AND a.deleted = 0 ORDER BY e.end_date ASC");?>
	<?php 	} ?>
	<?php 	foreach($result as $object){?>
	<?php 		list($y,$m,$d) = explode('-',$object->start_date);?>
	<?php 		$object->start_time = mktime(0,0,0,$m,$d,$y);?>
	<?php 		list($y,$m,$d) = explode('-',$object->end_date);?>
	<?php 		$object->end_time = mktime(0,0,0,$m,$d,$y);?>
	<?php 		$object->alias = 'discuss/event/'.$object->aid;?>
	<?php 		$futures[] = $object;?>
	<?php 		if(!isset($comments[$object->aid])){?>
	<?php 			$res = db_query("SELECT COUNT(cid) as total FROM tbl_project_comment WHERE aid = ".$object->aid." AND nid = ".$project->nid);?>
	<?php 			foreach($res as $obj) $comments[$object->aid] = $obj->total;?>
	<?php 		} ?>
	<?php 	} ?>
	<?php 	$result = NULL;?>
	<?php 	if(!$with_clients || !$is_client){?>
	<?php 		$result = db_query("SELECT a.aid, a.subject as title, a.description, a.type, l.name, a.tid, a.assign_date, a.assign_to,a.send_client FROM tbl_project_attribute a INNER JOIN tbl_todolist l ON a.tid = l.tid WHERE a.type = 2 AND a.deleted = 0 AND a.completed = 0 AND a.assign_date > '".$end_week."' AND a.nid = ".$project->nid);?>
	<?php 	} else { ?>
	<?php 		$result = db_query("SELECT a.aid, a.subject as title, a.description, a.type, l.name, a.tid, a.assign_date, a.assign_to,a.send_client FROM tbl_project_attribute a INNER JOIN tbl_todolist l ON a.tid = l.tid WHERE a.send_client = 1 AND a.type = 2 AND a.deleted = 0 AND a.completed = 0 AND a.assign_date > '".$end_week."' AND a.nid = ".$project->nid);?>
	<?php 	} ?>
	<?php 	foreach($result as $object){?>
	<?php 		list($y,$m,$d) = explode('-',$object->assign_date);?>
	<?php 		$object->start_time = mktime(0,0,0,$m,$d,$y);?>
	<?php 		$object->end_time = mktime(0,0,0,$m,$d,$y);?>
	<?php 		if(!isset($comments[$object->aid])){?>
	<?php 			$res = db_query("SELECT COUNT(cid) as total FROM tbl_project_comment WHERE aid = ".$object->aid." AND nid = ".$project->nid);?>
	<?php 			foreach($res as $obj) $comments[$object->aid] = $obj->total;?>
	<?php 		} ?>
	<?php 		$futures[] = $object;?>
	<?php 	} ?>
	<?php 	$pasts = array();?>
	<?php 	$result = NULL;?>
	<?php 	if(!$with_clients || !$is_client){?>
	<?php 		$result = db_query("SELECT e.*,a.subject as title,a.description,a.type,a.send_client FROM tbl_events e INNER JOIN tbl_project_attribute a ON e.aid = a.aid WHERE e.calendar = ".$project->nid." AND e.start_date < '".$today."' AND a.deleted = 0 ORDER BY e.start_date ASC");?>
	<?php 	} else { ?>
	<?php 		$result = db_query("SELECT e.*,a.subject as title,a.description,a.type,a.send_client FROM tbl_events e INNER JOIN tbl_project_attribute a ON e.aid = a.aid WHERE a.send_client = 1 AND e.calendar = ".$project->nid." AND e.start_date < '".$today."' AND a.deleted = 0 ORDER BY e.start_date ASC");?>
	<?php 	} ?>
	<?php 	foreach($result as $object){?>
	<?php 		list($y,$m,$d) = explode('-',$object->start_date);?>
	<?php 		$object->start_time = mktime(0,0,0,$m,$d,$y);?>
	<?php 		list($y,$m,$d) = explode('-',$object->end_date);?>
	<?php 		$object->end_time = mktime(0,0,0,$m,$d,$y);?>
	<?php 		$object->alias = 'discuss/event/'.$object->aid;?>
	<?php 		$pasts[] = $object;?>
	<?php 		if(!isset($comments[$object->aid])){?>
	<?php 			$res = db_query("SELECT COUNT(cid) as total FROM tbl_project_comment WHERE aid = ".$object->aid." AND nid = ".$project->nid);?>
	<?php 			foreach($res as $obj) $comments[$object->aid] = $obj->total;?>
	<?php 		} ?>
	<?php 	} ?>
	<?php 	$result = NULL;?>
	<?php 	if(!$with_clients || !$is_client){?>
	<?php 		$result = db_query("SELECT a.aid, a.subject as title, a.description, a.type,l.name, a.tid, a.assign_date, a.assign_to,a.send_client FROM tbl_project_attribute a INNER JOIN tbl_todolist l ON a.tid = l.tid WHERE a.type = 2 AND a.deleted = 0 AND a.completed = 0 AND a.assign_date < '".$today."' AND a.nid = ".$project->nid);?>
	<?php 	} else { ?>
	<?php 		$result = db_query("SELECT a.aid, a.subject as title, a.description, a.type,l.name, a.tid, a.assign_date, a.assign_to,a.send_client FROM tbl_project_attribute a INNER JOIN tbl_todolist l ON a.tid = l.tid WHERE a.send_client = 1 AND a.type = 2 AND a.deleted = 0 AND a.completed = 0 AND a.assign_date < '".$today."' AND a.nid = ".$project->nid);?>
	<?php 	} ?>
	<?php 	foreach($result as $object){?>
	<?php 		list($y,$m,$d) = explode('-',$object->assign_date);?>
	<?php 		$object->start_time = mktime(0,0,0,$m,$d,$y);?>
	<?php 		$object->end_time = mktime(0,0,0,$m,$d,$y);?>
	<?php 		if(!isset($comments[$object->aid])){?>
	<?php 			$res = db_query("SELECT COUNT(cid) as total FROM tbl_project_comment WHERE aid = ".$object->aid." AND nid = ".$project->nid);?>
	<?php 			foreach($res as $obj) $comments[$object->aid] = $obj->total;?>
	<?php 		} ?>
	<?php 		$pasts[] = $object;?>
	<?php 	} ?>
	<?php if(empty($todays) && empty($weeks) && empty($futures)){?>
  	<section class="upcoming_events upcoming_events_blank_slate">
		<h2>Upcoming events</h2>
		<div class="notice">
	  		<p>Nothing’s scheduled right now.</p>
		</div>
 	</section>
 	<?php } else {?>
 	<div class="upcoming_events">
		<h2>Upcoming events</h2>
	</div>
 	<?php } ?>
 	<?php if(!empty($todays)){?>
	<section class="group today upcoming_events">
		<header>
			<h2>Today</h2>
		</header>
		<article class="calendar_event">
		  	<header>
			  	<time datetime="<?php print date('Y-m-d');?>">
					<span class="day_of_week"><?php print date('l');?></span><span class="month"><?php print date('F');?></span>
					<span style="background-color: #<?php print $color;?>" class="day"><?php print date('j');?></span>
			  	</time>
		  	</header>
		  	<?php $have_events = 0; $have_todos = 0;?>
		  	<?php if($todays[0]->type == 5) $have_events = 1; else if($todays[0]->type == 2) $have_todos = 1;?>
		  	<?php if($have_events){?>
			<ul>
			<?php foreach($todays as $event){?>
			<?php 	if($event->type == 5){?>
				<li id="CalendarEvent_<?php print $event->aid;?>">
					<?php if(!empty($event->at_time) && $event->start_date == $event->end_date){?>
					<time style="color: #<?php print $color;?>" datetime="<?php print $event->at_time;?>"><?php print change_time($event->at_time);?></time>
					<?php }?>
			  		<a href="<?php print $base_url.'/'.$event->alias;?>"><?php print $event->title;?></a> 
			  		<?php if($comments[$event->aid]){?>
					<span class="pill comments"><a href="<?php print $base_url.'/'.$event->alias;?>"><?php print $comments[$event->aid];?> comment</a></span>
					<?php } ?>
					<?php if($with_clients && !$is_client && $event->send_client == 0){?>
					<span class="hidden_from_client">The client can’t see this event</span>
					<?php } ?>
				</li>
			<?php 	} else { $have_todos++;} ?>
			<?php } ?>
			</ul>
			<?php } ?>
			<?php if($have_todos){ ?>
			<?php 	$number = $have_events?$have_todos:count($todays);?>
			<ul class="todos">
				<li>
					<h5><?php print $number?> To-do<?php if($number > 1) print 's';?></h5>
				</li>
			<?php 	foreach($todays as $todo){ ?>
			<?php 		if($todo->type == 2){?>
				<li>
					<form data-behavior="edit_calendar_todo has_hover_content" data-remote="true" method="PUT" action="" class="calendar_todo_editor_form">
					  <div>
						<span class="wrapper ">
						  <input type="checkbox" onclick="toggle_todo(this,'.$todo->aid.')" value="1">
						  <span class="content">
							<a title="List: <?php print $todo->name;?>" href="<?php print $base_url.'/todos/'.$todo->aid;?>"><?php print $todo->title;?></a>
							<?php if($comments[$todo->aid]){?>
							<span class="pill comments"><a href="<?php print $base_url;?>/todos/<?php print $todo->aid;?>"><?php print $comments[$todo->aid];?> comments</a></span>
							<?php } ?>
							<?php if($with_clients && !$is_client && $todo->send_client == 0){?>
							<span class="hidden_from_client">The client can’t see this event</span>
							<?php } ?>
							<span data-behavior="expandable expand_exclusively load_assignee_options " id="pill-<?php print $todo->aid?>" class="pill has_balloon ">
							  <a onclick="edit_todo(this,event,<?php print $todo->aid;?>)" href="#">
								<span data-behavior="assignee_name">
								<?php 
									if($todo->assign_to){
										$member = user_load($todo->assign_to);
										$assign = '';
										if(isset($member->field_profile_fname['und'][0]['value']))
											$assign .= $member->field_profile_fname['und'][0]['value'].' ';
										if(isset($member->field_profile_lname['und'][0]['value']))
											$assign .= $member->field_profile_lname['und'][0]['value'];
										$assign = trim($assign);
										if(empty($assign))
											$assign = $member->mail;
									}
									else $assign = 'Unassigned';
								?>
								  <?php print $assign;?>
								</span>
							  </a>
							  <span class="balloon right_side expanded_content">
								<span class="arrow"></span>
								<span class="arrow"></span>
								<label>
					  				<b>Assign this to-do to:</b>
					  				<select data-assignee-code="" data-project-id="<?php print $project->nid;?>" data-behavior="assignee_options" name="todo[assignee_code]">
										<option value="0">Unassigned</option>
							<?php foreach($users as $member){		?>
										<option value="<?php print $member->uid?>"<?php print ($todo->assign_to == $member->uid?' selected=""':'')?>><?php print $member->full_name?></option>
							<?php } ?>
					  				</select>
								</label>
								<small><p>The person you select will be notified by email</p></small>
								<label>
					  				<b>Set the due date:</b>
					  				<hr>
									<input type="hidden" data-behavior="alt_date_field" value="2013-12-20" id="alt_date_todo" name="todo[due_on]">
									<div class="notranslate datepicker" data-behavior="date_picker"></div>
								</label>
								<footer><a class="no_date" onclick="no_due_date(this,event,<?php print $todo->aid?>)" href="#">No due date</a></footer>
							  </span>
							</span>
						  </span>
						</span>
					  </div>
					</form>
				</li>
			<?php 		}?>
			<?php 	} ?>
			</ul>
			<?php } ?>
		</article>
	</section>
	<?php } ?>
	<?php $time = mktime(0,0,0,date('m'),date('d'),date('Y'));?>
	<?php if(!empty($weeks)){?>
	<section class="calendar_events group this_week upcoming_events">
	  <header>
	    <h2>The rest of the week</h2>
	  </header>
  	  
  	  <?php $tomorrow = $time + 86400;?>
  	  <?php $w = date('w');?>
  	  <?php $end_week = $time + (6-$w)*86400;?>
  	  <?php for($i = $tomorrow; $i<=$end_week; $i+= 86400){
  	  	$output = '
		<article class="calendar_event">
		  <header>
			  <time datetime="'.date('Y-m-d',$i).'">
				<span class="day_of_week">'.date('l',$i).'</span><span class="month">'.date('F',$i).'</span>
				<span style="background-color: #'.$color.'" class="day">'.date('j',$i).'</span>
			  </time>
		  </header>';
		  $have_todos = 0;
		  $ul_output = '
			<ul>';
			$count = 0 ;
			foreach($weeks as $event){
				if($event->type == 5){
					if($event->start_time <= $i && $i <= $event->end_time){
						$count++;
						$ul_output .= '
				<li id="CalendarEvent_'.$event->aid.'">';
						if(!empty($event->at_time) && $event->start_date == $event->end_date){
							$ul_output .= '
					<time style="color: #'.$color.'" datetime="'.$event->at_time.'">'.change_time($event->at_time).'</time>';
						}
						$ul_output .= '
			  		<a href="'.$base_url.'/'.$event->alias.'">'.$event->title.'</a>';
			  			if($comments[$event->aid]){
			  				$ul_output .= '
					<span class="pill comments"><a href="'.$base_url.'/'.$event->alias.'">'.$comments[$event->aid].' comment</a></span>';
						}
						if($with_clients && !$is_client && $event->send_client == 0){
							$ul_output .= '
					<span class="hidden_from_client">The client can’t see this event</span>';
						}
						$ul_output .= '
				</li>';
					}
				}
				else if($event->start_time == $i)
					$have_todos++;
			}
			$ul_output .= '		
			</ul>';
			if($count == 0)
				$ul_output = '';
			if($have_todos){
				$ul_output .= '
				<ul class="todos">
					<li><h5>'.$have_todos.' To-do'.($have_todos>1?'s':'').'</h5></li>';
				foreach($weeks as $todo){
					if($todo->type == 2 && $todo->start_time == $i){
						if($todo->assign_to){
							$member = user_load($todo->assign_to);
							$assign = '';
							if(isset($member->field_profile_fname['und'][0]['value']))
								$assign .= $member->field_profile_fname['und'][0]['value'].' ';
							if(isset($member->field_profile_lname['und'][0]['value']))
								$assign .= $member->field_profile_lname['und'][0]['value'];
							$assign = trim($assign);
							if(empty($assign))
								$assign = $member->mail;
						}
						else $assign = 'Unassigned';
						$ul_output .= '
					<li>
						<form data-behavior="edit_calendar_todo has_hover_content" data-remote="true" method="PUT" action="" class="calendar_todo_editor_form">
						  <div>
							<span class="wrapper ">
							  <input type="checkbox"  onclick="toggle_todo(this,'.$todo->aid.')" value="1">

							  <span class="content">
								<a title="List: '.$todo->name.'" href="'.$base_url.'/todos/'.$todo->aid.'">'.$todo->title.'</a>';
						if($comments[$todo->aid]){
							$ul_output .= '
								<span class="pill comments"><a href="'.$base_url.'/todos/'.$todo->aid.'">'.$comments[$todo->aid].' comments</a></span>';
						}
						if($with_clients && !$is_client && $todo->send_client == 0){
							$ul_output .= '
								<span class="hidden_from_client">The client can’t see this event</span>';
						}
						$ul_output .= '
								<span data-behavior="expandable expand_exclusively load_assignee_options " id="pill-'.$todo->aid.'" class="pill has_balloon ">
								  <a onclick="edit_todo(this,event,'.$todo->aid.')" href="#">
									<span data-behavior="assignee_name">'.$assign.'</span>
								  </a>
								  <span class="balloon right_side expanded_content">
									<span class="arrow"></span>
									<span class="arrow"></span>
									<label>
						  				<b>Assign this to-do to:</b>
						  				<select data-assignee-code="" data-project-id="4321849" data-behavior="assignee_options" name="todo[assignee_code]">
											<option value="0">Unassigned</option>';
							foreach($users as $member){		
								$ul_output .= '	<option value="'.$member->uid.'"'.($todo->assign_to == $member->uid?' selected=""':'').'>'.$member->full_name.'</option>';
							}
							$ul_output .= '
						  				</select>
									</label>
									<small><p>The person you select will be notified by email</p></small>
									<label>
						  				<b>Set the due date:</b>
						  				<hr>
										<input type="hidden" data-behavior="alt_date_field" value="2013-12-20" id="alt_date_todo" name="todo[due_on]">
										<div class="notranslate datepicker" data-behavior="date_picker"></div>
									</label>
									<footer><a class="no_date" onclick="no_due_date(this,event,'.$todo->aid.')" href="#">No due date</a></footer>
								  </span>
								</span>
							  </span>
							</span>
						  </div>
						</form>
					</li>';
					}
				}
				$ul_output .= '
				</ul>';
				$count = $have_todos;
			}
			$output .= $ul_output.'
		</article>';
		if($count == 0)
			$output = '';
		print $output;
		} ?>
	</section>
	<?php } ?>
	
	<?php if(!empty($futures)){
		$min_time = $futures[0]->start_time;
		$max_time = $futures[count($futures)-1]->end_time;
		foreach($futures as $event){
			$start_time = $event->start_time;
			$end_time = $event->end_time;
			if($start_time < $min_time)
				$min_time = $start_time;
			if($end_time > $max_time)
				$max_time = $end_time;
		}
		if($min_time <= $end_week )
			$min_time = $end_week + 86400;
		$m_output = '';
		$month = '';
		$m_count = 0;
		for($i = $min_time; $i <= $max_time; $i+=86400){
			$i_month = date('F',$i);
			if($month != $i_month){
				$m_output .= '
	</section>';
				if($m_count == 0)
					$m_output = '';
				print $m_output;
				$month = $i_month;
				$m_count = 0;
				$m_output = '
	<section class="calendar_events group month upcoming_events">
		<header><h2>'.$month.'</h2></header>
				';
			}
				$d_output = '
		<article class="calendar_event">
		  	<header>
			  <time datetime="'.date('Y-m-d',$i).'">
				<span class="day_of_week">'.date('l',$i).'</span><span class="month">'.$month.'</span>
				<span style="background-color: #'.$color.'" class="day">'.date('j',$i).'</span>
			  </time>
		  	</header>';
		  		$have_todos = 0;
		  		$ul_output = '
			<ul>';
				$d_count = 0;
				foreach($futures as $event){
					if($event->type == 5){
						if($event->start_time <= $i && $event->end_time >= $i){
							$d_count++;
							$m_count++;
							$ul_output .= '
					<li id="CalendarEvent_'.$event->aid.'">';
							if(!empty($event->at_time) && $event->start_date == $event->end_date){
								$ul_output .= '
						<time style="color: #'.$color.'" datetime="'.$event->at_time.'">'.change_time($event->at_time).'</time>';
							}
							$ul_output .= '
				  		<a href="'.$base_url.'/'.$event->alias.'">'.$event->title.'</a>';
				  			if($comments[$event->aid]){
				  				$ul_output .= '
						<span class="pill comments"><a href="'.$base_url.'/'.$event->alias.'">'.$comments[$event->aid].' comment</a></span>';
							}
							if($with_clients && !$is_client && $event->send_client == 0){
								$ul_output .= '
						<span class="hidden_from_client">The client can’t see this event</span>';
							}
							$ul_output .= '
					</li>';
						}
					}
					else if($event->type == 2 && $event->start_time == $i){
						$have_todos++;
					}
				}
				$ul_output .= '
			</ul>';
				if($d_count == 0)
					$ul_output = '';
				if($have_todos){
					
					$d_count = $have_todos;
					$m_count += $have_todos;
					$ul_output .= '
			<ul class="todos">
				<li><h5>'.$have_todos.' To-do'.($have_todos>1?'s':'').'</h5></li>';
					foreach($futures as $todo){
						if($todo->type == 2 && $todo->start_time == $i){
							if($todo->assign_to){
								$member = user_load($todo->assign_to);
								$assign = '';
								if(isset($member->field_profile_fname['und'][0]['value']))
									$assign .= $member->field_profile_fname['und'][0]['value'].' ';
								if(isset($member->field_profile_lname['und'][0]['value']))
									$assign .= $member->field_profile_lname['und'][0]['value'];
								$assign = trim($assign);
								if(empty($assign))
									$assign = $member->mail;
							}
							else $assign = 'Unassigned';
							$ul_output .= '
						<li>
							<form data-behavior="edit_calendar_todo has_hover_content" data-remote="true" method="PUT" action="" class="calendar_todo_editor_form">
							  <div>
								<span class="wrapper ">
								  <input type="checkbox"  onclick="toggle_todo(this,'.$todo->aid.')" value="1">

								  <span class="content">
									<a title="List: '.$todo->name.'" href="'.$base_url.'/todos/'.$todo->aid.'">'.$todo->title.'</a>';
							if($comments[$todo->aid]){
								$ul_output .= '
									<span class="pill comments"><a href="'.$base_url.'/todos/'.$todo->aid.'">'.$comments[$todo->aid].' comments</a></span>';
							}
							if($with_clients && !$is_client && $todo->send_client == 0){
								$ul_output .= '
									<span class="hidden_from_client">The client can’t see this event</span>';
							}
							$ul_output .= '
									<span data-behavior="expandable expand_exclusively load_assignee_options " id="pill-'.$todo->aid.'" class="pill has_balloon ">
									  <a onclick="edit_todo(this,event,'.$todo->aid.')" href="#">
										<span data-behavior="assignee_name">'.$assign.'</span>
									  </a>
									  <span class="balloon right_side expanded_content">
										<span class="arrow"></span>
										<span class="arrow"></span>
										<label>
							  				<b>Assign this to-do to:</b>
							  				<select data-assignee-code="" data-project-id="'.$project->nid.'" data-behavior="assignee_options" name="todo[assignee_code]">
												<option value="0">Unassigned</option>';
								foreach($users as $member){		
									$ul_output .= '	<option value="'.$member->uid.'"'.($todo->assign_to == $member->uid?' selected=""':'').'>'.$member->fname.'</option>';
								}
								$ul_output .= '
							  				</select>
										</label>
										<small><p>The person you select will be notified by email</p></small>
										<label>
							  				<b>Set the due date:</b>
							  				<hr>
											<input type="hidden" data-behavior="alt_date_field" value="2013-12-20" id="alt_date_todo" name="todo[due_on]">
											<div class="notranslate datepicker" data-behavior="date_picker"></div>
										</label>
										<footer><a class="no_date" onclick="no_due_date(this,event,'.$todo->aid.')" href="#">No due date</a></footer>
									  </span>
									</span>
								  </span>
								</span>
							  </div>
							</form>
						</li>';
						}
					}
					$ul_output .= '
			</ul>';
				}
				$d_output .= $ul_output.'
		</article>';
				if($d_count == 0)
					$d_output = '';
				$m_output .= $d_output;
		}
		$m_output .= '</section>';
		if($m_count == 0)
			$m_output = '';
		print $m_output;

	} ?>
	<?php if(!empty($pasts)){?>
	<div class="past_events"><h2>Past events</h2></div>
	<?php 
		$min_time = $pasts[0]->start_time;
		$max_time = $pasts[0]->end_time;
		foreach($pasts as $event){
			$start_time = $event->start_time;
			$end_time = $event->end_time;
			if($min_time > $start_time)
				$min_time = $start_time;
			if($max_time < $end_time)
				$max_time = $end_time;
		}
		if($max_time >= $time)
			$max_time = $time - 86400;
		$m_output = '';
		$month = '';
		$m_count = 0;
		for($i = $max_time; $i >= $min_time; $i-=86400){
			$i_month = date('F',$i);
			if($month != $i_month){
				$m_output .= '
	</section>';
				if($m_count == 0)
					$m_output = '';
				print $m_output;
				$month = $i_month;
				$m_count = 0;
				$m_output = '
	<section class="calendar_events group month past_events">
		<header><h2>'.$month.'</h2></header>
				';
			}
				$d_output = '
		<article class="calendar_event">
		  	<header>
			  <time datetime="'.date('Y-m-d',$i).'">
				<span class="day_of_week">'.date('l',$i).'</span><span class="month">'.$month.'</span>
				<span style="background-color: #'.$color.'" class="day">'.date('j',$i).'</span>
			  </time>
		  	</header>';
		  		$have_todos = 0;
		  		$ul_output = '
			<ul>';
				$d_count = 0;
				foreach($pasts as $event){
					if($event->type == 5){
						if($event->start_time <= $i && $event->end_time >= $i){
						$d_count++;
						$m_count++;
						$ul_output .= '
				<li id="CalendarEvent_'.$event->aid.'">';
						if(!empty($event->at_time) && $event->start_date == $event->end_date){
							$ul_output .= '
					<time style="color: #'.$color.'" datetime="'.$event->at_time.'">'.change_time($event->at_time).'</time>';
						}
						$ul_output .= '
			  		<a href="'.$base_url.'/'.$event->alias.'">'.$event->title.'</a>';
			  			if($comments[$event->aid]){
			  				$ul_output .= '
					<span class="pill comments"><a href="'.$base_url.'/'.$event->alias.'">'.$comments[$event->aid].' comment</a></span>';
						}
						if($with_clients && !$is_client && $event->send_client == 0){
							$ul_output .= '
					<span class="hidden_from_client">The client can’t see this event</span>';
						}
						$ul_output .= '
				</li>';
						}
					}
					else if($event->type == 2 && $event->start_time == $i)
						$have_todos++;
				}
				$ul_output .= '
			</ul>';
			if($d_count == 0)
				$ul_output = '';
			if($have_todos){
				$d_count = $have_todos;
				$m_count += $have_todos;
				$ul_output .= '
			<ul class="todos">
				<li><h5>'.$have_todos.' To-do'.($have_todos>1?'s':'').'</h5></li>';
					foreach($pasts as $todo){
						if($todo->type == 2 && $todo->start_time == $i){
							if($todo->assign_to){
								$member = user_load($todo->assign_to);
								$assign = '';
								if(isset($member->field_profile_fname['und'][0]['value']))
									$assign .= $member->field_profile_fname['und'][0]['value'].' ';
								if(isset($member->field_profile_lname['und'][0]['value']))
									$assign .= $member->field_profile_lname['und'][0]['value'];
								$assign = trim($assign);
								if(empty($assign))
									$assign = $member->mail;
							}
							else $assign = 'Unassigned';
							$ul_output .= '
						<li>
							<form data-behavior="edit_calendar_todo has_hover_content" data-remote="true" method="PUT" action="" class="calendar_todo_editor_form">
							  <div>
								<span class="wrapper ">
								  <input type="checkbox" onclick="toggle_todo(this,'.$todo->aid.')" value="1">

								  <span class="content">
									<a title="List: '.$todo->name.'" href="'.$base_url.'/todos/'.$todo->aid.'">'.$todo->title.'</a>';
							if($comments[$todo->aid]){
								$ul_output .= '
									<span class="pill comments"><a href="'.$base_url.'/todos/'.$todo->aid.'">'.$comments[$todo->aid].' comments</a></span>';
							}
							if($with_clients && !$is_client && $todo->send_client == 0){
								$ul_output .= '
									<span class="hidden_from_client">The client can’t see this event</span>';
							}
							$ul_output .= '
									<span data-behavior="expandable expand_exclusively load_assignee_options " id="pill-'.$todo->aid.'" class="pill has_balloon ">
									  <a onclick="edit_todo(this,event,'.$todo->aid.')" href="#">
										<span data-behavior="assignee_name">'.$assign.'</span>
									  </a>
									  <span class="balloon right_side expanded_content">
										<span class="arrow"></span>
										<span class="arrow"></span>
										<label>
							  				<b>Assign this to-do to:</b>
							  				<select data-assignee-code="" data-project-id="'.$project->nid.'" data-behavior="assignee_options" name="todo[assignee_code]">
												<option value="0">Unassigned</option>';
								foreach($users as $member){		
									$ul_output .= '	<option value="'.$member->uid.'"'.($todo->assign_to == $member->uid?' selected=""':'').'>'.$member->full_name.'</option>';
								}
								$ul_output .= '
							  				</select>
										</label>
										<small><p>The person you select will be notified by email</p></small>
										<label>
							  				<b>Set the due date:</b>
							  				<hr>
											<input type="hidden" data-behavior="alt_date_field" value="2013-12-20" id="alt_date_todo" name="todo[due_on]">
											<div class="notranslate datepicker" data-behavior="date_picker"></div>
										</label>
										<footer><a class="no_date" onclick="no_due_date(this,event,'.$todo->aid.')" href="#">No due date</a></footer>
									  </span>
									</span>
								  </span>
								</span>
							  </div>
							</form>
						</li>';
						}
					}
					$ul_output .= '
			</ul>';
			}
				$d_output .= $ul_output.'		    			
		</article>';
				if($d_count == 0)
					$d_output = '';
				$m_output .= $d_output;

		}
		$m_output .= '</section>';
		if($m_count == 0)
			$m_output = '';
		print $m_output;
	?>
	<?php } ?>
	
<?php } ?>
	</section>

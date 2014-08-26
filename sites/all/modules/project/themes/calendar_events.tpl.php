<?php global $base_url,$user;?>
<?php date_default_timezone_set("Asia/Bangkok");?>
<?php $alias = 'node/'.$project->nid;?>
<?php 
	$events = 0;
	if(!$with_clients || !$is_client){
		$result = db_query("SELECT COUNT(aid) as total FROM tbl_project_attribute WHERE (type = 2 OR type = 5) AND deleted = 0 AND nid = ".$project->nid);
		foreach($result as $object){
			$events = $object->total;
		}
	} else { 
		$result = db_query("SELECT COUNT(aid) as total FROM tbl_project_attribute WHERE send_client = 1 AND (type = 2 OR type = 5) AND deleted = 0 AND nid = ".$project->nid);
		foreach($result as $object){
			$events = $object->total;
		}
	}
	$author = user_load($user->uid);
	$author->full_name = '';
	if(isset($author->field_profile_fname['und'][0]['value']))
		$author->full_name .= $author->field_profile_fname['und'][0]['value'] . ' ';
	if(isset($author->field_profile_lname['und'][0]['value']))
		$author->full_name .= $author->field_profile_lname['und'][0]['value'];
	$author->full_name = trim($author->full_name);
	if(empty($author->full_name))
		$author->full_name = $author->mail;
	$users = array();
	$result = db_query("SELECT uid,client FROM tbl_assign_project WHERE nid = ".$project->nid." AND uid <> ".$user->uid." ORDER BY client ASC");
	$clients = array();
	foreach($result as $object){
		$member = user_load($object->uid);
		$member->full_name = '';
		if(isset($member->field_profile_fname['und'][0]['value']))
			$member->full_name .= $member->field_profile_fname['und'][0]['value'] . ' ';
		if(isset($member->field_profile_lname['und'][0]['value']))
			$member->full_name .= $member->field_profile_lname['und'][0]['value'];
		$member->full_name = trim($member->full_name);
		if(empty($member->full_name))
			$member->full_name = $member->mail;
		$member->client = $object->client;
		$users[] = $member;
		if($object->client)
			$clients[] = $member->full_name;
	}
	$client_names = '';
	if(!empty($clients)){
		$client_names = $clients[count($clients)-1];
		unset($clients[count($clients)-1]);
		if(!empty($clients))
			$client_names = implode(', ',$clients).' and '.$client_names;
	}
	$clients = isset($project->field_with_clients['und'][0]['value'])?$project->field_with_clients['und'][0]['value']:0;
	$color = $project->field_color['und'][0]['value'];
?>
<div data-creator-id="<?php print $project->nid;?>" class="panel sheet project active inactive" <?php if($project->sticky==1) print' data-behavior ="read_only" data-status="archived"'; else print 'data-status="active"'?>>
    <header><h1><a class="link_page" data-restore-position="" <?php if($project->type == 'project') print 'data-nid="'.$project->nid.'"'?>href="<?php if($project->type == 'project') print $base_url.'/'.$alias; else print $base_url.'/calendar';?>"><?php print $project->title;?></a></h1></header>
	<div class="panel sheet calendar_events<?php if(!$events) print ' blank_slate';?>" style="margin-left: 20px; margin-bottom: -20px;">
		<header class="has_buttons">
		  <h1 class="upcoming_events">Dates</h1>
		  <div class="upcoming_events">
		    <span class="position_reference">
		      <button class="action_button" onclick="new_calendar_event()" <?php if($project->sticky) echo 'style="display:none;"';?>>Add a new event</button>
		      <div class="blank_slate_arrow"></div>
		    </span>
		  </div>
		</header>
    	<div class="sheet_body">
      		<title><?php print $project->title;?>: Events</title>
        	<div id="project_calendar_event_editor" class="project_calendar_event_editor all_day" data-autoview="">
        		<article data-behavior="expandable hide_buttons_on_expand" class="calendar_event new">
				  <div class="expanded_content bubble">
					<form id="add-event" class="calendar_event_editor_form" action="<?php print $base_url;?>/ajax-process?action=add_event" method="post">
					  <div data-behavior="expandable recurrence_prompt">
						<div class="collapsed_content">
						  <header class="text_entry">
								<fieldset class="event_name">
								  <table cellspacing="0" cellpadding="0" border="0">
									<tbody><tr>
									  <td>
										<strong>
										  <textarea placeholder="Name the event" rows="1" data-behavior="autoresize placeholder submit_on_enter dirty_tracking" name="summary" data-autoresize="true" style="resize: none; overflow: hidden; min-height: 1px;color:#000;" onKeyup="name_event(this)">New event</textarea>
										</strong>
									  </td>
									</tr>
								  </tbody>
								 </table>
								</fieldset>
						  		<fieldset class="event_time">
									<table>
							  			<tbody>
							  				<tr>
												<td data-behavior="time_entry" class="time">
													<input type="text" name="starts_at_time" value="" data-behavior="placeholder dirty_tracking" data-type="text" placeholder="At what time? (9am, 1pm, etc.)" class="placeholding">
												</td>
							  				</tr>
										</tbody>
									</table>
						  		</fieldset>
								<fieldset class="event_note">
							  		<table>
										<tbody>
											<tr>
											  	<td class="description">
													<textarea rows="1" name="description" data-type="textarea" placeholder="Add an optional note" class="placeholding" data-autoresize="true" style="resize: none; overflow: hidden; min-height: 21px;"></textarea>
											  	</td>
											</tr>
							  			</tbody>
							  		</table>
								</fieldset>
								<fieldset data-role="bucket_fields" class="event_calendar">
										<input type="hidden" value="<?php print $project->nid;?>" name="bucket">
								</fieldset>
								  <fieldset class="event_date">
									  <div class="start_date">
										<table cellspacing="0" cellpadding="0" border="0">
										  <tbody><tr>
											<td class="label">
											  <label>Starts:</label>
											</td>
											<td data-behavior="date_entry" class="date">
											  <input name="starts_at_date" type="text" tabindex="-1" readonly="" class="date datepicker" value="<?php print date('F j, Y');?>">
											</td>
										  </tr>
										</tbody>
									   </table>
									  </div>
									  <div class="end_date">
										<table cellspacing="0" cellpadding="0" border="0">
										  <tbody><tr>
											<td class="label">
											  <label>Ends:</label>
											</td>
											<td data-behavior="date_entry" class="date">
											  <input name="ends_at_date" type="text" tabindex="-1" readonly="" class="date datepicker" value="<?php print date('F j, Y');?>">
											</td>
										  </tr>
										</tbody>
									   </table>
									  </div>
								</fieldset>
								<fieldset class="event_recurrence">
								  <table>
									<tbody><tr>
									  <td class="label">
										<label>Repeats?</label>
									  </td>
									  <td>
										  <select name="freq">
						
											  <option value="">No</option>
						
											  <option value="daily">Every day</option>
						
											  <option value="weekly">Every week</option>
						
											  <option value="monthly">Every month</option>
						
											  <option value="yearly">Every year</option>
						
										  </select>

										  <div style="display:none" class="recurrence_end">
											<ul>
											  <li>
												<input type="radio" value="count" name="event_recurrence_end" id="event_recurrence_end" checked="">
												<span class="wrapper">
												  For
												  <select name="count">
														<?php for($i=2;$i<=30;$i++){?>
														<option value="<?php print $i;?>"><?php print $i;?></option>
														<?php } ?>
												  </select>
												  <label data-role="freqtext" for="event_recurrence_end"></label>
												</span>
											  </li>
												<li>
												  <label>
													<input type="radio" value="until" name="event_recurrence_end">
													<span data-behavior="date_entry" class="wrapper">Until
													  <input name="until_date" type="text" readonly="" class="date datepicker" value="<?php print date('F j, Y');?>">
													</span>
												  </label>
												</li>
											</ul>
										  </div>
									  </td>
									</tr>
								  </tbody>
								 </table>
								</fieldset>
          						</header>
          						<footer>
          							<?php if(!empty($users)){?>
									<span style="<?php if(!$clients) print 'display:none;';?>" data-role="privacy_toggle">
									  	<label>
									  		<input type="checkbox" onclick="toggle_private_visibility(this)" name="private" value="1"> Don’t show this event to the client <span data-behavior="client_list"><?php print $client_names;?></span>
									  	</label>
									  	<br><br>
									</span>
									<div data-behavior="lazy_load_subscribers">
										<div class="subscribable" data-subscribers="[]">
											<div class="expanded_content">
												<span style="" data-behavior="multiple_subscribers">
													<div data-subscriber-ids="[]" data-groups="[]" data-private="false" data-client-ids="[]" data-new-subscribable="true" data-subscribable="calendar_event" data-behavior="subscriber_list">
														<table>
														  	<tbody>
																<tr>
															  		<td class="subscribers">
																  		<h4>Email <span data-behavior="pluralize_subscribable_label">this event</span> to people on the project:</h4>
																		<div class="select_all_or_none">
																			<a onclick="subscriber_select_all(this,event)" class="decorated select_everyone" href="#">Select all</a> |
																			<a onclick="subscriber_select_none(this,event)" class="decorated select_everyone" href="#">Select none</a>

																			<a style="display:none" data-behavior="subscriber_select_remembered" class="decorated select_everyone" href="#">
																			  Select remembered (only included as a gateway for the label click)
																			</a>
																		</div>
																		<div class="subscribers">
																  		<div>
																  			<input type="hidden" value="<?php print $user->uid;?>" name="calendar_event[subscribers][]" class="send_choose_mail">
													<?php $uids = array(0);?>
													<?php $length = count($users);?>
													<?php $row = floor($length/3);?>
													<?php if($length%3 != 0) $row++;?>
													<?php for($i=0;$i<$length;$i+=$row){?>
																		<div class="column">
													<?php 	for($j=$i;$j<($i+$row) && $j<$length; $j++){?>
													<?php 		$uids[] = $users[$j]->uid;?>
																		  <label class="<?php if($users[$j]->client) print 'client';?>" title="<?php print $users[$j]->full_name;?>" data-subscriber-id="<?php print $users[$j]->uid;?>">
																			<input type="checkbox" value="<?php print $users[$j]->uid;?>" name="calendar_event[subscribers][]" class="send_choose_mail">
																			<?php print $users[$j]->full_name;?>
																		  </label>
													<?php 	} ?>
																		</div>
													<?php } ?>
																  </div>
																</div>
															  </td>
													<?php $companies = get_project_groups($project->nid);?>			  
												            <?php if (isset($companies) && count($companies) > 0) {
												                ?>
												                <td class="groups">
												                    <h4>Select people from:</h4>
												                    <div class="group_options">
												                        <?php
												                        foreach ($companies as $company) {
												                            ?>
												                            <a data-group-id="group_<?= $company->id ?>" data-member-ids="[<?= $company->uids ?>]" class="companies_options link_page">
												                                <span><?= $company->title ?></span>
												                            </a>
												                            <div class="subgroup_options">
												                                <?php
												                                foreach ($company->groups as $group) {
												                                    ?>
												                                    <a data-parent-group-id="group_<?= $company->id ?>" data-group-id="group_<?= $group->id ?>" data-member-ids="[<?= $group->uids ?>]" class="subgroup link_page">
												                                        <span><?= $group->title ?></span>
												                                    </a>
												                                <?php } ?>
												                            </div>
												                        <?php } ?>
												                    </div>
												                </td>
												            <?php } ?>
															</tr>
														  </tbody>
														</table>
													</div>
												</span>
												<div data-behavior="expandable" class="subscribers ad-hoc">
												<div class="collapsed_content">
												  <span>
													<a href="#" data-behavior="expand_on_click" class="decorated">Loop-in someone who isn't on the project</a> to share this by email only
												  </span>
												</div>

												<div class="expanded_content">
												  <h4>Loop-in someone who isn't on the project to share this by email only:</h4>

												  <div class="note">
													They’ll see the whole discussion, and they can reply via email,
													but they can’t see anything else in the project.
												  </div>
												  <div data-outside-subscribers="[<?php print implode(',',$uids);?>]" data-subscribable="calendar_event" data-behavior="adhoc_subscribers" class="fieldset" id="persons">
														<div class="person field blank focused">
															<div class="autocomplete_people">
																<label class="focused placeholder">Type a name or email address...</label>
																<label class="blurred placeholder">Add another person...</label>
																<input type="text" value="" data-role="human_input" data-behavior="input_change_emitter" class="reminder" onblur="share_blur(this)" onfocus="share_focus(this)" onchange="share_change(this)" onkeyup="share_keyup(this,event)">
																<input type="hidden" value="" name="comment[new_subscriber_emails][]" data-role="email_address_input">
																<div data-role="suggestions_view" class="suggestions">
																	<ol class="suggestions">
																	</ol>
																</div>
															</div>
														</div>
												  </div>
												</div>
											  </div>
											</div>
										</div>
									</div>
									<?php } ?>
									<p class="submit">
										<input type="hidden" name="wants_notification" value="1"/>
										<input type="submit" class="action_button green" value="Add this event">
									  or <a data-behavior="cancel" data-role="cancel" class="cancel" href="#">Cancel</a>
									</p>
							  	</footer>
							</div>
							<div data-role="expanded_recurrence_prompt" class="expanded_content">
								<div data-role="recurrence_prompt" class="recurrence_prompt">
						  			<h4>You're  a repeating event.<br>Do you want to  all future versions of this event too?</h4>
								  	<ul>
										<li>
										  <label>
											<input type="radio" value="recurring" name="event_recurrence_choice">
											<span class="button">Yes,  all future versions.</span>
										  </label>
										</li>
		
										  <li>
											<label>
											  <input type="radio" value="excluded" name="event_recurrence_choice">
											  <span class="button">No, just  this one and keep the others as they are.</span>
											</label>
										  </li>
		
										<li>
										  <label>
											<input type="radio" value="cancel" name="event_recurrence_choice">
											<span class="button">Nevermind, don't  anything.</span>
										  </label>
										</li>
								  	</ul>
								</div>
							</div>
			  			</div>
					</form>
		  		</div>
			</article>
		</div>
        <div id="date_display_project" class="date_display" data-autoview="">
        	<section class="calendar_events">
        		<article class="blank_slate">
				  <header>
					<img width="234" height="182" alt="Text Documents" src="<?php print $base_url;?>/sites/all/themes/adaptivetheme/basecamp/images/blank_slate_icon_events.png">
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
                                                                <a href="<?php print $base_url.'/'.$event->alias;?>" class="link_page"><?php print $event->title;?></a> 
						  		<?php if($comments[$event->aid]){?>
                                                                <span class="pill comments"><a href="<?php print $base_url.'/'.$event->alias;?>" class="link_page"><?php print $comments[$event->aid];?> comment</a></span>
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
									  <input type="checkbox" onclick="toggle_todo(this,<?php print $todo->aid;?>)" value="1">
									  <span class="content">
                                                                              <a title="List: <?php print $todo->name;?>" href="<?php print $base_url.'/todos/'.$todo->aid;?>" class="link_page"><?php print $todo->title;?></a>
										<?php if($comments[$todo->aid]){?>
                                                                              <span class="pill comments"><a href="<?php print $base_url;?>/todos/<?php print $todo->aid;?>" class="link_page"><?php print $comments[$todo->aid];?> comments</a></span>
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
    									<?php if($with_clients && !$is_client && $todo->send_client == 0){?>
										<span class="hidden_from_client" style="display:none;">The client can’t see this todo</span>
										<?php } ?>
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
						  		<a href="'.$base_url.'/'.$event->alias.'" class="link_page">'.$event->title.'</a>';
						  			if($comments[$event->aid]){
						  				$ul_output .= '
								<span class="pill comments"><a href="'.$base_url.'/'.$event->alias.'" class="link_page">'.$comments[$event->aid].' comment</a></span>';
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
											<a title="List: '.$todo->name.'" href="'.$base_url.'/todos/'.$todo->aid.'" class="link_page">'.$todo->title.'</a>';
									if($comments[$todo->aid]){
										$ul_output .= '
											<span class="pill comments"><a href="'.$base_url.'/todos/'.$todo->aid.'" class="link_page">'.$comments[$todo->aid].' comments</a></span>';
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
											</span>';
    								if($with_clients && !$is_client && $todo->send_client == 0){
										$ul_output .= '
											<span class="hidden_from_client" style="display:none;">The client can’t see this todo</span>';
									}
                                    $ul_output .= '
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
							  		<a href="'.$base_url.'/'.$event->alias.'" class="link_page">'.$event->title.'</a>';
							  			if($comments[$event->aid]){
							  				$ul_output .= '
									<span class="pill comments"><a href="'.$base_url.'/'.$event->alias.'" class="link_page">'.$comments[$event->aid].' comment</a></span>';
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
												<a title="List: '.$todo->name.'" href="'.$base_url.'/todos/'.$todo->aid.'" class="link_page">'.$todo->title.'</a>';
										if($comments[$todo->aid]){
											$ul_output .= '
												<span class="pill comments"><a href="'.$base_url.'/todos/'.$todo->aid.'" class="link_page">'.$comments[$todo->aid].' comments</a></span>';
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
												</span>';
    									if($with_clients && !$is_client && $todo->send_client == 0){
											$ul_output .= '
												<span class="hidden_from_client" style="display:none;">The client can’t see this todo</span>';
										}
                                        $ul_output .= '
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
						  		<a href="'.$base_url.'/'.$event->alias.'" class="link_page">'.$event->title.'</a>';
						  			if($comments[$event->aid]){
						  				$ul_output .= '
								<span class="pill comments"><a href="'.$base_url.'/'.$event->alias.'" class="link_page">'.$comments[$event->aid].' comment</a></span>';
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
											if(isset($member->uid)){
												if(isset($member->field_profile_fname['und'][0]['value']))
													$assign .= $member->field_profile_fname['und'][0]['value'].' ';
												if(isset($member->field_profile_lname['und'][0]['value']))
													$assign .= $member->field_profile_lname['und'][0]['value'];
												$assign = trim($assign);
												if(empty($assign))
													$assign = $member->mail;
											}
										}
										else $assign = 'Unassigned';
										$ul_output .= '
									<li>
										<form data-behavior="edit_calendar_todo has_hover_content" data-remote="true" method="PUT" action="" class="calendar_todo_editor_form">
										  <div>
											<span class="wrapper ">
											  <input type="checkbox" onclick="toggle_todo(this,'.$todo->aid.')" value="1">

											  <span class="content">
												<a title="List: '.$todo->name.'" href="'.$base_url.'/todos/'.$todo->aid.'" class="link_page">'.$todo->title.'</a>';
										if($comments[$todo->aid]){
											$ul_output .= '
												<span class="pill comments"><a href="'.$base_url.'/todos/'.$todo->aid.'" class="link_page">'.$comments[$todo->aid].' comments</a></span>';
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
												</span>';
    									if($with_clients && !$is_client && $todo->send_client == 0){
											$ul_output .= '
												<span class="hidden_from_client" style="display:none;">The client can’t see this todo</span>';
										}
                                        $ul_output .= '
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
			</div>
		</div>
	</div>
</div>
<script>
	var aid = 0;
	var blank = <?php if(empty($event)) print '1'; else print '0';?>;
	jQuery(document).ready(function(){
		data_behavior();
                   load_page();
		jQuery("#add-event").ajaxForm(options);
		jQuery("body").bind("click",function(){
			jQuery(".pill").removeClass("expanded");
		});
		jQuery(".pill .datepicker").datepicker({ dateFormat: "MM d, yy", onSelect: function(dateText,inst ){ todo_set_date(dateText)} });
		jQuery(".pill select").bind("change",function(){
			todo_set_user(this);
		});
		jQuery(".balloon").bind("click",function(event){
			event.stopPropagation();
		});
		jQuery("body").bind("click",function(){
			jQuery("div.suggestions").html("");
		});
        jQuery(".send_choose_mail").click(function(){
            var me = jQuery(this);
            select_group_company(me);
        });
        jQuery(".subgroup").click(function(){
            var me = jQuery(this);
            var member_ids =  jQuery(me).data("member-ids");
            if(jQuery(me).hasClass("activated"))
                jQuery(me).removeClass("activated");
            else 
                jQuery(me).addClass("activated");
             var company_id = jQuery(me).data("parent-group-id");
            jQuery(".send_choose_mail").each(function(){
                var parent = jQuery(this).parent();
                var uid = jQuery(parent).data("subscriber-id");
                var exist = jQuery.inArray(uid, member_ids);
                if(exist>-1){
                    if(jQuery("a.companies_options[data-group-id='"+company_id+"']").hasClass("activated")){
                        jQuery(this).prop('checked', true);  
                    }else {
                        if(jQuery(me).hasClass("activated"))
                        jQuery(this).prop('checked', true);  
                    else 
                        jQuery(this).prop('checked', false);
                    }                                
                }
            });
        });
        jQuery(".companies_options").click(function(){
            var me = jQuery(this);
            var member_ids =  jQuery(me).data("member-ids");
            if(jQuery(me).hasClass("activated"))
                jQuery(me).removeClass("activated");
            else 
                jQuery(me).addClass("activated");
            var group_id = jQuery(me).data("group-id");
            jQuery(".send_choose_mail").each(function(){
                var parent = jQuery(this).parent();
                var uid = jQuery(parent).data("subscriber-id");
                var exist = jQuery.inArray(uid, member_ids);
                if(exist>-1){
                    if(jQuery(me).hasClass("activated")){
                        jQuery(".subgroup_options a[data-parent-group-id='"+group_id+"']").addClass("activated");
                        jQuery(this).prop('checked', true);
                    }
                    else {
                        jQuery(".subgroup_options a[data-parent-group-id='"+group_id+"']").removeClass("activated");
                        jQuery(this).prop('checked', false); 
                    }
                }
                
            }); 
        });
	});

	function new_calendar_event(){
		if(blank){
			jQuery(".calendar_events").removeClass("blank_slate");
		}
		jQuery(".calendar_events > header").addClass("hide_buttons");
		jQuery("#project_calendar_event_editor .calendar_event").addClass("expanded");
	}
	var options = { 
		beforeSubmit:  showRequest,
		success:       showResponse
	}; 
	
	function showRequest(formData, jqForm, options) { 
		var secs = options.url.split('action=');
		var action = secs[1];
		if(action == 'add_event')
			jQuery("#add-event p.submit").addClass("busy");
	}
	
	function showResponse(responseText, statusText, xhr, form) { 
		var result = jQuery.parseJSON(responseText);
		if(result.error == 0){
			jQuery("#date_display_project").html(result.events);
			jQuery(".calendar_events > header").removeClass("hide_buttons");
			jQuery("#project_calendar_event_editor > .calendar_event").removeClass("expanded");
			jQuery(".pill .datepicker").datepicker({ dateFormat: "MM d, yy", onSelect: function(dateText,inst ){ todo_set_date(dateText)} });
			jQuery(".pill select").bind("change",function(){
				todo_set_user(this);
			});
			jQuery(".balloon").bind("click",function(event){
				event.stopPropagation();
			});
			jQuery("#event_recurrence_end").click();
			jQuery("#add-event textarea").val("");
			jQuery("input[name=starts_at_time]").each(function(){
				this.value = '';
			});
			jQuery("input[name=description]").each(function(){
				this.value = '';
			});
		}
		else{
			alert(result.message);
		}
		jQuery("#add-event p.submit").removeClass("busy");
	}

	function toggle_private_visibility(self){
		if(self.checked)
			jQuery("label.client").css("display","none");
		else jQuery("label.client").css("display","");
	}
	function expand_on_click(self){
		jQuery(".ad-hoc").addClass("expanded");
	}

	function cancel(self){
		jQuery(".calendar_events > header").removeClass("hide_buttons");
		jQuery("#project_calendar_event_editor > .calendar_event").removeClass("expanded");
	}
	function edit_todo(self,event,id){
		event.preventDefault();
		event.stopPropagation();
		aid = id;
		jQuery(".pill").removeClass("expanded");
		var parent = jQuery(self).parent();
		jQuery(parent).addClass("exclusively_expanded");
		jQuery(parent).addClass("expanded");
	}
	function todo_set_date(date){
		jQuery(".pill").removeClass("expanded");
		var id="#pill-" + aid;
		jQuery(id).addClass("busy");
		jQuery.post("<?php print $base_url;?>/ajax-calendar",{action:"todo_set_date","aid":aid,"date":date},function(data){
			if(data.o_date != data.date){
				jQuery(".dates > .date").each(function(){
					var date = jQuery(this).attr("data-date");
					if(date == data.o_date){
						jQuery(this).children(".todos").html(data.o_todos);
					}
					else if(date == data.date){
						jQuery(this).children(".todos").html(data.todos);
					}
				});
			}
			window.location.href = window.location.href;
		},'json');
	}
	function todo_set_user(self){
		jQuery(".pill").removeClass("expanded");
		var parent = jQuery(self).parents(".pill:first");
		parent.addClass("busy");
		jQuery.post("<?php print $base_url;?>/ajax-calendar",{action:"todo_set_user","aid":aid,"uid":self.options[self.selectedIndex].value},function(data){
			jQuery(parent).children("a").children("span").html(jQuery(self.options[self.selectedIndex]).html());
			parent.removeClass("busy");
		});
	}
	function no_due_date(self,event,aid){
		event.preventDefault();
		event.stopPropagation();
		jQuery(".pill").removeClass("expanded");
		var parent = jQuery(self).parents(".pill:first");
		parent.addClass("busy");
		jQuery(self).parents(".pill:first").addClass("busy");
		jQuery.post("<?php print $base_url;?>/ajax-calendar",{action:"todo_remove_date","aid":aid},function(data){
			var li = jQuery(parent).parents("li").first();
			var ul = jQuery(li).parent();
			var lis = jQuery(ul).children("li");
			if(lis.length > 1)
				jQuery(ul).children("li").first().children("h5").html((lis.length-1) + ' todos');
			var article = jQuery(li).parents("article").first();
			jQuery(li).remove();
			lis = jQuery(article).find("li");
			if(lis.length == 1)
				jQuery(article).remove();
		},'json');
	}
	jQuery("form#add-event input.date").datepicker({ dateFormat: "MM d, yy", onSelect: function(dateText,inst ){ 
		var sdate_text = '';
		var edate_text = '';
		var udate_text = '';
		var name = jQuery(inst.input).attr("name");
		if(name == 'starts_at_date'){
			sdate_text = dateText;
			jQuery("input[name=ends_at_date]").each(function(){ edate_text = this.value;});
			jQuery("input[name=until_date]").each(function(){ udate_text = this.value;});
		}
		else if(name == 'ends_at_date'){
			edate_text = dateText;
			jQuery("input[name=starts_at_date]").each(function(){ sdate_text = this.value;});
			jQuery("input[name=until_date]").each(function(){ udate_text = this.value;});
		}
		else if(name == 'until_date'){
			udate_text = dateText;
			jQuery("input[name=ends_at_date]").each(function(){ edate_text = this.value;});
			jQuery("input[name=starts_at_date]").each(function(){ sdate_text = this.value;});
		}
		var sdate = new Date(sdate_text);
		var edate = new Date(edate_text);
		var udate = new Date(udate_text);
		if(name == 'until_date'){
			var compare = compare_date(udate,edate);
			if(compare == -1){
				jQuery("input[name=ends_at_date]").datepicker( "setDate", dateText );
			}
			compare = compare_date(udate,sdate);
			if(compare == -1){
				jQuery("input[name=starts_at_date]").datepicker( "setDate", dateText );
				jQuery("form.calendar_event_editor_form .event_time").css("display","block");
			}
			else if(compare == 0){
				jQuery("form.calendar_event_editor_form .event_time").css("display","block");
			}
		}
		else if(name == 'starts_at_date'){
			var compare = compare_date(sdate,edate);
			if(compare == 1){
				jQuery("input[name=ends_at_date]").datepicker( "setDate", dateText );
				jQuery("form.calendar_event_editor_form .event_time").css("display","block");
			}
			else if(compare == 0){
				jQuery("form.calendar_event_editor_form .event_time").css("display","block");
			}
			else if(compare == -1){
				jQuery("form.calendar_event_editor_form .event_time").css("display","none");
			}
			compare = compare_date(sdate,udate);
			if(compare == 1){
				jQuery("input[name=until_date]").datepicker( "setDate", dateText );
			}
		}
		else if(name == 'ends_at_date'){
			var compare = compare_date(sdate,edate);
			if(compare == 1){
				jQuery("input[name=starts_at_date]").datepicker( "setDate", dateText );
				jQuery("form.calendar_event_editor_form .event_time").css("display","block");
			}
			else if(compare == 0){
				jQuery("form.calendar_event_editor_form .event_time").css("display","block");
			}
			else if(compare == -1){
				jQuery("form.calendar_event_editor_form .event_time").css("display","none");
			}
			compare = compare_date(edate,udate);
			if(compare == 1){
				jQuery("input[name=until_date]").datepicker( "setDate", dateText );
			}
		}
	}});
	jQuery("textarea[name=summary]").bind("keyup",function(){
		var content = jQuery(this).val();
		if(content.length == 0){
			jQuery(this).addClass("placeholding");
		}
		else{
			jQuery(this).removeClass("placeholding");
		}
	});
	jQuery("input[name=starts_at_time]").bind("keyup",function(){
		if(this.value.length == 0){
			jQuery(this).addClass("placeholding");
		}
		else{
			jQuery(this).removeClass("placeholding");
		}
	}).bind("change",function(){
		this.value = this.value.trim();
		this.value = this.value.toLowerCase();
		var hour = this.value.replace('.',':').replace('.',':');
		var secs = hour.split(':');
		if(secs.length > 3 || secs[0].length == 0){
			this.value = '';
			jQuery(this).addClass("placeholding");
		}
		else{
			if(secs.length == 2){
				if(secs[0].length%2 != 0){
					hour = secs[0].substring(0,secs[0].length-1) + '0' + secs[0].substring(secs[0].length-1);
				}
				else hour = secs[0];
				hour += secs[1];
			}
			else if(secs.length == 3){
				if(secs[0].length%2 != 0){
					hour = secs[0].substring(0,secs[0].length-1) + '0' + secs[0].substring(secs[0].length-1);
				}
				else hour = secs[0];
				if(secs[1].length%2 != 0){
					hour += secs[1].substring(0,secs[1].length-1) + '0' + secs[1].substring(secs[1].length-1);
				}
				else hour += secs[1];
				hour += secs[2];
			}
			if(hour.length == 0){
				this.value = '';
				jQuery(this).addClass("placeholding");
			}
			var idx = hour.length-1;
			for(var i=hour.length-1; i>=0; i--){
				if(is_numeric(hour[i])){
					idx = i;
					break;
				}
			}
			var suffix = hour.substring(idx+1);
			hour = hour.substring(0,idx+1);
			if(suffix.length > 0 && suffix != 'am' && suffix != 'pm' && suffix != 'h'){
				this.value = '';
				jQuery(this).addClass("placeholding");
			}
			else{
				if(hour.length == 0 || !is_numeric(hour)){
					this.value = '';
					jQuery(this).addClass("placeholding");
				}
				else{
					switch(hour.length){
						case 1:
						case 2:
							if(hour[0] == '0' && hour.length == 2)
								hour = hour[1];
							var h = parseInt(hour);
							if(h>23){
								this.value = '';
								jQuery(this).addClass("placeholding");
							}
							else if(h>12)
								this.value = (h-12) + 'pm';
							else{
								if(h == 0) this.value = '12am';
								else if(suffix == 'pm')
									this.value = h + 'pm';
								else this.value = h + 'am';
							}
							break;
						case 3:
							var hr = hour.substring(0,2);
							var mi = hour.substring(2);
							if(hr[0] == '0' && hr.length == 2)
								hr = hr[1];
							if(mi[0] == '0' && mi.length == 2)
								mi = mi[1];	
							var h = parseInt(hr);
							var m = parseInt(mi);
							if(h>23 || m > 59){
								this.value = '';
								jQuery(this).addClass("placeholding");
							}
							else if(h>12){
								if(m == 0)
									m = '';
								else m = ':0' + m;
								this.value = (h-12) + m + 'pm';
							}
							else{
								if(m == 0)
									m = '';
								else m = ':0' + m;
								if(h == 0) this.value = '12' + m + 'am';
								else if(suffix == 'pm')
									this.value = h + m + 'pm';
								else this.value = h + m + 'am';
							}
							break;
						case 4:
							var hr = hour.substring(0,2);
							var mi = hour.substring(2);
							if(hr[0] == '0' && hr.length == 2)
								hr = hr[1];
							if(mi[0] == '0' && mi.length == 2)
								mi = mi[1];	
							var h = parseInt(hr);
							var m = parseInt(mi);
							if(h>23 || m > 59){
								this.value = '';
								jQuery(this).addClass("placeholding");
							}
							else if(h>12){
								if(m == 0)
									m = '';
								else if(m < 10)
									m = ':0' + m;
								else m = ':'+m;
								this.value = (h-12) + m + 'pm';
							}
							else{
								if(m == 0)
									m = '';
								else if(m < 10)
									m = ':0' + m;
								else m = ':'+m;
								if(h == 0) this.value = '12' + m + 'am';
								else if(suffix == 'pm')
									this.value = h + m + 'pm';
								else this.value = h + m + 'am';
							}
							break;
						case 5:
							var hr = hour.substring(0,2);
							var mi = hour.substring(2,4);
							var sd = hour.substring(4);
							if(hr[0] == '0' && hr.length == 2)
								hr = hr[1];
							if(mi[0] == '0' && mi.length == 2)
								mi = mi[1];	
							if(sd[0] == '0' && sd.length == 2)
								sd = sd[1];	
							var h = parseInt(hr);
							var m = parseInt(mi);
							var s = parseInt(sd);
							if(h>23 || m > 59 || s > 59){
								this.value = '';
								jQuery(this).addClass("placeholding");
							}
							else if(h>12){
								if(m == 0 && s == 0)
									m = '';
								else if(m == 0 && s > 0)
									m = ':00';
								else if(m < 10)
									m = ':0' + m;
								else m = ':' + m;
								if(s == 0)
									s = '';
								else s = ':0' + s;
								this.value = (h-12) + m + s + 'pm';
							}
							else{
								if(m == 0 && s == 0)
									m = '';
								else if(m == 0 && s > 0)
									m = ':00';
								else if(m < 10)
									m = ':0' + m;
								else m = ':' + m;
								if(s == 0)
									s = '';
								else s = ':0' + s;
								if(h == 0) this.value = '12' + m + s + 'am';
								else if(suffix == 'pm')
									this.value = h + m + s + 'pm';
								else this.value = h + m + s + 'am';
							}
							break;
						case 6:
							var hr = hour.substring(0,2);
							var mi = hour.substring(2,4);
							var sd = hour.substring(4);
							if(hr[0] == '0' && hr.length == 2)
								hr = hr[1];
							if(mi[0] == '0' && mi.length == 2)
								mi = mi[1];	
							if(sd[0] == '0' && sd.length == 2)
								sd = sd[1];	
							var h = parseInt(hr);
							var m = parseInt(mi);
							var s = parseInt(sd);
							if(h>23 || m > 59 || s > 59){
								this.value = '';
								jQuery(this).addClass("placeholding");
							}
							else if(h>12){
								if(m == 0 && s == 0)
									m = '';
								else if(m == 0 && s > 0)
									m = ':00';
								else if(m < 10)
									m = ':0' + m;
								else m = ':' + m;
								if(s == 0)
									s = '';
								else if(s < 10)
									s = ':0' + s;
								else s = ':' + s;
								this.value = (h-12) + m + s + 'pm';
							}
							else{
								if(m == 0 && s == 0)
									m = '';
								else if(m == 0 && s > 0)
									m = ':00';
								else if(m < 10)
									m = ':0' + m;
								else m = ':' + m;
								if(s == 0)
									s = '';
								else if(s < 10)
									s = ':0' + s;
								else s = ':' + s;
								if(h == 0) this.value = '12' + m + s + 'am';
								else if(suffix == 'pm')
									this.value = h + m + s + 'pm';
								else this.value = h + m + s + 'am';
							}
							break;
						default:
							this.value = '';
							jQuery(this).addClass("placeholding");
							break;
					}
				}
			}
		}
	});
	jQuery("textarea[name=description]").bind("keyup",function(){
		var content = jQuery(this).val();
		if(content.length == 0){
			jQuery(this).addClass("placeholding");
		}
		else{
			jQuery(this).removeClass("placeholding");
		}
	});
	jQuery("select[name=freq]").bind("change",function(){
		var value = this.options[this.selectedIndex].value;
		if(value.length > 0){
			var next = jQuery(this).next();
			jQuery(next).css("display","block");
			var label = value.replace('ily','ys');
			label = label.replace('ly','s');
			jQuery(next).find("li:first label").html(label);
			
		}
		else jQuery(this).next().css("display","none");
	});
	function autocomplete(self){
		if(self.value.length > 1){
			var disabled = jQuery("#persons").attr("data-outside-subscribers");
			jQuery.post("<?php print $base_url;?>/ajax-process",{action:"get_suggested_users",keyword:self.value,'disabled':disabled},function(data){
				if(data.length>0){
					var view = jQuery(self).next().next();
					jQuery(view).html(data);
					jQuery(view).find("li").hover(function(){
						jQuery(this).addClass("selected");
					},function(){
						jQuery(this).removeClass("selected");
					});
					jQuery(view).find("li").removeAttr("onclick");
					jQuery(view).find("li").bind("click",function(event){
						if(!jQuery(this).hasClass("disabled")){
							var email = jQuery(this).children("span.email").html();
							var input = jQuery(this).parent().parent().prev();
							jQuery(input).each(function(){this.value = email;});
							jQuery(input).prev().each(function(){this.value = email;});
							var person = jQuery(input).parent().parent();
							jQuery(person).removeClass("unknown");
							var next = jQuery(person).next();
							var content = jQuery(next).html();
							if(typeof content == 'undefined'){
								var field = '';
								field += '<div class="person field blank">';
								field += '	<div class="autocomplete_people">';
								field += '		<label class="focused placeholder">Type a name or email address...</label>';
								field += '		<label class="blurred placeholder">Add another person...</label>';
								field += '		<input type="text" value="" data-role="human_input" data-behavior="input_change_emitter" onblur="share_blur(this)" onfocus="share_focus(this)" onchange="share_change(this)" onkeyup="share_keyup(this,event)">';
								field += '		<input type="hidden" value="" name="comment[new_subscriber_emails][]" data-role="email_address_input">';
								field += '		<div data-role="suggestions_view" class="suggestions"></div>';
								field += '	</div>';
								field += '</div>';
								jQuery(person).parent().append(field);
							}
							jQuery(view).html("");
						}
					});
				}
			});
		}
	}
	function subscriber_select_all(self,event){
		event.preventDefault();
		event.stopPropagation();
		jQuery(".subscribers input[type=checkbox]").each(function(){
			this.checked = true;
		});
		set_checkbox('all');
	}
	function subscriber_select_none(self,event){
		event.preventDefault();
		event.stopPropagation();
		jQuery(".subscribers input[type=checkbox]").each(function(){
			this.checked = false;
		});
		set_checkbox('none');
	}
	function expand_share(self,event){
		event.preventDefault();
		event.stopPropagation();
		var parent = jQuery(self).parent().parent().parent();
		jQuery(parent).addClass("expanded");
	}
	function share_blur(self){
		var person = jQuery(self).parents(".person:first");
		self.value = self.value.trim();
		if(self.value.length > 0)
			jQuery(person).removeClass("blank");
		else jQuery(person).addClass("blank");
		jQuery(person).removeClass("focused");
		if(is_email(self.value))
			jQuery(self).next().attr("value",self.value);
		else jQuery(person).addClass("unknown");
		
	}
	function share_focus(self){
		var person = jQuery(self).parents(".person:first");
		jQuery(person).removeClass("unknown");
		jQuery(person).addClass("focused");
	}
	function share_change(self){
		var person = jQuery(self).parents(".person:first");
		self.value = self.value.trim();
		if(self.value.length>0)
			jQuery(person).removeClass("blank");
		else jQuery(person).addClass("blank");
		if(is_email(self.value))
			jQuery(self).next().attr("value",self.value);
		else jQuery(person).addClass("unknown");
	}
	function share_keyup(self,event){
		var code = event.keyCode || event.which;
		if(code == '9'){}
		else{}
		var person = jQuery(self).parents(".person:first");
		if(self.value.length > 0)
			jQuery(person).removeClass("blank");
		else jQuery(person).addClass("blank");
		if(self.value.length >= 6){
			var next = jQuery(person).next();
			var content  = jQuery(next).html();
			if(typeof content == 'undefined'){
				var field = '';
				field += '<div class="person field blank">';
				field += '	<div class="autocomplete_people">';
				field += '		<label class="focused placeholder">Type a name or email address...</label>';
				field += '		<label class="blurred placeholder">Add another person...</label>';
				field += '		<input type="text" value="" data-role="human_input" data-behavior="input_change_emitter" onblur="share_blur(this)" onfocus="share_focus(this)" onchange="share_change(this)" onkeyup="share_keyup(this,event)">';
				field += '		<input type="hidden" value="" name="comment[new_subscriber_emails][]" data-role="email_address_input">';
				field += '		<div data-role="suggestions_view" class="suggestions"></div>';
				field += '	</div>';
				field += '</div>';
				jQuery(person).parent().append(field);
			}
		}
		
		if(self.value.length > 2){
			autocomplete(self);
		}
	}
	function toggle_todo(self,nid){
		var complete = 0;
		if(self.checked){
			complete = 1;
			jQuery(self).parent().addClass("complete");
		}
		else jQuery(self).parent().removeClass("complete");
		jQuery.post("<?php print $base_url;?>/ajax-calendar",{action:"update_todo",aid:nid,"field":"completed",complete:complete},function(){});
		
	}
	var ut;
	var current_time = <?php print time();?>;
	ut = setTimeout('update_info()',5000);
	function update_info(){
		clearTimeout(ut);
		jQuery.post("<?php print $base_url;?>/ajax-process?action=update_info",{"since":current_time,"project_id":<?php print $project->nid;?>},function(data){
			if(data.content.length > 0){
				jQuery("#date_display_project").html(data.content);
				jQuery(".pill .datepicker").datepicker({ dateFormat: "MM d, yy", onSelect: function(dateText,inst ){ todo_set_date(dateText)} });
				jQuery(".pill select").bind("change",function(){
					todo_set_user(this);
				});
				jQuery(".balloon").bind("click",function(event){
					event.stopPropagation();
				});
			}
			current_time = data.time;
			ut = setTimeout('update_info()',5000);
		},'json');
	}
</script>

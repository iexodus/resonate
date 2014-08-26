<?php
global $base_url, $user;
$path_theme = str_replace("project" . DS . "themes", "administration" . DS . "themes", dirname(__FILE__)) . DS;
date_default_timezone_set("Asia/Bangkok");
?>
<?php $alias = 'node/' . $project->nid; ?>
<?php
$events = 0;
if (!$with_clients || !$is_client) {
    $result = db_query("SELECT COUNT(aid) as total FROM tbl_project_attribute WHERE (type = 2 OR type = 5) AND deleted = 0 AND nid = " . $project->nid);
    foreach ($result as $object) {
        $events = $object->total;
    }
} else {
    $result = db_query("SELECT COUNT(aid) as total FROM tbl_project_attribute WHERE send_client = 1 AND (type = 2 OR type = 5) AND deleted = 0 AND nid = " . $project->nid);
    foreach ($result as $object) {
        $events = $object->total;
    }
}
$color = $project->field_color['und'][0]['value'];
list($cy,$cm,$cd) = explode('-', date('Y-m-d'));
$start_date=$cy.'-'.$cm.'-01';
        $end_week =$cy.'-'.$cm.'-31';
 $prev_date = strtotime($start_date)-86400;
         $next_date = strtotime($end_week)+86400;
?>
<div class="panel sheet in_project android_card <?php if($project->sticky==1) print 'read_only' ?>">
    <?php print frender($path_theme . 'menu-layout-mobile.tpl.php', array('project' => $project)) ?>
    <header class="calendar_nav">
        <a class="action_button next" data-behavior="selectable" data-replace-sheet="true" data-href="nid=<?=$project->nid?>&month=<?=date('m',$next_date)?>&year=<?=date('Y',$next_date)?>" onclick="get_calendar(this)">▶</a>
        <a class="action_button prev" data-behavior="selectable" data-replace-sheet="true" data-href="nid=<?=$project->nid?>&month=<?=date('m',$prev_date)?>&year=<?=date('Y',$prev_date)?>" onclick="get_calendar(this)">◀</a>
        <h2><span class="padded">Next 6 weeks</span></h2>
        <a class="action_button full_width hide_from_android hide_when_read_only" data-behavior="selectable" data-browser-support-required="true" href="<?=$base_url?>/ajax-process?action=add_event&nid=<?=$project->nid?>">Add an event</a>
    </header>
    <?php $todays = $weeks = $futures = $pasts = array(); ?>
    <?php $comments = array(); ?>
    <?php if ($events) { ?>
        <?php $todays = array(); ?>
        <?php $today = date('Y-m-d'); ?>
        <?php $result = NULL; ?>
        <?php if (!$with_clients || !$is_client) { ?>
            <?php $result = db_query("SELECT e.*,a.subject as title,a.description,a.type,a.send_client FROM tbl_events e INNER JOIN tbl_project_attribute a ON e.aid = a.aid WHERE e.calendar = " . $project->nid . " AND e.start_date <= '" . $today . "' AND e.end_date >= '" . $today . "' AND a.deleted = 0 ORDER BY e.end_date ASC"); ?>
        <?php } else { ?>
            <?php $result = db_query("SELECT e.*,a.subject as title,a.description,a.type,a.send_client FROM tbl_events e INNER JOIN tbl_project_attribute a ON e.aid = a.aid WHERE send_client = 1 AND e.calendar = " . $project->nid . " AND e.start_date <= '" . $today . "' AND e.end_date >= '" . $today . "' AND a.deleted = 0 ORDER BY e.end_date ASC"); ?>
        <?php } ?>
        <?php foreach ($result as $object) { ?>
            <?php $object->alias = 'discuss/event/' . $object->aid; ?>
            <?php $todays[] = $object; ?>
        <?php } ?>
        <?php $result = NULL; ?>
        <?php if (!$with_clients || !$is_client) { ?>
            <?php $result = db_query("SELECT a.aid, a.subject as title, a.description, a.type, l.name, a.tid, a.assign_date, a.assign_to,a.send_client,a.completed FROM tbl_project_attribute a INNER JOIN tbl_todolist l ON a.tid = l.tid WHERE a.type = 2 AND a.deleted = 0 AND a.completed = 0 AND a.assign_date = '" . $today . "' AND a.nid = " . $project->nid); ?>
        <?php } else { ?>
            <?php $result = db_query("SELECT a.aid, a.subject as title, a.description, a.type, l.name, a.tid, a.assign_date, a.assign_to,a.send_client,a.completed FROM tbl_project_attribute a INNER JOIN tbl_todolist l ON a.tid = l.tid WHERE a.send_client = 1 AND a.type = 2 AND a.deleted = 0 AND a.completed = 0 AND a.assign_date = '" . $today . "' AND a.nid = " . $project->nid); ?>
        <?php } ?>
        <?php foreach ($result as $object) { ?>

            <?php list($y, $m, $d) = explode('-', $object->assign_date); ?>
            <?php $object->start_time = mktime(0, 0, 0, $m, $d, $y); ?>
            <?php $object->end_time = mktime(0, 0, 0, $m, $d, $y); ?>
            <?php $todays[] = $object; ?>
        <?php } ?>
        <?php $weeks = array(); ?>
        <?php $tomorrow = date('Y-m-d', time() + 86400); ?>
        <?php
        $w = date('w');
        $end_week = date('Y-m-d', time() + (6 - $w) * 86400);
        ?>
        <?php $result = NULL; ?>
        <?php if (!$with_clients || !$is_client) { ?>
            <?php $result = db_query("SELECT e.*,a.subject as title,a.description,a.type,a.send_client FROM tbl_events e INNER JOIN tbl_project_attribute a ON e.aid = a.aid WHERE  e.calendar = " . $project->nid . " AND e.start_date <= '" . $end_week . "' AND e.end_date >= '" . $tomorrow . "' AND a.deleted = 0 ORDER BY e.end_date ASC"); ?>
        <?php } else { ?>
            <?php $result = db_query("SELECT e.*,a.subject as title,a.description,a.type,a.send_client FROM tbl_events e INNER JOIN tbl_project_attribute a ON e.aid = a.aid WHERE a.send_client = 1 AND e.calendar = " . $project->nid . " AND e.start_date <= '" . $end_week . "' AND e.end_date >= '" . $tomorrow . "' AND a.deleted = 0 ORDER BY e.end_date ASC"); ?>
        <?php } ?>
        <?php foreach ($result as $object) { ?>
            <?php list($y, $m, $d) = explode('-', $object->start_date); ?>
            <?php $object->start_time = mktime(0, 0, 0, $m, $d, $y); ?>
            <?php list($y, $m, $d) = explode('-', $object->end_date); ?>
            <?php $object->end_time = mktime(0, 0, 0, $m, $d, $y); ?>
            <?php $object->alias = 'discuss/event/' . $object->aid; ?>
            <?php $weeks[] = $object; ?>
        <?php } ?>
        <?php $result = NULL; ?>
        <?php if (!$with_clients || !$is_client) { ?>
            <?php $result = db_query("SELECT a.aid, a.subject as title, a.description, a.type, l.name, a.tid, a.assign_date, a.assign_to,a.send_client,a.completed FROM tbl_project_attribute a INNER JOIN tbl_todolist l ON a.tid = l.tid WHERE a.type = 2 AND a.deleted = 0 AND a.completed = 0 AND a.assign_date >= '" . $tomorrow . "' AND a.assign_date <= '" . $end_week . "' AND a.nid = " . $project->nid); ?>
        <?php } else { ?>
            <?php $result = db_query("SELECT a.aid, a.subject as title, a.description, a.type, l.name, a.tid, a.assign_date, a.assign_to,a.send_client,a.completed FROM tbl_project_attribute a INNER JOIN tbl_todolist l ON a.tid = l.tid WHERE a.send_client = 1 AND a.type = 2 AND a.deleted = 0 AND a.completed = 0 AND a.assign_date >= '" . $tomorrow . "' AND a.assign_date <= '" . $end_week . "' AND a.nid = " . $project->nid); ?>
        <?php } ?>
        <?php foreach ($result as $object) { ?>
            <?php list($y, $m, $d) = explode('-', $object->assign_date); ?>
            <?php $object->start_time = mktime(0, 0, 0, $m, $d, $y); ?>
            <?php $object->end_time = mktime(0, 0, 0, $m, $d, $y); ?>	
            <?php $weeks[] = $object; ?>
        <?php } ?>
        <?php $futures = array(); ?>
        <?php $result = NULL; ?>
        <?php if (!$with_clients || !$is_client) { ?>
            <?php $result = db_query("SELECT e.*,a.subject as title,a.description,a.type,a.send_client FROM tbl_events e INNER JOIN tbl_project_attribute a ON e.aid = a.aid WHERE e.calendar = " . $project->nid . " AND e.end_date > '" . $end_week . "' AND a.deleted = 0 ORDER BY e.end_date ASC"); ?>
        <?php } else { ?>
            <?php $result = db_query("SELECT e.*,a.subject as title,a.description,a.type,a.send_client FROM tbl_events e INNER JOIN tbl_project_attribute a ON e.aid = a.aid WHERE a.send_client = 1 AND e.calendar = " . $project->nid . " AND e.end_date > '" . $end_week . "' AND a.deleted = 0 ORDER BY e.end_date ASC"); ?>
        <?php } ?>
        <?php foreach ($result as $object) { ?>
            <?php list($y, $m, $d) = explode('-', $object->start_date); ?>
            <?php $object->start_time = mktime(0, 0, 0, $m, $d, $y); ?>
            <?php list($y, $m, $d) = explode('-', $object->end_date); ?>
            <?php $object->end_time = mktime(0, 0, 0, $m, $d, $y); ?>
            <?php $object->alias = 'discuss/event/' . $object->aid; ?>
            <?php $futures[] = $object; ?>
        <?php } ?>
        <?php $result = NULL; ?>
        <?php if (!$with_clients || !$is_client) { ?>
            <?php $result = db_query("SELECT a.aid, a.subject as title, a.description, a.type, l.name, a.tid, a.assign_date, a.assign_to,a.send_client,a.completed FROM tbl_project_attribute a INNER JOIN tbl_todolist l ON a.tid = l.tid WHERE a.type = 2 AND a.deleted = 0 AND a.completed = 0 AND a.assign_date > '" . $end_week . "' AND a.nid = " . $project->nid); ?>
        <?php } else { ?>
            <?php $result = db_query("SELECT a.aid, a.subject as title, a.description, a.type, l.name, a.tid, a.assign_date, a.assign_to,a.send_client,a.completed FROM tbl_project_attribute a INNER JOIN tbl_todolist l ON a.tid = l.tid WHERE a.send_client = 1 AND a.type = 2 AND a.deleted = 0 AND a.completed = 0 AND a.assign_date > '" . $end_week . "' AND a.nid = " . $project->nid); ?>
        <?php } ?>
        <?php foreach ($result as $object) { ?>
            <?php list($y, $m, $d) = explode('-', $object->assign_date); ?>
            <?php $object->start_time = mktime(0, 0, 0, $m, $d, $y); ?>
            <?php $object->end_time = mktime(0, 0, 0, $m, $d, $y); ?>
            <?php $futures[] = $object; ?>
        <?php } ?>
        <?php if (empty($todays) && empty($weeks) && empty($futures)) { ?>
          <div class="blank_slate">
                 <h2><br>Nothing’s coming up in the next 6 weeks.<br><br></h2>
            </div>
        <?php } ?>
        <?php if (!empty($todays)) { ?>

            <section class="calendar_events grouped_by_date">
                <h2>Today</h2>
                <article class="calendar_event">
                    <header class="date font-tiny">
                        <time class="day font-small" style="background-color: #<?php print $color; ?>"><?php print date('j'); ?></time>
                        <time class="weekday"><?php print date('l'); ?></time>
                    </header>

                    <?php
                    $have_events = 0;
                    $have_todos = 0;
                    ?>
                    <?php if ($todays[0]->type == 5) $have_events = 1; else if ($todays[0]->type == 2) $have_todos = 1; ?>
                    <?php if ($have_events) { ?>

                        <?php foreach ($todays as $event) { ?>
                            <?php if ($event->type == 5) { ?>
                                <a data-behavior="selectable" href="<?php print $base_url . '/' . $event->alias; ?>">
                                    <h3>
                                        <div style="color: #<?php print $color; ?>" class="project"><?= $project->title ?></div>
                                        <span class="title"><?php print $event->title; ?></span>
                                    </h3>
                                    <?php if ($with_clients && !$is_client && $event->send_client == 0) { ?>
                                        <span class="hidden_from_client">The client can’t see this event</span>
                                    <?php } ?>
                                </a>
                                <?php
                            } else {
                                $have_todos++;
                            }
                            ?>
                        <?php } ?>

                    <?php } ?>
                    <?php if ($have_todos) { ?>
                        <?php $number = $have_events ? $have_todos : count($todays); ?>
                        <section class="todos_for_date">
                            <header><b><?php print $number ?> To-do<?php if ($number > 1) print 's'; ?></b></header>
                            <ul class="todos">
                                <?php
                                foreach ($todays as $todo) {
                                    $todo->subject = $todo->title;
                                    if ($todo->type == 2) {
                                        print frender($path_theme . 'todolist-layout-mobile.tpl.php', array('todo' => $todo, 'project' => $project));
                                    }
                                }
                                ?>
                            </ul>
                        </section>
                    <?php } ?>
                </article>
            </section>
        <?php } ?>
        <?php $time = mktime(0, 0, 0, date('m'), date('d'), date('Y')); ?>
        <?php if (!empty($weeks)) { ?>
            <section class="calendar_events grouped_by_date">
                <h2>This week</h2>

                <?php $tomorrow = $time + 86400; ?>
                <?php $w = date('w'); ?>
                <?php $end_week = $time + (6 - $w) * 86400; ?>
                <?php
                for ($i = $tomorrow; $i <= $end_week; $i+= 86400) {
                    $output = '
            		<article class="calendar_event">
					  <header class="date font-tiny">
						  <time class="day font-small" style="background-color: #' . $color . '">' . date('j', $i) . '</time>
						   <time class="weekday">' . date('l', $i) . '</time>
					  </header>';
                    $have_todos = 0;
                    $ul_output = '';
                    $count = 0;
                    foreach ($weeks as $event) {
                        if ($event->type == 5) {
                            if ($event->start_time <= $i && $i <= $event->end_time) {
                                $count++;
                                $ul_output .= '
        					<a data-behavior="selectable" href="' . $base_url . '/' . $event->alias . '">';
                                if (!empty($event->at_time) && $event->start_date == $event->end_date) {
//                                                    $ul_output .= '
//								<time style="color: #' . $color . '" datetime="' . $event->at_time . '">' . change_time($event->at_time) . '</time>';
                                }
                                $ul_output .= '
						  		 <h3>
                                                                 <div style="color: #' . $color . '" class="project">' . $project->title . '</div><span class="title">' . $event->title . '</span>
                                                                     </h3>';
                                if ($with_clients && !$is_client && $event->send_client == 0) {
                                    $ul_output .= '
								<span class="hidden_from_client">The client can’t see this event</span>';
                                }
                                $ul_output .= '
        					</a>';
                            }
                        } else if ($event->start_time == $i)
                            $have_todos++;
                    }

                    if ($count == 0)
                        $ul_output = '';
                    if ($have_todos) {
                        $ul_output.='<section class="todos_for_date">';
                        $ul_output.='<header><b>' . $have_todos . ' To-do' . ($have_todos > 1 ? 's' : '') . '</b></header>';
                        $ul_output.='<ul class="todos">';
                        foreach ($weeks as $todo) {
                            $todo->subject = $todo->title;
                            if ($todo->type == 2 && $todo->start_time == $i) {
                                $ul_output.=frender($path_theme . 'todolist-layout-mobile.tpl.php', array('todo' => $todo, 'project' => $project));
                            }
                        }
                        $ul_output.='</ul>';
                        $ul_output.='</section>';
                        $count = $have_todos;
                    }
                    $output .= $ul_output . '
					</article>';
                    if ($count == 0)
                        $output = '';
                    print $output;
                }
                ?>
            </section>
        <?php } ?>

        <?php
        if (!empty($futures)) {
            $min_time = $futures[0]->start_time;
            $max_time = $futures[count($futures) - 1]->end_time;
            foreach ($futures as $event) {
                $start_time = $event->start_time;
                $end_time = $event->end_time;
                if ($start_time < $min_time)
                    $min_time = $start_time;
                if ($end_time > $max_time)
                    $max_time = $end_time;
            }
            if ($min_time <= $end_week)
                $min_time = $end_week + 86400;
            $m_output = '';
            $month = '';
            $m_count = 0;
            for ($i = $min_time; $i <= $max_time; $i+=86400) {
                $i_month = date('F', $i);
                if ($month != $i_month) {
                    $m_output .= '
		    	</section>';
                    if ($m_count == 0)
                        $m_output = '';
                    print $m_output;
                    $month = $i_month;
                    $m_count = 0;
                    $m_output = '
		    	<section class="calendar_events grouped_by_date">
		    		<h2>' . $month . '</h2>
		    				';
                }
                $d_output = '
		    		<article class="calendar_event">
                                 <header class="date font-tiny">
                                    <time class="day font-small" style="background-color: #' . $color . '">' . date('j', $i) . '</time>
                                     <time class="weekday">' . date('l', $i) . '</time>
                                 </header>
				';
                $have_todos = 0;
                $ul_output = '
    					<a>
                                        
                                        ';
                $d_count = 0;
                foreach ($futures as $event) {
                    if ($event->type == 5) {
                        if ($event->start_time <= $i && $event->end_time >= $i) {
                            $d_count++;
                            $m_count++;
                            $ul_output .= '<a data-behavior="selectable" href="' . $base_url . '/' . $event->alias . '">';
                            if (!empty($event->at_time) && $event->start_date == $event->end_date) {
//                                                $ul_output .= '
//									<time style="color: #' . $color . '" datetime="' . $event->at_time . '">' . change_time($event->at_time) . '</time>';
                            }
                            $ul_output .= '
                                                <h3>
                                                    <div style="color: #' . $color . '" class="project">' . $project->title . '</div>
                                                    <span class="title">' . $event->title . '</span>
                                                 </h3>';

                            if ($with_clients && !$is_client && $event->send_client == 0) {
                                $ul_output .= '
									<span class="hidden_from_client">The client can’t see this event</span>';
                            }
                            $ul_output .= '</a>';
                        }
                    } else if ($event->type == 2 && $event->start_time == $i) {
                        $have_todos++;
                    }
                }
                $ul_output .= '
		    			</a>';
                if ($d_count == 0)
                    $ul_output = '';
                if ($have_todos) {
                    $d_count = $have_todos;
                    $m_count += $have_todos;
                    if ($have_todos) {
                        $ul_output.='<section class="todos_for_date">';
                        $ul_output.='<header><b>' . $have_todos . ' To-do' . ($have_todos > 1 ? 's' : '') . '</b></header>';
                        $ul_output.='<ul class="todos">';
                        foreach ($futures as $todo) {
                            $todo->subject = $todo->title;
                            if ($todo->type == 2 && $todo->start_time == $i) {
                                $ul_output.=frender($path_theme . 'todolist-layout-mobile.tpl.php', array('todo' => $todo, 'project' => $project));
                            }
                        }
                        $ul_output.='</ul>';
                        $ul_output.='</section>';
                        $count = $have_todos;
                    }
                    $ul_output .= '
		    			</ul>';
                }
                $d_output .= $ul_output . '
		    		</article>';
                if ($d_count == 0)
                    $d_output = '';
                $m_output .= $d_output;
            }
            $m_output .= '</section>';
            if ($m_count == 0)
                $m_output = '';
            print $m_output;
        }
        ?>
    <?php } ?>
</div>

<script>
    jQuery("document").ready(function(){
        todo_click();
    });
     function get_calendar(me){
      var link =jQuery(me).data("href");
        jQuery.get("<?=$base_url?>/calendar-events?"+link, {'action':'filter'},function (data){
            jQuery(".panel.sheet").replaceWith(data);
        });
    }
</script>
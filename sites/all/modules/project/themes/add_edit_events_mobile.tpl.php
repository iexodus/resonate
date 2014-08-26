<?php
global $base_url;
if(isset($event))
    $nid =$event->nid;
else 
    $nid = $_GET['nid'];
$project_info = get_info_project($nid, 'members');
$project = $project_info['project'];
$members = $project_info['members_all'];
$teams = $project_info['members_all_team']; 
$path_theme = str_replace("project" . DS . "themes", "administration" . DS . "themes", dirname(__FILE__)) . DS;
$property_show_client = get_property_show_client('project', $project->nid);
$recurrence = isset($_GET['recurrence'])?$_GET['recurrence']:0;
$at_time = 'Any time';
if (isset($event->at_time) && !empty($event->at_time)) {
    list($h, $m, $s) = explode(":", $event->at_time);
    if ($h < 13)
        $at_time = $h . ':' . $m . 'am';
    else
        $at_time = ($h - 12) . ':' . $m . 'pm';
}
?>
<div class="panel sheet">
    <?php
    if ($project->field_with_clients['und'][0]['value'] == 1) {
        if (isset($property_show_client->client) && $property_show_client->client == 0 ) {
            print frender($path_theme . 'show-client-layout-mobile.tpl.php', array('status_client' => (isset($event->send_client) ? $event->send_client : 1), 'text' => 'this event'));
        }
    }
    ?>
    <header class="sheet_header hide_from_android">
        <span class="hide_from_ios"><a class="parent hide_from_ios hide_from_android" data-behavior="selectable" href="<?= $base_url ?>/node/<?= $project->nid ?>">From the project: <?= $project->title ?>  </a>
            <a class="parent for_ios hide_from_android" data-behavior="selectable" data-replace-sheet="true" href="<?= $base_url ?>/node/<?= $project->nid ?>">From the project: <?= $project->title ?></a>
            <span class="parent for_android hide_from_ios">From the project: <?= $project->title ?></span>
        </span>
        <h1><?=isset($event->aid)?'Edit this event':'Add a new event'?></h1>
    </header>
    <article class="calendar_event form" data-behavior="calendar_event_form">
        <form accept-charset="UTF-8" action="<?php print $base_url; ?>/<?= isset($event->aid) ? 'ajax-calendar?action=update_event' : 'ajax-process?action=add_event' ?>" class="new_calendar_event all_day" data-behavior="calendar_event_form" data-remove-sheet="true" id="new_calendar_event" method="post">
            <input type="hidden" name="bucket" value="<?= $project->nid ?>">
<!--            <input id="calendar_event_all_day" name="calendar_event[all_day]" type="hidden" value="false">-->
            <fieldset class="name_fields">
                <p>
                    <label class="hide_from_ios" for="summary">Event title:</label>
                    <input data-behavior="autosave autofocus_android" id="calendar_event_summary" name="summary" placeholder="Name the event..." type="text" value="<?= isset($event->subject) ? $event->subject : '' ?>">
                </p>

                <p>
                    <label class="hide_from_ios" for="description">Event description:</label>
                    <textarea data-behavior="autosave" id="calendar_event_description" name="description" placeholder="Add an optional note..." rows="3"><?= isset($event->description) ? $event->description : '' ?></textarea>
                </p>
            </fieldset>

            <fieldset class="timed" data-role="timed_or_spanned">
                <p class="custom_selects time" data-behavior="set_time"  style="<?php if (isset($event) && ($event->start_date != $event->end_date)) print 'display:none;'; ?>">
                    <label for="starts_at_time">Time:</label>
                    <a class="action_button" data-behavior="action_button" href="#"><?= $at_time ?></a>
                    <input data-behavior="time_picker focusable_field starts_at_time" data-datetime="" id="calendar_event_starts_at_time" name="starts_at_time" type="time" value="<?= isset($event->at_time) ? $event->at_time : '' ?>" onchange="set_assign_select(this,'time_assign')">
                    <input type="hidden" name="starts_at_time" id="starts_at_time_temp">
                </p>

                <p class="custom_selects" data-behavior="set_date">
                    <label for="starts_at">Starts:</label>
                    <a class="action_button" data-behavior="action_button"><?= isset($event->start_date) ? date('D, M d, Y', $event->start_date) : date('D, M d, Y', time()) ?></a>
                    <input data-behavior="date_picker starts_at_date focusable_field" id="calendar_event_starts_at" name="starts_at_date" type="date" value="<?= isset($event->start_date) ? date('Y-m-d', $event->start_date) : date('Y-m-d', time()) ?>" onchange="set_assign_select(this,'starts_date')">
                </p>

                <p class="custom_selects" data-behavior="set_date">
                    <label for="ends_at">Ends:</label>
                    <a class="action_button" data-behavior="action_button"><?= isset($event->end_date) ? date('D, M d, Y', $event->end_date) : date('D, M d, Y', time()) ?></a>
                    <input data-behavior="date_picker ends_at_date focusable_field" id="calendar_event_ends_at" name="ends_at_date" type="date" value="<?= isset($event->end_date) ? date('Y-m-d', $event->end_date) : date('Y-m-d', time()) ?>" onchange="set_assign_select(this,'ends_date')">
                </p>
            </fieldset>
            <?php $repeats = array('daily' => 'Every day', 'weekly' => 'Every week', 'monthly' => 'Every month', 'yearly' => 'Every year') ?>
            <fieldset class="recurrences" data-behavior="expandable">
                <p class="custom_selects set_recurrence" data-behavior="set_recurrence">
                    <label for="freq">Repeats?</label>
                    <a class="action_button" data-behavior="recurrence_button"><?= (isset($event->at_repeat) && !empty($event->at_repeat)) ? $repeats[$event->at_repeat] : 'No' ?></a>
                    <select data-behavior="set_recurrence_freq focusable_field" id="calendar_event_freq" name="freq" onchange="set_assign_select(this,'repeat_day')">
                        <option value="">No</option>
                        <option value="daily"<?php if (isset($event) && $event->at_repeat == 'daily') print ' selected=""'; ?>>Every day</option>
                        <option value="weekly"<?php if (isset($event) && $event->at_repeat == 'weekly') print ' selected=""'; ?>>Every week</option>
                        <option value="monthly"<?php if (isset($event) && $event->at_repeat == 'monthly') print ' selected=""'; ?>>Every month</option>
                        <option value="yearly"<?php if (isset($event) && $event->at_repeat == 'yearly') print ' selected=""'; ?>>Every year</option>
                    </select>
                </p>

                <div class="recurrence_options expanded_content">
                    <!--                    <label>
                                            <input checked="checked" id="calendar_event_recurrence_end_forever" name="event_recurrence_end" type="radio" value="forever">
                                            <span>Forever</span>
                                        </label>-->
                    <label>
                        <input id="calendar_event_recurrence_end_count" name="event_recurrence_end" type="radio" value="count" checked="">
                        <span>For</span>
                        <p class="custom_selects" data-behavior="set_count">
                            <a class="action_button" data-behavior="action_button" href="#">
                                <span data-role="counttext">2</span> <span data-role="freqtext">days</span>
                            </a>          
                            <select data-behavior="count_picker focusable_field" id="calendar_event_count" name="count" onchange="set_assign_select(this,'count_picker')">
                                <?php for ($i = 2; $i <= 30; $i++) { ?>
                                    <option value="<?php print $i; ?>"<?php if (isset($event) && $event->at_for == $i) print ' selected=""'; ?>><?php print $i; ?></option>
                                <?php } ?>
                            </select>
                        </p>
                    </label>
                    <label>
                        <input id="calendar_event_recurrence_end_until" name="event_recurrence_end" type="radio" value="until">
                        <span>Until</span>
                        <p class="custom_selects" data-behavior="set_date">
                            <a class="action_button" data-behavior="action_button"><?= isset($event->at_until) ? date('D, M d, Y', strtotime($event->at_until)) : date('D, M d, Y', time()) ?></a>
                            <input data-behavior="date_picker focusable_field" id="calendar_event_until_date" name="until_date" type="date" value="<?= isset($event->at_until) ? $event->at_until : date('Y-m-d', time()) ?>" onchange="set_assign_select(this,'until_date')">
                        </p>
                    </label>
                </div>
            </fieldset>

            <fieldset class="subscribers">
                <?php
                if (isset($_SESSION['notified']['newest'][$project->nid])) {
                    $expired = (23 * 60 * 60 + $_SESSION['notified']['newest'][$project->nid]['session_start']);
                    if ($expired < time())
                        unset($_SESSION['notified']['newest'][$project->nid]);
                }
                $session = isset($_SESSION['notified']['newest'][$project->nid]) ? $_SESSION['notified']['newest'][$project->nid] : array();
                if(isset($event->send_client)&&$event->send_client==0)
                    $members =$teams;
                print frender($path_theme . 'send-email-layout-mobile.tpl.php', array('members' => $members, 'session' => $session, 'event_form_send_mail' => 1));
                ?>
            </fieldset>

            <fieldset class="reminders" style="">
                <div class="reminder_buttons">
                    <input type="hidden" name="wants_notification" value="0">
                    <label><input type="checkbox" name="wants_notification" value="1"> Send other people an email now and a reminder at 7am the day of the event </label>
                </div>
                <!--                <div class="reminder_buttons">
                                    <p class="custom_selects reminder_day" data-behavior="set_remind_at_offset">
                                        <a class="action_button" data-behavior="action_button" href="#">Day of the event at...</a>
                                        <input type="hidden" name="wants_notification" value="1">
                                        <select class="all_day_reminder_offset" data-behavior="focusable_field" id="remind_at_offset" name="wants_notification"><option value="false">None</option>
                                            <option value="0d">Day of the event at...</option>
                                            <option value="-1d">1 day before at...</option>
                                            <option value="-2d">2 days before at...</option>
                                        </select>
                
                                        <select class="timed_reminder_offset" data-behavior="focusable_field" id="remind_at_offset" name="wants_notification"><option value="false">None</option>
                                            <option  value="-30m">30 minutes before</option>
                                            <option value="-60m">1 hour before</option>
                                            <option value="0d">Day of the event at...</option>
                                            <option value="-1d">1 day before at...</option>
                                            <option value="-2d">2 days before at...</option>
                                        </select>
                                    </p>
                
                                    <p class="custom_selects reminder_time" data-behavior="set_time" data-role="reminder_time">
                                        <a class="action_button" data-behavior="action_button" href="#">7am</a>
                                        <input data-behavior="time_picker focusable_field" data-datetime="2014-04-18T00:00:00Z" id="calendar_event_remind_at" name="calendar_event[remind_at]" type="time">
                                    </p>
                                </div>-->
            </fieldset>

            <footer>
                <div class="submit hide_from_android">

                    <input class="action_button default_action" data-behavior="selectable" name="commit" type="submit" value="<?= isset($event->aid) ? 'Save changes' : 'Add this event' ?>">

                </div>
            </footer>

            <?php if (isset($event->aid)) { ?>
                <p class="hide_from_android"><a class="action_button hide_from_ios" data-behavior="selectable" href="<?= $base_url ?>/discuss/event/<?= $event->aid ?>">Cancel changes</a></p>
                <input type="hidden" name="details" value="1"/>
                <input type="hidden" name="order" value="<?php print $recurrence; ?>"/>
                <input type="hidden" name="last" value="<?php print $last; ?>"/>
                <input type="hidden" name="event" value="<?php if ($event) print $event->aid; ?>"/>
            <?php } ?>
        </form>
    </article>
</div>

<script src="<?php print $base_url; ?>/sites/all/libraries/date.format.js"></script>
<script>
    function set_assign_select(me,flag){
        switch(flag){
            case 'time_assign':
                var value = jQuery(me).val();
                var times = value.split(":");
                if(times[0]<13)
                    value = times[0]+':'+times[1]+'am';
                else 
                    value = (times[0]-12)+':'+times[1]+'pm';
                jQuery(me).prev().html(value);
                jQuery("#starts_at_time_temp").attr("value",value);
                break;
            case 'starts_date':
                var value = jQuery(me).val();
                var format_date = dateFormat(value, "ddd, mmm d, yyyy");//Mon, Apr 28, 2014
                jQuery(me).prev().html(format_date);
                jQuery("#calendar_event_ends_at").attr("value",value).prev().html(format_date);
                break;
            case 'ends_date':
                var value = jQuery(me).val();
                var format_date = dateFormat(value, "ddd, mmm d, yyyy");//Mon, Apr 28, 2014
                jQuery(me).prev().html(format_date);
                var start_date = jQuery("#calendar_event_starts_at").val();
                if(value==start_date){
                    jQuery(".custom_selects.time").show();
                } else {
                    if(new Date(value) <= new Date(start_date))
                    {
                        var format_sdate = dateFormat(start_date, "ddd, mmm d, yyyy");//Mon, Apr 28, 2014
                        jQuery(me).attr("value", start_date).prev().html(format_sdate);
                    }
                    jQuery(".custom_selects.time").hide();
                    jQuery("#calendar_event_starts_at_time").attr("value","").prev().html("Any time");;
                    jQuery("#starts_at_time_temp").attr("value","");
                }
                break;
            case 'repeat_day':
                var value = jQuery(me).val();
                var text = jQuery("#calendar_event_freq option:selected").html();
                jQuery(me).prev().html(text);
                if(value.length==0)
                    jQuery(me).closest(".recurrences").removeClass("expanded");
                else 
                    jQuery(me).closest(".recurrences").addClass("expanded");
                    
                break;
            case 'count_picker':
                var value = jQuery(me).val();
                jQuery(me).prev().children().first().html(value);
                break;
            case 'event_recurrence_end':
                var value = jQuery(me).val();
                var format_date = dateFormat(value, "ddd, mmm d, yyyy");//Mon, Apr 28, 2014
                jQuery(me).prev().html(format_date);
                break;
            case 'until_date':
                var value = jQuery(me).val();
                var format_date = dateFormat(value, "ddd, mmm d, yyyy");//Mon, Apr 28, 2014
                jQuery(me).prev().html(format_date);
                break;
        }
                   
    }
</script>



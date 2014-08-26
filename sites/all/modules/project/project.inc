<?php

date_default_timezone_set("Asia/Bangkok");

function clear_caches() {
//    include_once('./includes/bootstrap.inc');
//    drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
//    drupal_flush_all_caches();
}

function projects($page = true) {
    clear_caches();
    global $base_url, $user, $msw_detect;
    $project_view = isset($_SESSION['project_view']) ? $_SESSION['project_view'] : 'cards';
    $cards = $starred = $list = $archived = $updates = array();
    $authors = array();
    $result = db_query("SELECT nid FROM tbl_assign_project WHERE uid = $user->uid");
    $nids = array(0);
    foreach ($result as $object)
        $nids[] = $object->nid;
    $result = NULL;
    if ($project_view == 'cards') {
        if(isset($_GET['places'])){
            $result = db_query("SELECT n.nid,n.promote as star,n.sticky as active FROM node n WHERE n.type='project' AND n.sticky = 0 AND (n.status = 1 OR (n.status = 0 AND n.nid IN (" . implode(",",$nids) . "))) ORDER BY n.promote desc,n.created asc"); 
        }else {
            if ($user->uid == 1)
            $result = db_query("SELECT n.nid,n.promote as star,n.sticky as active FROM node n  WHERE n.type='project' AND n.sticky = 0 AND (n.status = 1 OR (n.status = 0 AND n.uid = " . $user->uid . ")) ORDER BY n.promote desc,n.created asc");
        else
            $result = db_query("SELECT n.nid,n.promote as star,n.sticky as active FROM node n  WHERE n.type='project' AND n.sticky = 0 AND (n.status = 1 OR (n.status = 0 AND n.uid = " . $user->uid . ")) AND n.nid IN (" . implode(',', $nids) . ") ORDER BY n.promote desc,n.created asc");
        }
        foreach ($result as $object) {
            $project = node_load($object->nid);
            $project->alias = 'node/' . $object->nid;
            $project->star = $object->star;
            $project->active = $object->active;
            $project->assigned_user = array();
            $user_assign = db_query("SELECT * FROM tbl_assign_project WHERE nid = $project->nid");
            foreach ($user_assign as $user_info) {
                $user_info = user_load($user_info->uid);
                $project->assigned_user[$user_info->uid] = $user_info;
            }
            $cards[] = $project;
        }
    } else if ($project_view == 'update') {
        if ($user->uid == 1)
            $result = db_query("SELECT n.nid,n.promote as star,n.sticky as active FROM node n WHERE n.type='project' AND n.sticky = 0 AND (n.status = 1 OR (n.status = 0 AND n.uid = ".$user->uid.")) ORDER BY n.promote desc,n.created asc");
        else
            $result = db_query("SELECT n.nid,n.promote as star,n.sticky as active FROM node n WHERE n.type='project' AND n.sticky = 0 AND (n.status = 1 OR (n.status = 0 AND n.uid = ".$user->uid.")) AND n.nid IN (" . implode(',', $nids) . ") ORDER BY n.promote desc,n.created asc");
        foreach ($result as $object) {
            $project = node_load($object->nid);
            $project->alias = 'node/' . $object->nid;
            $project->star = $object->star;
            $project->active = $object->active;
            $project->assigned_user = array();
            $user_assign = db_query("SELECT * FROM tbl_assign_project WHERE nid = $project->nid");
            foreach ($user_assign as $user_info) {
                $user_info = user_load($user_info->uid);
                $project->assigned_user[] = $user_info;
            }
            $project->updates = array();
            $res = db_query("SELECT * FROM tbl_history WHERE nid = " . $project->nid . " ORDER BY created  DESC LIMIT 0,3");
            foreach ($res as $obj) {
                if (!isset($authors[$obj->uid])) {
                    $authors[$obj->uid] = user_load($obj->uid);
                    $authors[$obj->uid]->full_name = 'You';
                    if ($obj->uid != $user->uid) {
                        $authors[$obj->uid]->full_name = '';
                        if (isset($authors[$obj->uid]->field_profile_fname['und'][0]['value']))
                            $authors[$obj->uid]->full_name .= $authors[$obj->uid]->field_profile_fname['und'][0]['value'] . ' ';
                        if (isset($authors[$obj->uid]->field_profile_lname['und'][0]['value']))
                            $authors[$obj->uid]->full_name .= $authors[$obj->uid]->field_profile_lname['und'][0]['value'];
                        $authors[$obj->uid]->full_name = trim($authors[$obj->uid]->full_name);
                        if (empty($authors[$obj->uid]->full_name))
                            $authors[$obj->uid]->full_name = $authors[$obj->uid]->mail;
                    }
                }
                $project->updates[] = $obj;
            }
            $updates[] = $project;
        }
    }
    else {
        if ($user->uid == 1)
            $result = db_query("SELECT n.nid,n.promote as star,n.sticky as active FROM node n WHERE n.type='project' AND n.sticky = 0 AND (n.status = 1 OR (n.status = 0 AND n.uid = " . $user->uid . ")) AND n.promote = 1 ORDER BY n.created asc");
        else
            $result = db_query("SELECT n.nid,n.promote as star,n.sticky as active FROM node n WHERE n.type='project' AND n.sticky = 0 AND (n.status = 1 OR (n.status = 0 AND n.uid = " . $user->uid . ")) AND n.promote = 1 AND n.nid IN (" . implode(',', $nids) . ") ORDER BY n.created asc");
        foreach ($result as $object) {
            $project = node_load($object->nid);
            $project->alias = 'node/' . $object->nid;
            $project->star = $object->star;
            $project->active = $object->active;
            $project->assigned_user = array();
            $user_assign = db_query("SELECT * FROM tbl_assign_project WHERE nid = $project->nid");
            foreach ($user_assign as $user_info) {
                $user_info = user_load($user_info->uid);
                $project->assigned_user[] = $user_info;
            }
            $starred[] = $project;
        }
        $result = NULL;
        if ($user->uid == 1)
            $result = db_query("SELECT n.nid,n.promote as star,n.sticky as active FROM node n WHERE n.type='project' AND n.sticky = 0 AND (n.status = 1 OR (n.status = 0 AND n.uid = " . $user->uid . ")) ORDER BY n.title asc,n.promote desc");
        else
            $result = db_query("SELECT n.nid,n.promote as star,n.sticky as active FROM node n WHERE n.type='project' AND n.sticky = 0 AND (n.status = 1 OR (n.status = 0 AND n.uid = " . $user->uid . ")) AND n.nid IN (" . implode(',', $nids) . ") ORDER BY n.title asc,n.promote desc");
        foreach ($result as $object) {
            $project = node_load($object->nid);
            $project->alias = 'node/' . $object->nid;
            $project->star = $object->star;
            $project->active = $object->active;
            $project->assigned_user = array();
            $user_assign = db_query("SELECT * FROM tbl_assign_project WHERE nid = $project->nid");
            foreach ($user_assign as $user_info) {
                $user_info = user_load($user_info->uid);
                $project->assigned_user[] = $user_info;
            }
            $list[] = $project;
        }
    }
    $result = NULL;
    if ($user->uid == 1)
        $result = db_query("SELECT n.nid,n.promote as star,n.sticky as active FROM node n WHERE n.type='project' AND n.sticky = 1 AND (n.status = 1 OR (n.status = 0 AND n.uid = " . $user->uid . ")) ORDER BY n.title asc,n.promote desc");
    else
        $result = db_query("SELECT n.nid,n.promote as star,n.sticky as active FROM node n WHERE n.type='project' AND n.sticky = 1 AND (n.status = 1 OR (n.status = 0 AND n.uid = " . $user->uid . ")) AND n.nid IN (" . implode(',', $nids) . ") ORDER BY n.title asc,n.promote desc");
    foreach ($result as $object) {
        $project = node_load($object->nid);
        $project->alias = 'node/' . $object->nid;
        $project->star = $object->star;
        $project->active = $object->active;
        $project->assigned_user = array();
        $user_assign = db_query("SELECT * FROM tbl_assign_project WHERE nid = $project->nid");
        foreach ($user_assign as $user_info) {
            $user_info = user_load($user_info->uid);
            $project->assigned_user[] = $user_info;
        }
        $archived[] = $project;
    }
    /*
      $query = new EntityFieldQuery();
      $query->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', 'article')
      ->propertyCondition('status', 1)
      ->fieldCondition('field_news_types', 'value', 'spotlight', '=')
      ->fieldCondition('field_photo', 'fid', 'NULL', '!=')
      ->fieldCondition('field_faculty_tag', 'tid', $value)
      ->fieldCondition('field_news_publishdate', 'value', $year. '%', 'like')
      ->fieldOrderBy('field_photo', 'fid', 'DESC')
      ->range(0, 10)
      ->addMetaData('account', user_load(1));
      $result = $query->execute();
      if (isset($result['node'])) {
      $news_items_nids = array_keys($result['node']);
      $news_items = entity_load('node', $news_items_nids);
      }
     */

    if ($msw_detect->isMobile()) {
        $path_theme = dirname(__FILE__) . DS . 'themes' . DS;
        return frender($path_theme . 'projects_mobile.tpl.php', array('project_view' => $project_view, 'cards' => $cards, 'starred' => $starred, 'list' => $list, 'archived' => $archived, 'updates' => $updates, 'authors' => $authors, 'page' => $page));
    } else {
        if(isset($_GET['from_workspace']))
            print theme('projects', array('project_view' => $project_view, 'cards' => $cards, 'starred' => $starred, 'list' => $list, 'archived' => $archived, 'updates' => $updates, 
                'authors' => $authors, 'page' => $page));
        else 
        return theme('projects', array('project_view' => $project_view, 'cards' => $cards, 'starred' => $starred, 'list' => $list, 'archived' => $archived, 'updates' => $updates, 
            'authors' => $authors, 'page' => $page));
    }
}
function projects_staff_picks(){
    global $user;
  
   $result = db_query("SELECT tbl.nid FROM tbl_assign_project tbl INNER JOIN field_data_field_project_staff_picks fp ON tbl.nid = fp.entity_id WHERE fp.field_project_staff_picks_value=1");
    $nids = array(0);
    foreach ($result as $object)
        $nids[] = $object->nid;
    $result = NULL;
            $result = db_query("SELECT n.nid,n.promote as star,n.sticky as active FROM node n  WHERE n.type='project' AND n.sticky = 0 AND n.status = 1 AND n.nid IN (" . implode(',', $nids) . ") ORDER BY n.promote desc,n.created asc");
        foreach ($result as $object) {
            $project = node_load($object->nid);
            $project->alias = 'node/' . $object->nid;
            $project->star = $object->star;
            $project->active = $object->active;
            $project->assigned_user = array();
            $user_assign = db_query("SELECT * FROM tbl_assign_project WHERE nid = $project->nid");
            foreach ($user_assign as $user_info) {
                $user_info = user_load($user_info->uid);
                $project->assigned_user[$user_info->uid] = $user_info;
            }
            $cards[] = $project;
        }
         if(isset($_GET['from_workspace']))
         print theme('project_all', array('cards' => $cards, 'starred' => '', 'list' => '', 'archived' => '', 'updates' => '','authors' => ''));
         else 
         return theme('project_all', array('cards' => $cards, 'starred' => '', 'list' => '', 'archived' => '', 'updates' => '','authors' => ''));    
}
function projects_get_new() {
    global $user;
    $result = db_query("SELECT tbl.nid FROM tbl_assign_project tbl WHERE uid = $user->uid");
    $nids = array(0);
    foreach ($result as $object)
        $nids[] = $object->nid;
    $result = NULL;
    $time_current = time();
    $day_current = date('w', time());
    $begin_week =  $time_current - (24 * 3600 * $day_current);
    $end_week =  $time_current + (24 * 3600 * (6 - $day_current));
    $results = db_query("SELECT n.nid,n.promote as star,n.sticky as active FROM node n  WHERE n.type='project' AND n.sticky = 0 AND (n.status = 1 OR (n.status = 0 AND n.uid = " . $user->uid . ")) AND n.nid IN (" . implode(',', $nids) . ")  
                AND  $begin_week <= n.created AND n.created<=$end_week ORDER BY n.promote desc,n.created asc");
    foreach ($results as $object) {
        $project = node_load($object->nid);
        $project->alias = 'node/' . $object->nid;
        $project->star = $object->star;
        $project->active = $object->active;
        $project->assigned_user = array();
        $user_assign = db_query("SELECT * FROM tbl_assign_project WHERE nid = $project->nid");
        foreach ($user_assign as $user_info) {
            $user_info = user_load($user_info->uid);
            $project->assigned_user[$user_info->uid] = $user_info;
        }
        $cards[] = $project;
    }
    if (isset($_GET['from_workspace']))
        print theme('project_all', array('cards' => $cards, 'starred' => '', 'list' => '', 'archived' => '', 'updates' => '', 'authors' => ''));
    else
        return theme('project_all', array('cards' => $cards, 'starred' => '', 'list' => '', 'archived' => '', 'updates' => '', 'authors' => ''));
}

function templates($page = true) {
    global $base_url, $user;
    $templates = array();
    if ($user->uid == 2)
        $result = db_query("SELECT n.nid FROM node n WHERE n.type='template' AND n.status <> -1 ORDER BY n.created asc");
    else {
        $result = db_query("SELECT n.nid FROM node n INNER JOIN tbl_assign_project a ON n.nid = a.nid WHERE n.type='template' AND n.status <> -1 AND a.uid = $user->uid ORDER BY n.created asc");
    }

    foreach ($result as $object) {
        $template = node_load($object->nid);
        $template->alias = 'node/' . $object->nid;
        $user_assign = db_query("SELECT * FROM tbl_assign_project WHERE nid = $template->nid");
        foreach ($user_assign as $user_info) {
            $user_info = user_load($user_info->uid);
            $template->assigned_user[] = $user_info;
        }
        $result = db_query("SELECT type,COUNT(aid) as total FROM tbl_project_attribute WHERE nid = " . $template->nid . " GROUP BY type");
        $template->discussions = 'no';
        $template->todos = 'no';
        $template->files = 'no';
        $template->documents = 'no';
        $template->emails = 'no';
        $template->dates = 'no';
        $template->times = 'no';
        foreach ($result as $object) {
            if ($object->type == 1)
                $template->discussions = $object->total;
            else if ($object->type == 2)
                $template->todos = $object->total;
            else if ($object->type == 3)
                $template->documents = $object->total;
            else if ($object->type == 4)
                $template->files = $object->total;
            else if ($object->type == 5)
                $template->dates = $object->total;
        }
        $result = db_query("SELECT COUNT(id) as total FROM tbl_todo_time WHERE nid = " . $template->nid);
        foreach ($result as $object)
            $template->times = $object->total;
        if (!$template->times)
            $template->times = 'no';
        $templates[] = $template;
    }
    return theme('templates', array('templates' => $templates));
}

function ajax_process() {
    global $base_url, $user, $msw_detect;
    $path_theme = dirname(__FILE__) . DS . 'themes' . DS;
    $action = $_REQUEST['action'];
    switch ($action) {
        case 'load_content':
            print load_content($_REQUEST);
            break;
        case 'add_project':
            print add_project($_REQUEST,$_FILES);
            break;
        case 'add_template':
            print add_template();
            break;
        case 'remove_node':
            node_delete($_REQUEST['nid']);
            $check_mobile = 0;
            if ($msw_detect->isMobile())
                $check_mobile = 1;
            print json_encode(array('error' => 0, 'check_mobile' => $check_mobile));
            break;
        case 'delete_node':
            $node = node_load($_REQUEST['nid']);
            $node->status = -1;
            $node->changed = time();
            node_save($node);
            db_insert("tbl_history")->fields(array(
                'nid' => $node->nid,
                'uid' => $user->uid,
                'title' => $node->title,
                'content' => 'deleted the ' . $node->type,
                'link' => 'node/' . $node->nid,
                'action' => 1,
                'created' => time()
            ))->execute();
            if ($node->type == 'template')
                print json_encode(array('error' => 0));
            else if ($node->type == 'project') {
                if ($msw_detect->isMobile())
                    print json_encode(array('error' => 0, 'url' => $base_url . '/progress'));
                else
                    print json_encode(array('error' => 0, 'url' => $base_url . '/projects', 'content' => projects(true)));
            }
            break;
        case 'update_node':
            $node = node_load($_REQUEST['nid']);
            if ($_REQUEST['attr'] == 'active') {
                if ($_REQUEST['project']['archived'] == 'true')
                    $node->sticky = 1;
                else
                    $node->sticky = 0;
                node_save($node);
                print json_encode(array('content' => drupal_render(node_view($node))));
            }
            else if ($_REQUEST['attr'] == 'with_clients') {
                $node->field_with_clients = array('und' => array(0 => array('value' => $_REQUEST['value'])));
                node_save($node);
            } else if ($_REQUEST['attr'] == 'restore') {
                $node->status = 1;
                $node->changed = time();
                node_save($node);
                db_insert("tbl_history")->fields(array(
                    'nid' => $node->nid,
                    'uid' => $user->uid,
                    'title' => $node->title,
                    'content' => 'restored the ' . $node->type,
                    'link' => 'node/' . $node->nid,
                    'action' => 0,
                    'created' => time()
                ))->execute();
                print json_encode(array('error' => 0));
            }
            break;
        case 'publish_node':
            $node = node_load($_REQUEST['nid']);
            $node->status = 1;
            node_save($node);
            print json_encode(array('error' => 0, 'content' => drupal_render(node_view($node))));
            break;
        case 'project_star':
            $node = node_load($_REQUEST['nid']);
            $node->promote = $_REQUEST['star'];
            node_save($node);
            print load_content(array('content' => 'projects', 'page' => false));
            break;
        case 'get_suggested_users':
            print get_suggested_users($_REQUEST);
            break;
        case 'get_suggested_users_mobile':
            print get_suggested_users_mobile($_REQUEST);
            break;
        case 'add_event':
            if ($msw_detect->isMobile()) {
                if (isset($_REQUEST['bucket'])) {
                    $_REQUEST['private'] = $_REQUEST['private_client'];
                    $_REQUEST['starts_at_date'] = date('F d, Y', strtotime($_REQUEST['starts_at_date']));
                    $_REQUEST['ends_at_date'] = date('F d, Y', strtotime($_REQUEST['ends_at_date']));
                    $_REQUEST['until_date'] = date('F d, Y', strtotime($_REQUEST['until_date']));
                     $_REQUEST['private'] = $_REQUEST['private_client'];
                    $_REQUEST['calendar_event']['subscribers'] = array();
                    $_REQUEST['comment']['new_subscriber_emails'] = array();
                    if (isset($_REQUEST['send_mail']) == 'choose') {
                        foreach ($_REQUEST['send_choose'] as $uid => $mail) {
                            $_REQUEST['calendar_event']['subscribers'][] = $uid;
                        }
                    } else {
                        foreach ($_REQUEST['default_send'] as $uid => $mail) {
                            $_REQUEST['calendar_event']['subscribers'][] = $uid;
                        }
                    }
                    if (isset($_POST['email_expand'])) {
                        foreach ($_POST['email_expand'] as $email) {
                            $_REQUEST['comment']['new_subscriber_emails'][] = $email;
                        }
                    }
                    unset($_REQUEST['default_send']);
                    unset($_REQUEST['send_choose']);
                    unset($_REQUEST['email_expand']);
                    $aid = add_project_event($_REQUEST);
                    if ($aid > 0)
                        drupal_goto("discuss/event/$aid");
                }
                $nid = isset($_GET['nid']) ? $_GET['nid'] : 0;
                return frender($path_theme . 'add_edit_events_mobile.tpl.php', array('nid' => $nid));
            } else {
                print add_project_event($_REQUEST);
            }
            break;
        case 'invite_members':
            print invite_members($_REQUEST);
            break;
        case 'invite_clients':
            print invite_clients($_REQUEST);
            break;
        case 'remove_member':
            print remove_member($_REQUEST);
            break;
        case 'send_invitation':
            print send_invitation($_REQUEST);
            break;
        case 'update_info':
            print update_date_info($_REQUEST);
            break;
        case 'upload':
            print upload_cloud_file($_REQUEST);
            break;
        case 'invite_members_client':
            invite_members_clients($_REQUEST);
            break;
        case 'autocomplete':
             $result =array('content'=>'','value_exist'=>'');
                $value = isset($_GET['value']) ? trim($_GET['value']) : '';
                $values = explode(",", $value);
                $value_full = $values;
                array_pop($values);
                $where = "";
                $string = "";
                $value_end = trim(end($value_full));
                if (!empty($value_end)) {
                    foreach ($values as $name) {
                        $where.=" AND name!='" . $name . "'";
                    }
                    $results = db_query("SELECT * FROM taxonomy_term_data WHERE vid = 7 AND name like '%" . $value_end . "%' $where");
                    foreach ($results as $info) {
                        $string.='<li onclick="select_suggest_info(this)" data-name="' . $info->name . '" data-tid="' . $info->tid . '">' . $info->name . '</li>';
                    }
                    $result['content'] = $string;
                    $result['value_exist'] = implode(", ", $values);
                }
                print json_encode($result);
                        break;
                        case 'set_favorites':
                            $nid = isset($_POST['nid'])?$_POST['nid']:0;
                            $status = isset($_POST['status'])?$_POST['status']:-1;
                            if($status>-1){
                             $node = node_load($nid);
                            $node->field_project_staff_picks =array('und'=>array(0=>array('value'=>$status)));
                            node_save($node);
                            }
                            print $node->nid;
                            break;
                }
}

function update_date_info($vars) {
    date_default_timezone_set("Asia/Bangkok");
    $project = node_load($vars['project_id']);
    $since = $vars['since'];
    $have_changes = false;
    $result = db_query("SELECT aid FROM tbl_project_attribute WHERE nid = " . $project->nid . " AND (type = 5 OR type = 2) AND (created > " . $since . " OR modified > " . $since . ") LIMIT 0,1");
    foreach ($result as $object)
        $have_changes = true;
    $output = '';
    if ($have_changes) {
        $project->alias = 'node/' . $project->nid;
        $events = 0;
        $result = db_query("SELECT COUNT(aid) as total FROM tbl_project_attribute WHERE (type = 2 OR type = 5) AND nid = " . $project->nid);
        foreach ($result as $object) {
            $events = $object->total;
        }
        $users = array();
        $result = db_query("SELECT uid FROM tbl_assign_project WHERE nid = " . $project->nid);
        foreach ($result as $object) {
            $member = user_load($object->uid);
            $name = '';
            if (isset($member->field_profile_fname['und'][0]['value']))
                $name .= $member->field_profile_fname['und'][0]['value'] . ' ';
            if (isset($member->field_profile_lname['und'][0]['value']))
                $name .= $member->field_profile_lname['und'][0]['value'];
            $name = trim($name);
            if (empty($name))
                $name = $member->mail;
            $member->full_name = $name;
            $users[] = $member;
        }
        $ds = DIRECTORY_SEPARATOR;
        $path = dirname(__FILE__) . $ds . 'themes' . $ds;
        $output = frender($path . 'events.tpl.php', array('events' => $events, 'project' => $project, 'users' => $users));
    }
    return json_encode(array('content' => $output, 'time' => time()));
}

function upload_cloud_file($vars) {
    if ($vars['server'] == 'dropbox') {
        $contents = file_get_contents($_REQUEST['link']);
        $ds = DIRECTORY_SEPARATOR;
        $path = str_replace('all' . $ds . 'modules' . $ds . 'project', 'default' . $ds . 'files' . $ds . 'tempt', dirname(__FILE__));
        $ext = strtolower(substr($_REQUEST['name'], strrpos($_REQUEST['name'], '.') + 1));
        $name = substr($_REQUEST['name'], 0, strrpos($_REQUEST['name'], '.')) . '_' . time() . '.' . $ext;
        $handle = fopen($path . $ds . $name, 'wb');
        if ($handle) {
            fwrite($handle, $contents);
            fclose($handle);
            return json_encode(array('error' => 0, 'path' => $name, 'id' => '#' . $_REQUEST['id']));
        }
        else
            return json_encode(array('error' => 1, 'id' => '#' . $_REQUEST['id']));
    }
    else if ($vars['server'] == 'google_drive') {
        $contents = urldecode($vars['content']);
        $ds = DIRECTORY_SEPARATOR;
        $path = str_replace('all' . $ds . 'modules' . $ds . 'project', 'default' . $ds . 'files' . $ds . 'tempt', dirname(__FILE__));
        $ext = strtolower(substr($_REQUEST['name'], strrpos($_REQUEST['name'], '.') + 1));
        $name = substr($_REQUEST['name'], 0, strrpos($_REQUEST['name'], '.')) . '_' . time() . '.' . $ext;
        $handle = fopen($path . $ds . $name, 'wb');
        if ($handle) {
            fwrite($handle, $contents);
            fclose($handle);
            return json_encode(array('error' => 0, 'path' => $name, 'id' => '#' . $_REQUEST['id']));
        }
        else
            return json_encode(array('error' => 1, 'id' => '#' . $_REQUEST['id']));
    }
}

function send_invitation($vars) {
    global $base_url, $user;
    $author = user_load($user->uid);
    $author->full_name = $user->full_name;
    $project = node_load($vars['nid']);
    $member = user_load($vars['uid']);
    $member->full_name = '';
    if (isset($member->field_profile_fname['und'][0]['value']))
        $member->full_name .= $member->field_profile_fname['und'][0]['value'] . ' ';
    if (isset($member->field_profile_lname['und'][0]['value']))
        $member->full_name .= substr($member->field_profile_lname['und'][0]['value'], 0, 1) . '.';
    if (empty($member->full_name))
        $member->full_name = $member->mail;
    list($name, $domain) = explode('@', $member->full_name);
    $site = arg(3, $base_url);
    $message = '<p>Hi, ' . $name . '</p>';
    $message .= '<p>' . $author->full_name . ' added you to this project on Freebasecamp. <strong>' . $project->title . '</strong></p>';
    $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 19px">';
    $message .= ' <a href="http://www.freebasecamp.org/invitation?action=accept&site=' . $site . '&uid=' . $member->uid . '&signature=' . $member->signature . '">Accept this invitation to get started</a></p>';
    $message.= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 19px">We&rsquo;re using Freebasecamp to collaborate. It\'s a website where we have discussions, share files and keep track of to-do lists.</p>';
    $message.= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 19px">If you\'re new to Freebasecamp, <a href="http://www.youtube.com/watch?v=6MDJwG4GTts&amp;feature=youtu.be">check out this 90 second video</a> for tips to getting the most from Freebasecamp.</p>';
    $message.= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 19px;display:none">You can also <a href="http://www.freebasecamp.org/invitation?action=decline&site=' . $site . '&uid=' . $member->uid . '&signature=' . $member->signature . '">decline this invitation</a> if you don&rsquo;t want to participate.</p>';
    @send_mail('info@freebasecamp.org', $member->mail, 'You\'ve been added to the ' . $project->title . ' project on Freebasecamp', $message);
    db_update("users")->fields(array('invitation' => time()))->condition("uid", $member->uid, "=")->execute();
    return json_encode(array('error' => 0, 'message' => ''));
}

function remove_member($vars) {
    global $user;
    $project = node_load($vars['nid']);
    $member = user_load($vars['uid']);
    $member->full_name = '';
    if (isset($member->field_profile_fname['und'][0]['value']))
        $member->full_name .= $member->field_profile_fname['und'][0]['value'] . ' ';
    if (isset($member->field_profile_lname['und'][0]['value']))
        $member->full_name .= substr($member->field_profile_lname['und'][0]['value'], 0, 1) . '.';
    if (empty($member->full_name))
        $member->full_name = $member->mail;
    db_query("DELETE FROM tbl_assign_project WHERE nid = " . $vars['nid'] . " AND uid = " . $vars['uid']);
    db_insert("tbl_history")->fields(array(
        'nid' => $vars['nid'],
        'uid' => $user->uid,
        'title' => $project->title,
        'content' => 'removed ' . $member->full_name . ' from the project',
        'created' => time()
    ))->execute();
    return json_encode(array('error' => 0, 'message' => ''));
}

function invite_members($vars) {
    global $base_url, $user;
    $author = user_load($user->uid);
    $author->full_name = '';
    if (isset($author->field_profile_fname['und'][0]['value']))
        $author->full_name .= $author->field_profile_fname['und'][0]['value'];
    if (isset($author->field_profile_lname['und'][0]['value']))
        $author->full_name .= ' ' . $author->field_profile_lname['und'][0]['value'];
    $author->full_name = trim($author->full_name);
    if (empty($author->full_name))
        $author->full_name = $author->mail;
    $project = node_load($vars['nid']);
    $project->alias = 'node/' . $project->nid;
    $members = array();
    $names = array();
    foreach ($vars['invitees'] as $invitee) {
        if (!is_email($invitee['email_address']))
            continue;
        $result = db_query("SELECT uid FROM users WHERE mail = '" . $invitee['email_address'] . "'");
        $uid = 0;
        foreach ($result as $object)
            $uid = $object->uid;
        $account = NULL;
        if (!$uid) {
            $password = user_password(8);
            $edit = array(
                'name' => $invitee['email_address'],
                'pass' => $password,
                'mail' => $invitee['email_address'],
                'status' => 0,
                'signature' => random_string(),
                'language' => 'en',
                'init' => $invitee['email_address'],
                'roles' => array(2 => 'authenticated user'),
                'status' => 1
            );
            $edit['field_profile_picture'] = array('und' => array(0 => array('value' => get_avatar())));
            $edit['field_manager'] = array('und' => array(0 => array('uid' => $user->uid)));
            if (isset($vars['permissions'][$invitee['email_address']]) && !empty($vars['permissions'][$invitee['email_address']])) {
                $edit['field_permissions'] = array('und' => array());
                if ($vars['permissions'][$invitee['email_address']]['admin']) {
                    $edit['field_permissions']['und'][] = array('value' => 'Admin');
                    $edit['field_permissions']['und'][] = array('value' => 'Create');
                } else if ($vars['permissions'][$invitee['email_address']]['can_create_projects'])
                    $edit['field_permissions']['und'][] = array('value' => 'Create');
                if (empty($edit['field_permissions']['und']))
                    $edit['field_permissions'] = array();
            }
            $account = user_save(NULL, $edit);
            if ($account->uid) {
                $account->full_name = $account->mail;
                $uid = $account->uid;
            }
            $account->new_account = true;
        } else {
            $id = 0;
            $client = 0;
            $result = db_query("SELECT id FROM tbl_assign_project WHERE nid = " . $project->nid . " AND uid = " . $uid);
            foreach ($result as $object)
                $id = $object->id;
            if ($id)
                $uid = 0;
            $account = user_load($uid);
            $account->full_name = '';
            if (isset($account->field_profile_fname['und'][0]['value']))
                $account->full_name .= $account->field_profile_fname['und'][0]['value'] . ' ';
            if (isset($author->field_profile_lname['und'][0]['value']))
                $account->full_name .= $account->field_profile_lname['und'][0]['value'];
            if (empty($account->full_name))
                $account->full_name = $account->mail;
            if (isset($vars['permissions'][$invitee['email_address']]) && !empty($vars['permissions'][$invitee['email_address']])) {
                $account->field_permissions = array('und' => array());
                if ($vars['permissions'][$invitee['email_address']]['can_create_projects'])
                    $account->field_permissions['und'][] = array('value' => 'Create');
                if ($vars['permissions'][$invitee['email_address']]['admin'])
                    $account->field_permissions['und'][] = array('value' => 'Admin');
            }
            user_save($account);
            $account->new_account = false;
        }
        if ($uid) {
            $members[$account->uid] = $account;
            $names[] = $account->full_name;
            $id = db_insert("tbl_assign_project")->fields(array(
                        'nid' => $project->nid,
                        'uid' => $uid,
                        'client' => 0,
                        'created' => time(),
                        'status' => 0
                    ))->execute();
        }
    }
    $emails = array();
    $site = arg(3, $base_url);
    foreach ($members as $member) {
   
        $message = '<table><tbody><tr><td><img src="' . $base_url . '/sites/default/files/profile/' . $author->field_profile_picture['und'][0]['value'] . '" style="width:96px;border-radius:48px;"/></td><td><p style="color:red;font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 19px">' . $author->full_name . ' added you to this project on Freebasecamp.</p></td></tr></tbody></table>';

        if ($member->new_account)
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px"><a href="http://www.freebasecamp.org/invitation?site=' . $site . '&action=accept&uid=' . $uid . '&signature=' . $member->signature . '">Accept this invitation to get started</a></p>';

        else {
            
            
            $message .= '<p><strong>Project: ' . $project->title . '</strong></p>';
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px"><a href="' . $base_url . '/node/' . $project->nid . '">Visit this project on Freebasecamp</a></p>';
            
            //add to friend list
            
            $query_friend_admin="SELECT nid FROM node WHERE type='friend_list' AND title ='$member->uid'";
            $nids=  db_query($query_friend_admin);
            foreach ($nids as $nid_n){
                $friend_list=node_load($nid_n);
                $count=0;
            }
            
            
        }
        $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px">Hi there. We\'ll be using Freebasecamp to share ideas, gather feedback, and track progress during this project.</p>';

        if ($member->new_account)
            $message.= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48pxx;display:none">You can also <a href="http://www.freebasecamp.org/invitation?site=' . $site . '&action=decline&uid=' . $uid . '&signature=' . $member->signature . '">decline this invitation</a> if you don&rsquo;t want to participate.</p>';

        $others = $names;
        unset($others[$member->uid]);
        if (!empty($others)) {
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48pxx">The other people added to this project are:<br/>' . implode(', ', $others) . '</p>';
        }

      //  send_mail('info@freebasecamp.org', $member->mail, 'You\'ve been added to the ' . $node->title . ' project on Freebasecamp', $message);

        if ($member->new_account) {
            $emails[] = $member->mail;
            db_update("users")->fields(array('invitation' => time()))->condition("uid", $member->uid, "=")->execute();
        }
    }
    if (!empty($names)) {
        $name_list = '';
        $length = count($names);
        if ($length == 1)
            $name_list = $names[0];
        else {
            $name_list = $names[$length - 1];
            unset($names[$length - 1]);
            $name_list = implode(', ', $names) . ' and ' . $name_list;
        }
        db_insert("tbl_history")->fields(array(
            'nid' => $project->nid,
            'uid' => $user->uid,
            'title' => $project->title,
            'content' => 'gave ' . $name_list . ' access to the project',
            'created' => time()
        ))->execute();
    }
    $members = array();
    $result = db_query("SELECT uid FROM tbl_assign_project WHERE client = 0 AND nid = " . $project->nid . " ORDER BY uid ASC");
    foreach ($result as $object) {
        $member = user_load($object->uid);
        $member->full_name = '';
        if (isset($member->field_profile_fname['und'][0]['value']) && !empty($member->field_profile_fname['und'][0]['value']))
            $member->full_name = $member->field_profile_fname['und'][0]['value'];
        if (isset($member->field_profile_lname['und'][0]['value']) && !empty($member->field_profile_lname['und'][0]['value']))
            $member->full_name .= ' ' . $member->field_profile_lname['und'][0]['value'];
        $member->full_name = trim($member->full_name);
        if (empty($member->full_name))
            $member->full_name = $member->mail;
        $members[] = $member;
    }
    $output = '';
    foreach ($members as $member) {
        $output .= '
		<article class="access" data-person-id="' . $member->uid . '" id="access_' . $member->uid . '">
		    <div class="wrapper">
		        <a data-replace-stack="true" href="' . $base_url . '/user/' . $member->uid . '" class="link_page" data-uid="'.$member->uid.'">
		        <img class="avatar" height="96" src="' . $base_url . '/sites/default/files/profile/' . $member->field_profile_picture['und'][0]['value'] . '"
		        title="' . $member->full_name . '" width="96"></a>';
        if (is_email($member->full_name)) {
            $secs = explode('@', $member->full_name);
            $output .= '
				<h1>
					<span class="mailbox">' . $secs[0] . '</span><span class="at_symbol">@</span><span class="domain">' . $secs[1] . '</span>
				</h1>';
        } else {
            $output .= '
				<h1>
					<a class="person_name" href="' . $base_url . '/user/' . $member->uid . '" class="link_page" data-uid="'.$member->uid.'">' . $member->full_name . '</a>
		            <div class="email">
		                <a href="mailto:' . $member->mail . '">' . $member->mail . '</a>
		            </div>
		        </h1>';
        }
        $output .= '
		        <div class="last_invitee_event">Active <time data-time-ago="" datetime="2013-12-02T07:33:07Z">2 hours ago</time></div>';
        if ($member->uid == 1)
            $output .= '
				<div class="intro">
            		The account owner has access to all projects.
       			</div>';
        else if ($member->uid != $project->uid) {
            $output .= '
				<div class="controls">
					<div class="invite">
						<a rel="nofollow" href="" data-remote="true" data-method="post" onclick="invite_resend(this,event)" data-activated-text="Sending invitation…" class="decorated">Send another invitation</a>
					</div>
					<form method="post" class="revoke" action="" accept-charset="UTF-8">
						<div style="margin:0;padding:0;display:inline">
							<input type="hidden" value="✓" name="utf8">
							<input type="hidden" value="delete" name="_method">
						</div>
						<div class="intro">
							<a onclick="start_remove(this,event)" class="decorated">Remove</a>
						</div>
						<div style="display:none" class="confirm">
							<span class="question">Remove The from this project?</span>
							<input type="button" value="Remove" name="commit" onclick="remove_team(this,' . $project->nid . ',' . $member->uid . ')"> or <a onclick="stop_remove(this,event,' . $project->nid . ',' . $member->uid . ')" class="decorated">Keep Them</a>
						</div>
						<div style="display:none" class="request">
							Removing ' . $member->full_name . '…
						</div>
					</form>   
				</div>';
        }
        $output .= '
		    </div>
		</article>';
    }
    return json_encode(array('error' => 0, 'people' => 'team', 'content' => $output));
}

function invite_clients($vars) {
    global $base_url, $user;
    $author = user_load($user->uid);
    $author->full_name = '';
    if (isset($author->field_profile_fname['und'][0]['value']))
        $author->full_name .= $author->field_profile_fname['und'][0]['value'];
    if (isset($author->field_profile_lname['und'][0]['value']))
        $author->full_name .= ' ' . $author->field_profile_lname['und'][0]['value'];
    $author->full_name = trim($author->full_name);
    if (empty($author->full_name))
        $author->full_name = $author->mail;
    $project = node_load($vars['nid']);
    $project->alias = 'node/' . $project->nid;
    $members = array();
    $names = array();
    foreach ($vars['client_invitees'] as $invitee) {
        if (!is_email($invitee['email_address']))
            continue;
        $result = db_query("SELECT uid FROM users WHERE mail = '" . $invitee['email_address'] . "'");
        $uid = 0;
        foreach ($result as $object)
            $uid = $object->uid;
        $account = NULL;
        if (!$uid) {
            $password = user_password(8);
            $edit = array(
                'name' => $invitee['email_address'],
                'pass' => $password,
                'mail' => $invitee['email_address'],
                'status' => 0,
                'signature' => random_string(),
                'language' => 'en',
                'init' => $invitee['email_address'],
                'roles' => array(2 => 'authenticated user'),
                'status' => 1
            );
            $edit['field_profile_picture'] = array('und' => array(0 => array('value' => get_avatar())));
            $edit['field_manager'] = array('und' => array(0 => array('uid' => $user->uid)));
            $account = user_save(NULL, $edit);
            if ($account->uid) {
                $account->full_name = $account->mail;
                $uid = $account->uid;
            }
            $account->new_account = true;
        } else {
            $id = 0;
            $result = db_query("SELECT id,client FROM tbl_assign_project WHERE nid = " . $project->nid . " AND uid = " . $uid);
            foreach ($result as $object)
                $id = $object->id;
            if ($id)
                $uid = 0;
            $account = user_load($uid);
            $account->full_name = '';
            if (isset($account->field_profile_fname['und'][0]['value']))
                $account->full_name .= $account->field_profile_fname['und'][0]['value'] . ' ';
            if (isset($author->field_profile_lname['und'][0]['value']))
                $account->full_name .= $account->field_profile_lname['und'][0]['value'];
            if (empty($account->full_name))
                $account->full_name = $account->mail;
            $account->new_account = false;
        }
        if ($uid) {
            $members[$account->uid] = $account;
            $id = db_insert("tbl_assign_project")->fields(array(
                        'nid' => $project->nid,
                        'uid' => $uid,
                        'client' => 1,
                        'created' => time(),
                        'status' => 0
                    ))->execute();
        }
    }
    $emails = array();
    foreach ($members as $member) {
        $message = '<table><tbody><tr><td><img src="' . $base_url . '/sites/default/files/profile/' . $author->field_profile_picture['und'][0]['value'] . '" style="width:96px;border-radius:48px;"/></td><td><p style="color:red;font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 19px">' . $author->full_name . ' added you to this project on Freebasecamp.</p></td></tr></tbody></table>';
        $site = arg(3, $base_url);
        if ($member->new_account)
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px"><a href="http://www.freebasecamp.org/invitation?site=' . $site . '&action=accept&uid=' . $uid . '&signature=' . $member->signature . '">Accept this invitation to get started</a></p>';

        else {
            $message .= '<p><strong>Project: ' . $project->title . '</strong></p>';
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px"><a href="' . $base_url . '/node/' . $project->nid . '">Visit this project on Freebasecamp</a></p>';
        }
        $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px">Hi there. We\'ll be using Freebasecamp to share ideas, gather feedback, and track progress during this project.</p>';

        if ($member->new_account)
            $message.= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48pxx;display:none">You can also <a href="http://www.freebasecamp.org/invitation?site=' . $site . '&action=decline&uid=' . $uid . '&signature=' . $member->signature . '">decline this invitation</a> if you don&rsquo;t want to participate.</p>';
        $others = $names;
        unset($others[$member->uid]);
        if (!empty($others)) {
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48pxx">The other people added to this project are:<br/>' . implode(', ', $others) . '</p>';
        }
        @send_mail('info@freebasecamp.org', $member->mail, 'You\'ve been added to the ' . $project->title . ' project on Freebasecamp', $message);

        if ($member->new_account) {
            db_update("users")->fields(array('invitation' => time()))->condition("uid", $member->uid, "=")->execute();
            $emails[] = $member->mail;
        }

        $names[] = $member->full_name;
    }
   // add_to_main_site($emails, $base_url);
    if (!empty($names)) {
        $name_list = '';
        $length = count($names);
        if ($length == 1)
            $name_list = $names[0];
        else {
            $name_list = $names[$length - 1];
            unset($names[$length - 1]);
            $name_list = implode(', ', $names) . ' and ' . $name_list;
        }
        db_insert("tbl_history")->fields(array(
            'nid' => $project->nid,
            'uid' => $user->uid,
            'title' => $project->title,
            'content' => 'gave ' . $name_list . ' access to the project',
            'created' => time()
        ))->execute();
    }
    $members = array();
    $is_client = 0;
    $result = db_query("SELECT client FROM tbl_assign_project WHERE uid = " . $user->uid . " AND nid = " . $project->nid);
    foreach ($result as $object)
        $is_client = $object->client;
    $result = NULL;
    if ($is_client)
        $result = db_query("SELECT uid FROM tbl_assign_project WHERE nid = " . $project->nid . " ORDER BY uid ASC");
    else
        $result = db_query("SELECT uid FROM tbl_assign_project WHERE client = 1 AND nid = " . $project->nid);
    foreach ($result as $object) {
        $member = user_load($object->uid);
        $member->full_name = '';
        if (isset($member->field_profile_fname['und'][0]['value']) && !empty($member->field_profile_fname['und'][0]['value']))
            $member->full_name = $member->field_profile_fname['und'][0]['value'];
        if (isset($member->field_profile_lname['und'][0]['value']) && !empty($member->field_profile_lname['und'][0]['value']))
            $member->full_name .= ' ' . $member->field_profile_lname['und'][0]['value'];
        $member->full_name = trim($member->full_name);
        if (empty($member->full_name))
            $member->full_name = $member->mail;
        $members[] = $member;
    }
    $output = '';
    if (!empty($members)) {
        foreach ($members as $member) {
            $output .= '
			<article class="access" data-person-id="' . $member->uid . '" id="access_' . $member->uid . '">
				<div class="wrapper">
				    <a data-replace-stack="true" href="' . $base_url . '/user/' . $member->uid . '" class="link_page" data-uid="'.$member->uid.'">
				    <img class="avatar" height="96" src="' . $base_url . '/sites/default/files/profile/' . $member->field_profile_picture['und'][0]['value'] . '"
				    title="' . $member->full_name . '" width="96"></a>';
            if (is_email($member->full_name)) {
                $secs = explode('@', $member->full_name);
                $output .= '
					<h1>
						<span class="mailbox">' . $secs[0] . '</span><span class="at_symbol">@</span><span class="domain">' . $secs[1] . '</span>
					</h1>';
            } else {
                $output .= '
					<h1>
						<a class="person_name" href="' . $base_url . '/user/' . $member->uid . '" class="link_page" data-uid="'.$member->uid.'">' . $member->full_name . '</a>
				        <div class="email">
				            <a href="mailto:' . $member->mail . '">' . $member->mail . '</a>
				        </div>
				    </h1>';
            }
            $output .= '
				    <div class="last_invitee_event">Active <time data-time-ago="" datetime="2013-12-02T07:33:07Z">2 hours ago</time></div>';
            if ($member->uid == 1)
                $output .= '
					<div class="intro">
		        		The account owner has access to all projects.
		   			</div>';
            else if ($member->uid != $project->uid) {
                $output .= '
					<div class="controls">
						<div class="invite">
							<a rel="nofollow" href="" data-remote="true" data-method="post" onclick="invite_resend(this,event)" data-activated-text="Sending invitation…" class="decorated">Send another invitation</a>
						</div>
						<form method="post" class="revoke" action="" accept-charset="UTF-8">
							<div style="margin:0;padding:0;display:inline">
								<input type="hidden" value="✓" name="utf8">
								<input type="hidden" value="delete" name="_method">
							</div>
							<div class="intro">
								<a onclick="start_remove(this,event)" class="decorated">Remove</a>
							</div>
							<div style="display:none" class="confirm">
								<span class="question">Remove The from this project?</span>
								<input type="button" value="Remove" name="commit" onclick="remove_team(this,' . $project->nid . ',' . $member->uid . ')"> or <a onclick="stop_remove(this,event,' . $project->nid . ',' . $member->uid . ')" class="decorated">Keep Them</a>
							</div>
							<div style="display:none" class="request">
								Removing ' . $member->full_name . '…
							</div>
						</form>   
					</div>';
            }
            $output .= '
				</div>
			</article>';
        }
    }
    return json_encode(array('error' => 0, 'people' => 'client', 'content' => $output));
}

function invite_members_clients($vars) {
    global $base_url, $user;
    $_SESSION['invited'] = '';
    $author = user_load($user->uid);
    $author->full_name = '';
    if (isset($author->field_profile_fname['und'][0]['value']))
        $author->full_name .= $author->field_profile_fname['und'][0]['value'];
    if (isset($author->field_profile_lname['und'][0]['value']))
        $author->full_name .= ' ' . $author->field_profile_lname['und'][0]['value'];
    $author->full_name = trim($author->full_name);
    if (empty($author->full_name))
        $author->full_name = $author->mail;
    $project = node_load($vars['nid']);
    $project->alias = 'node/' . $project->nid;
    $project->field_with_clients['und'][0]['value'] = $vars['with_clients'];
    node_save($project);
    $members = array();
    $names = array();
    if(isset($vars['invitees'])){
    foreach ($vars['invitees'] as $invitee) {
        if (!is_email($invitee['email_address']))
            continue;
        $result = db_query("SELECT uid FROM users WHERE mail = '" . $invitee['email_address'] . "'");
        $uid = 0;
        foreach ($result as $object)
            $uid = $object->uid;
        $account = NULL;
        if (!$uid) {
            $password = user_password(8);
            $edit = array(
                'name' => $invitee['email_address'],
                'pass' => $password,
                'mail' => $invitee['email_address'],
                'status' => 0,
                'signature' => random_string(),
                'language' => 'en',
                'init' => $invitee['email_address'],
                'roles' => array(2 => 'authenticated user'),
                'status' => 1
            );
            $edit['field_profile_picture'] = array('und' => array(0 => array('value' => get_avatar())));
            $edit['field_manager'] = array('und' => array(0 => array('uid' => $user->uid)));
            if (isset($vars['permissions'][$invitee['email_address']]) && !empty($vars['permissions'][$invitee['email_address']])) {
                $edit['field_permissions'] = array('und' => array());
                if ($vars['permissions'][$invitee['email_address']]['admin']) {
                    $edit['field_permissions']['und'][] = array('value' => 'Admin');
                    $edit['field_permissions']['und'][] = array('value' => 'Create');
                } else if ($vars['permissions'][$invitee['email_address']]['can_create_projects'])
                    $edit['field_permissions']['und'][] = array('value' => 'Create');
                if (empty($edit['field_permissions']['und']))
                    $edit['field_permissions'] = array();
            }
            $account = user_save(NULL, $edit);
            if ($account->uid) {
                $account->full_name = $account->mail;
                $uid = $account->uid;
            }
            $account->new_account = true;
        } else {
            $id = 0;
            $client = 0;
            $result = db_query("SELECT id FROM tbl_assign_project WHERE nid = " . $project->nid . " AND uid = " . $uid);
            foreach ($result as $object)
                $id = $object->id;
            if ($id)
                $uid = 0;
            $account = user_load($uid);
            $account->full_name = '';
            if (isset($account->field_profile_fname['und'][0]['value']))
                $account->full_name .= $account->field_profile_fname['und'][0]['value'] . ' ';
            if (isset($account->field_profile_lname['und'][0]['value']))
                $account->full_name .= $account->field_profile_lname['und'][0]['value'];
            if (empty($account->full_name))
                $account->full_name = $account->mail;
            if (isset($vars['permissions'][$invitee['email_address']]) && !empty($vars['permissions'][$invitee['email_address']])) {
                $account->field_permissions = array('und' => array());
                if ($vars['permissions'][$invitee['email_address']]['can_create_projects'])
                    $account->field_permissions['und'][] = array('value' => 'Create');
                if ($vars['permissions'][$invitee['email_address']]['admin'])
                    $account->field_permissions['und'][] = array('value' => 'Admin');
            }
            user_save($account);
            $account->new_account = false;
        }
        if ($uid) {
            $members[$account->uid] = $account;
            $names[] = $account->full_name;
            $id = db_insert("tbl_assign_project")->fields(array(
                        'nid' => $project->nid,
                        'uid' => $uid,
                        'client' => 0,
                        'created' => time(),
                        'status' => 0
                    ))->execute();
        }
    }
    }
    $emails = array();
    $site = arg(3, $base_url);
    foreach ($members as $member) {
        $message = '<table><tbody><tr><td><img src="' . $base_url . '/sites/default/files/profile/' . $author->field_profile_picture['und'][0]['value'] . '" style="width:96px;border-radius:48px;"/></td><td><p style="color:red;font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 19px">' . $author->full_name . ' added you to this project on Freebasecamp.</p></td></tr></tbody></table>';
        if ($member->new_account)
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px"><a href="http://www.freebasecamp.org/invitation?site=' . $site . '&action=accept&uid=' . $uid . '&signature=' . $member->signature . '">Accept this invitation to get started</a></p>';

        else {
            $message .= '<p><strong>Project: ' . $project->title . '</strong></p>';
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px"><a href="' . $base_url . '/node/' . $project->nid . '">Visit this project on Freebasecamp</a></p>';
        }
        $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px">Hi there. We\'ll be using Freebasecamp to share ideas, gather feedback, and track progress during this project.</p>';

        if ($member->new_account)
            $message.= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48pxx;display:none">You can also <a href="http://www.freebasecamp.org/invitation?site=' . $site . '&action=decline&uid=' . $uid . '&signature=' . $member->signature . '">decline this invitation</a> if you don&rsquo;t want to participate.</p>';

        $others = $names;
        unset($others[$member->uid]);
        if (!empty($others)) {
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48pxx">The other people added to this project are:<br/>' . implode(', ', $others) . '</p>';
        }
        send_mail('info@freebasecamp.org', $member->mail, 'You\'ve been added to the ' . $project->title . ' project on Freebasecamp', $message);
        if ($member->new_account) {
            $emails[] = $member->mail;
            db_update("users")->fields(array('invitation' => time()))->condition("uid", $member->uid, "=")->execute();
        }
    }
  //  add_to_main_site($emails, $base_url);
    $_SESSION['invited'].= implode(', ', $names);
    if (!empty($names)) {
        $name_list = '';
        $length = count($names);
        if ($length == 1)
            $name_list = $names[0];
        else {
            $name_list = $names[$length - 1];
            unset($names[$length - 1]);
            $name_list = implode(', ', $names) . ' and ' . $name_list;
        }
        db_insert("tbl_history")->fields(array(
            'nid' => $project->nid,
            'uid' => $user->uid,
            'title' => $project->title,
            'content' => 'gave ' . $name_list . ' access to the project',
            'created' => time()
        ))->execute();
    }
    /* client */
    $members = array();
    $names = array();
    if(isset($vars['client_invitees'])){
    foreach ($vars['client_invitees'] as $invitee) {
        if (!is_email($invitee['email_address']))
            continue;
        $result = db_query("SELECT uid FROM users WHERE mail = '" . $invitee['email_address'] . "'");
        $uid = 0;
        foreach ($result as $object)
            $uid = $object->uid;
        $account = NULL;
        if (!$uid) {
            $password = user_password(8);
            $edit = array(
                'name' => $invitee['email_address'],
                'pass' => $password,
                'mail' => $invitee['email_address'],
                'status' => 0,
                'signature' => random_string(),
                'language' => 'en',
                'init' => $invitee['email_address'],
                'roles' => array(2 => 'authenticated user'),
                'status' => 1
            );
            $edit['field_profile_picture'] = array('und' => array(0 => array('value' => get_avatar())));
            $edit['field_manager'] = array('und' => array(0 => array('uid' => $user->uid)));
            $account = user_save(NULL, $edit);
            if ($account->uid) {
                $account->full_name = $account->mail;
                $uid = $account->uid;
            }
            $account->new_account = true;
        } else {
            $id = 0;
            $result = db_query("SELECT id,client FROM tbl_assign_project WHERE nid = " . $project->nid . " AND uid = " . $uid);
            foreach ($result as $object)
                $id = $object->id;
            if ($id)
                $uid = 0;
            $account = user_load($uid);
            $account->full_name = '';
            if (isset($account->field_profile_fname['und'][0]['value']))
                $account->full_name .= $account->field_profile_fname['und'][0]['value'] . ' ';
            if (isset($account->field_profile_lname['und'][0]['value']))
                $account->full_name .= $account->field_profile_lname['und'][0]['value'];
            if (empty($account->full_name))
                $account->full_name = $account->mail;
            $account->new_account = false;
        }
        if ($uid) {
            $members[$account->uid] = $account;
            $id = db_insert("tbl_assign_project")->fields(array(
                        'nid' => $project->nid,
                        'uid' => $uid,
                        'client' => 1,
                        'created' => time(),
                        'status' => 0
                    ))->execute();
        }
    }
}
    $emails = array();
    foreach ($members as $member) {
        $message = '<table><tbody><tr><td><img src="' . $base_url . '/sites/default/files/profile/' . $author->field_profile_picture['und'][0]['value'] . '" style="width:96px;border-radius:48px;"/></td><td><p style="color:red;font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 19px">' . $author->full_name . ' added you to this project on Freebasecamp.</p></td></tr></tbody></table>';
        $site = arg(3, $base_url);
        if ($member->new_account)
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px"><a href="http://www.freebasecamp.org/invitation?site=' . $site . '&action=accept&uid=' . $uid . '&signature=' . $member->signature . '">Accept this invitation to get started</a></p>';

        else {
            $message .= '<p><strong>Project: ' . $project->title . '</strong></p>';
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px"><a href="' . $base_url . '/node/' . $project->nid . '">Visit this project on Freebasecamp</a></p>';
        }
        $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px">Hi there. We\'ll be using Freebasecamp to share ideas, gather feedback, and track progress during this project.</p>';

        if ($member->new_account)
            $message.= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48pxx;display:none">You can also <a href="http://www.freebasecamp.org/invitation?site=' . $site . '&action=decline&uid=' . $uid . '&signature=' . $member->signature . '">decline this invitation</a> if you don&rsquo;t want to participate.</p>';
        $others = $names;
        unset($others[$member->uid]);
        if (!empty($others)) {
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48pxx">The other people added to this project are:<br/>' . implode(', ', $others) . '</p>';
        }
        @send_mail('info@freebasecamp.org', $member->mail, 'You\'ve been added to the ' . $project->title . ' project on Freebasecamp', $message);

        if ($member->new_account) {
            db_update("users")->fields(array('invitation' => time()))->condition("uid", $member->uid, "=")->execute();
            $emails[] = $member->mail;
        }

        $names[] = $member->full_name;
    }
   // add_to_main_site($emails, $base_url);
    if (!empty($names)) {

        $name_list = '';
        $length = count($names);
        if ($length == 1)
            $name_list = $names[0];
        else {
            $name_list = $names[$length - 1];
            unset($names[$length - 1]);
            $name_list = implode(', ', $names) . ' and ' . $name_list;
        }
        if (!empty($_SESSION['invited']))
            $_SESSION['invited'].=', ' . $name_list;
        else
            $_SESSION['invited'] = $name_list;
        db_insert("tbl_history")->fields(array(
            'nid' => $project->nid,
            'uid' => $user->uid,
            'title' => $project->title,
            'content' => 'gave ' . $name_list . ' access to the project',
            'created' => time()
        ))->execute();
    }
    header("Location:$base_url/invite/$project->nid");
    // return json_encode(array('error' => 0, 'people' => 'team','people' => 'client'));
}

function add_project_event($vars) {
    global $base_url, $user, $msw_detect;
    if (empty($user->timezone))
        $user->timezone = 'Asia/Bangkok';
    //$user->timezone = 'America/Los_Angeles';
    date_default_timezone_set($user->timezone);
    $months = array('January' => '01', 'February' => '02', 'March' => '03', 'April' => '04', 'May' => '05', 'June' => '06', 'July' => '07', 'August' => '08', 'September' => '09', 'October' => '10', 'November' => '11', 'December' => '12');
    $show_client = isset($vars['private']) ? 0 : 1;
    $aid = db_insert("tbl_project_attribute")->fields(array(
                'nid' => $vars['bucket'],
                'uid' => $user->uid,
                'type' => 5,
                'subject' => $vars['summary'],
                'description' => $vars['description'],
                'send_client' => $show_client,
                'created' => time(),
                'modified' => time(),
                'status_send' => 0
            ))->execute();
    $at_time = NULL;
    if (!empty($vars['starts_at_time'])) {
        $vars['starts_at_time'] = strtolower($vars['starts_at_time']);
        $am = $pm = false;
        if (strpos($vars['starts_at_time'], 'am') !== false)
            $am = true;
        else if (strpos($vars['starts_at_time'], 'pm') !== false)
            $pm = true;
        $vars['starts_at_time'] = str_replace('am', '', str_replace('pm', '', $vars['starts_at_time']));
        $secs = explode(':', $vars['starts_at_time']);
        $length = count($secs);
        if ($length == 1) {
            if ($am)
                $at_time = $secs[0] . ':00:00';
            else if ($pm) {
                if ($secs[0] == 12)
                    $at_time = '12:00:00';
                else
                    $at_time = ($secs[0] + 12) . ':00:00';
            }
        }
        else if ($length == 2) {
            if ($am)
                $at_time = $secs[0] . ':' . $secs[1] . ':00';
            else if ($pm) {
                if ($secs[0] == 12)
                    $at_time = '12:' . $secs[1] . ':00';
                else
                    $at_time = ($secs[0] + 12) . ':' . $secs[1] . ':00';
            }
        }
        else if ($length == 3) {
            if ($am)
                $at_time = $secs[0] . ':' . $secs[1] . ':' . $secs[2];
            else if ($pm) {
                if ($secs[0] == 12)
                    $at_time = '12:' . $secs[1] . ':' . $secs[2];
                else
                    $at_time = ($secs[0] + 12) . ':' . $secs[1] . ':' . $secs[2];
            }
        }
    }
    if ($aid) {
        $calendar = node_load($vars['bucket']);
        $calendar->changed = time();
        node_save($calendar);
        $nids = array();
        $nids[] = $aid;
        $parent = $aid;
        $vars['starts_at_date'] = str_replace(',', '', $vars['starts_at_date']);
        list($m, $d, $y) = explode(' ', $vars['starts_at_date']);
        $vars['sdate'] = substr($m, 0, 3) . ' ' . $d;
        if ($d < 10)
            $d = '0' . $d;
        $vars['starts_at_date'] = $y . '-' . $months[$m] . '-' . $d;
        $vars['ends_at_date'] = str_replace(',', '', $vars['ends_at_date']);
        list($m, $d, $y) = explode(' ', $vars['ends_at_date']);
        $vars['edate'] = substr($m, 0, 3) . ' ' . $d;
        if ($d < 10)
            $d = '0' . $d;
        $vars['ends_at_date'] = $y . '-' . $months[$m] . '-' . $d;
        $vars['until_date'] = str_replace(',', '', $vars['until_date']);
        list($m, $d, $y) = explode(' ', $vars['until_date']);
        $vars['udate'] = substr($m, 0, 3) . ' ' . $d;
        if ($d < 10)
            $d = '0' . $d;
        $vars['until_date'] = $y . '-' . $months[$m] . '-' . $d;
        $end = $vars['ends_at_date'];
        if (!empty($vars['freq'])) {
            if ($vars['event_recurrence_end'] == 'forever')
                $end = '2999-12-31';
            else if ($vars['event_recurrence_end'] == 'count') {
                if ($vars['freq'] == 'daily')
                    $end = increase_unixdate($vars['ends_at_date'], $vars['count'] - 1);
                else if ($vars['freq'] == 'weekly')
                    $end = increase_unixdate($vars['ends_at_date'], ($vars['count'] - 1) * 7);
                else if ($vars['freq'] == 'monthly')
                    $end = increase_unixdate($vars['ends_at_date'], ($vars['count'] - 1) * 30);
                else if ($vars['freq'] == 'yearly')
                    $end = increase_unixdate($vars['ends_at_date'], ($vars['count'] - 1) * 365);
            }
            else if ($vars['event_recurrence_end'] == 'until') {
                $end = increase_unixdate($vars['until_date'], unixdate_range($vars['starts_at_date'], $vars['ends_at_date']));
            }
        }
        db_insert("tbl_events")->fields(array(
            'aid' => $aid,
            'calendar' => $vars['bucket'],
            'at_time' => $at_time,
            'start_date' => $vars['starts_at_date'],
            'end_date' => $vars['ends_at_date'],
            'begin' => $vars['starts_at_date'],
            'end' => $end,
            'at_repeat' => $vars['freq'],
            'recurrence' => $vars['event_recurrence_end'],
            'at_for' => $vars['count'],
            'at_until' => $vars['until_date'],
            'private' => (isset($vars['private']) ? 1 : 0),
            'notify' => (isset($vars['wants_notification']) ? $vars['wants_notification'] : 0),
            'at_group' => (isset($vars['subscriber']['group']) ? $vars['subscriber']['group'] : ''),
            'disabled' => ''
        ))->execute();
        $freqs = array('daily' => 'every day', 'weekly' => 'every week', 'monthly' => 'every month', 'yearly' => 'every year');
        $content = 'created an event';
        if (empty($vars['freq'])) {
            $content .= ' for ' . $vars['sdate'];
            if ($vars['sdate'] != $vars['edate'])
                $content .= ' until ' . $vars['edate'];
            else if (!empty($at_time)) {
                $content .= ' at ' . change_time($at_time);
            }
        } else {
            if ($vars['sdate'] == $vars['edate']) {
                $content .= ' starting on ' . $vars['sdate'];
                if (!empty($at_time)) {
                    $content .= ' at ' . change_time($at_time);
                }
            }
            else
                $content .= ' for ' . $vars['sdate'] . ' until ' . $vars['edate'];
            $content .= ' and repeating ' . $freqs[$vars['freq']];
            if ($vars['event_recurrence_end'] == 'count')
                $content .= ' for ' . $vars['count'] . ' ' . str_replace('ly', 's', str_replace('ily', 'ys', $vars['freq']));
            else if ($vars['event_recurrence_end'] == 'until')
                $content .= ' until ' . $vars['udate'];
        }
        db_insert("tbl_history")->fields(array(
            'nid' => $vars['bucket'],
            'aid' => $aid,
            'uid' => $user->uid,
            'title' => $vars['summary'],
            'content' => $content,
            'link' => 'discuss/event/' . $aid,
            'created' => time()
        ))->execute();
        $author = user_load($user->uid);
        $author->full_name = '';
        if (isset($author->field_profile_fname['und'][0]['value']))
            $author->full_name .= $author->field_profile_fname['und'][0]['value'];
        if (isset($author->field_profile_lname['und'][0]['value']))
            $author->full_name .= ' ' . $author->field_profile_lname['und'][0]['value'];
        $author->full_name = trim($author->full_name);
        if (empty($author->full_name))
            $author->full_name = $author->mail;
        $receivers = array();
        $names = array();
        if ($vars['wants_notification']) {
            if (isset($vars['subscriber']['group'])) {
                if ($vars['subscriber']['group'] == 'everyone') {
                    $result = db_query("SELECT uid FROM tbl_assign_project WHERE nid = " . $vars['bucket']);
                    foreach ($result as $object) {
                        $receiver = user_load($object->uid);
                        $receiver->full_name = '';
                        if (isset($receiver->field_profile_fname['und'][0]['value']))
                            $receiver->full_name .= $receiver->field_profile_fname['und'][0]['value'];
                        if (isset($receiver->field_profile_lname['und'][0]['value']))
                            $receiver->full_name .= ' ' . $receiver->field_profile_lname['und'][0]['value'];
                        $receiver->full_name = trim($receiver->full_name);
                        if (empty($receiver->full_name))
                            $receiver->full_name = $receiver->mail;
                        if ($receiver->uid != $user->uid)
                            $names[] = $receiver->full_name;
                        $receivers[] = $receiver;
                        db_insert("tbl_event_reminder")->fields(array('nid' => $vars['bucket'], 'aid' => $aid, 'uid' => $object->uid))->execute();
                    }
                }
                else if (isset($vars['message']['subscribers']) && !empty($vars['message']['subscribers'])) {
                    foreach ($vars['message']['subscribers'] as $uid) {
                        $receiver = user_load($object->uid);
                        $receiver->full_name = '';
                        if (isset($receiver->field_profile_fname['und'][0]['value']))
                            $receiver->full_name .= $receiver->field_profile_fname['und'][0]['value'];
                        if (isset($receiver->field_profile_lname['und'][0]['value']))
                            $receiver->full_name .= ' ' . $receiver->field_profile_lname['und'][0]['value'];
                        $receiver->full_name = trim($receiver->full_name);
                        if (empty($receiver->full_name))
                            $receiver->full_name = $receiver->mail;
                        if ($receiver->uid != $user->uid)
                            $names[] = $receiver->full_name;
                        $receivers[] = $receiver;
                        db_insert("tbl_event_reminder")->fields(array('nid' => $vars['bucket'], 'aid' => $aid, 'uid' => $uid))->execute();
                    }
                }
            }
            else if (isset($vars['calendar_event']['subscribers']) && !empty($vars['calendar_event']['subscribers'])) {
                foreach ($vars['calendar_event']['subscribers'] as $uid) {
                    if (!is_numeric($uid))
                        continue;
                    $id = 0;
                    $res = db_query("SELECT id FROM tbl_event_reminder WHERE aid = " . $aid . " AND uid = " . $uid);
                    foreach ($res as $obj)
                        $id = $obj->id;
                    if (!$id) {
                        db_insert("tbl_event_reminder")->fields(array('nid' => $vars['bucket'], 'aid' => $aid, 'uid' => $uid))->execute();
                        $receiver = user_load($uid);
                        $receiver->full_name = '';
                        if (isset($receiver->field_profile_fname['und'][0]['value']))
                            $receiver->full_name .= $receiver->field_profile_fname['und'][0]['value'];
                        if (isset($receiver->field_profile_lname['und'][0]['value']))
                            $receiver->full_name .= ' ' . $receiver->field_profile_lname['und'][0]['value'];
                        $receiver->full_name = trim($receiver->full_name);
                        if (empty($receiver->full_name))
                            $receiver->full_name = $receiver->mail;
                        if ($receiver->uid != $user->uid)
                            $names[] = $receiver->full_name;
                        $receivers[] = $receiver;
                    }
                }
                $res = db_query("SELECT uid FROM tbl_event_reminder WHERE aid = " . $aid);
                foreach ($res as $obj) {
                    if (!in_array($obj->uid, $vars['calendar_event']['subscribers']))
                        db_query("DELETE FROM tbl_event_reminder WHERE aid = " . $aid . " AND uid = " . $obj->uid);
                }
            }
            if (isset($vars['calendar_event']['new_subscriber_emails'])) {
                foreach ($vars['calendar_event']['new_subscriber_emails'] as $email) {
                    $id = 0;
                    $res = db_query("SELECT id FROM tbl_event_reminder WHERE aid = " . $aid . " AND email = '" . $email . "'");
                    foreach ($res as $obj)
                        $id = $obj->id;
                    if (!$id) {
                        db_insert("tbl_event_reminder")->fields(array('nid' => $vars['bucket'], 'aid' => $aid, 'uid' => 0, 'email' => $email))->execute();
                        $receiver = user_load($object->uid);
                        $receiver->full_name = '';
                        if (isset($receiver->field_profile_fname['und'][0]['value']))
                            $receiver->full_name .= $receiver->field_profile_fname['und'][0]['value'];
                        if (isset($receiver->field_profile_lname['und'][0]['value']))
                            $receiver->full_name .= ' ' . $receiver->field_profile_lname['und'][0]['value'];
                        $receiver->full_name = trim($receiver->full_name);
                        if (empty($receiver->full_name))
                            $receiver->full_name = $receiver->mail;
                        if ($receiver->uid != $user->uid)
                            $names[] = $receiver->full_name;
                        $receivers[] = $receiver;
                    }
                }
                $res = db_query("SELECT email FROM tbl_event_reminder WHERE aid = " . $aid . " AND email <> NULL AND email <> ''");
                foreach ($res as $obj) {
                    if (!in_array($obj->email, $vars['calendar_event']['new_subscriber_emails']))
                        db_query("DELETE FROM tbl_event_reminder WHERE aid = " . $aid . " AND email = '" . $obj->email . "'");
                }
            }
        }
        $from = 'notifications@freebasecamp.org';
        $subject = '(' . $calendar->title . ') ' . $vars['summary'];
        $message = '
			<p>Write ABOVE THIS LINE to post a reply or <a href="' . $base_url . '/discuss/event/' . $aid . '">view this on Freebasecamp</a></p>
			<table style="margin:20px 0;">
				<tbody>
					<tr>
						<td><img src="' . $base_url . '/sites/default/files/profile/' . $author->field_profile_picture['und'][0]['value'] . '"/></td>
						<td>
							<p style="color:#a52b28;">' . $author->full_name . ' added this event on Freebasecamp</p>
							<p><strong>' . $vars['summary'] . '</strong><br/>' . date('l M j, Y') . ' at ' . date('h:i') . ' ICT</p>
						</td>
					</tr>
				</tbody>
			</table>';
        if (!empty($names))
            $message .= '<p>This email was sent to: ' . implode(', ', $names) . ' and ' . $author->full_name . '</p>';
        else
            $message .= '<p>This email was sent to: ' . $author->full_name . '</p>';
        foreach ($receivers as $receiver) {
            $link = '<p><a href="' . $base_url . '/ajax-calendar?action=unsubscribe&project=' . $calendar->nid . '&event=' . $aid . '&uid=' . $receiver->uid . '&email=">Stop receiving emails</a> when comments are post about this event.</p>';
            @send_mail($from, $receiver->mail, $subject, $message . $link);
        }
        if (isset($vars['calendar_event']['new_subscriber_emails'])) {
            foreach ($vars['calendar_event']['new_subscriber_emails'] as $email) {
                $link = '<p><a href="' . $base_url . '/ajax-calendar?action=unsubscribe&project=' . $calendar->nid . '&event=' . $aid . '&uid=0&email=' . $email . '">Stop receiving emails</a> when comments are post about this event.</p>';
                @send_mail($from, $receiver->mail, $subject, $message . $link);
            }
        }
        if ($msw_detect->isMobile()) {
            return $aid;
        } else {
            $project = node_load($vars['bucket']);
            $with_clients = 0;
            if (isset($project->field_with_clients['und'][0]['value']))
                $with_clients = $project->field_with_clients['und'][0]['value'];
            $alias = 'node/' . $project->nid;
            $is_client = 0;
            $result = db_query("SELECT client FROM tbl_assign_project WHERE uid = " . $user->uid . " AND nid = " . $project->nid);
            foreach ($result as $object)
                $is_client = $object->client;
            $events = 0;
            if (!$with_clients || !$is_client) {
                $result = db_query("SELECT COUNT(aid) as total FROM tbl_project_attribute WHERE (type = 2 OR type = 5) AND nid = " . $project->nid);
                foreach ($result as $object)
                    $events = $object->total;
            } else {
                $result = db_query("SELECT COUNT(aid) as total FROM tbl_project_attribute WHERE (type = 2 OR type = 5) AND send_client = 1 AND nid = " . $project->nid);
                foreach ($result as $object)
                    $events = $object->total;
            }
            $users = array();
            $result = db_query("SELECT uid FROM tbl_assign_project WHERE nid = " . $project->nid);
            foreach ($result as $object) {
                $member = user_load($object->uid);
                $name = '';
                if (isset($member->field_profile_fname['und'][0]['value']))
                    $name .= $member->field_profile_fname['und'][0]['value'] . ' ';
                if (isset($member->field_profile_lname['und'][0]['value']))
                    $name .= $member->field_profile_lname['und'][0]['value'];
                $name = trim($name);
                if (empty($name))
                    $name = $member->mail;
                $member->full_name = $name;
                $users[] = $member;
            }
            $ds = DIRECTORY_SEPARATOR;
            $path = dirname(__FILE__) . $ds . 'themes' . $ds;
            $output = frender($path . 'events.tpl.php', array('events' => $events, 'project' => $project, 'users' => $users, 'with_clients' => $with_clients, 'is_client' => $is_client));
            return json_encode(array('error' => 0, 'events' => $output));
        }
    }
    else
        return json_encode(array('error' => 1, 'message' => 'The system can\'t create new event. Please try again'));
}

function add_project($vars,$file) {
    global $base_url, $user;
    if (empty($user->timezone))
        $user->timezone = 'Asia/Bangkok';
    //$user->timezone = 'America/Los_Angeles';
    date_default_timezone_set($user->timezone);
    $ds = DIRECTORY_SEPARATOR;
    $colors = array('FF5980', 'FF7380', 'FF9966', 'FFBF66', 'FFFF66', 'A6FF66', '66FF73', '66FFBF', '6699FF', '3859FF', 'BF66FF');
    $lighter_colors=array('FFCCE6','FFCCCC','FFD9CC','FFE6CC','FFFFBF','E0FFCC','BFFFCC','BFFFE6','B3D9FF','8C99FF','E6CCFF');
    $number_project=db_query("SELECT COUNT(nid) FROM node WHERE type='project'")->fetchField();
    $color = $colors[$number_project%11];
    $lighter_color = $lighter_colors[$number_project%11];
    $author = user_load($user->uid);
    $author->full_name = $user->full_name;
    if ($vars['project_template_id']) {
        $template = node_load($vars['project_template_id']);
        $node = new stdClass();
        $node->nid = 0;
        $node->uid = $user->uid;
        $node->type = 'project';
        $node->title = $template->title;
        $node->body = array('und' => array(0 => array('value' => $template->body['und'][0]['value'])));
        $node->field_color = array('und' => array(0 => array("value" => $color)));
        $node->field_light_color = array('und' => array(0 => array("value" => $lighter_color)));
        $node->field_with_clients = array('und' => array(0 => array('value' => $template->field_with_clients['und'][0]['value'])));
        $node->field_template = array('und' => array(0 => array('nid' => $template->nid)));
       $node->field_project_category =array('und'=>array(0=>array('tid'=>$vars['category'])));
       if(!empty($vars['group']))
           $node->field_project_group = array('und'=>array(0=>array('nid'=>$vars['group'])));
        if(isset($vars['tags'])&&count($vars['tags'])>0){
            foreach ($vars['tags'] as $tag){
                if(!empty($tag)){
                $node->field_project_tags['und'][]['tid'] = $tag;
                }
            }
        }
        $node->language = 'und';
        $node->status = 0;
        $node->created = time();
        node_save($node);
        if ($node->nid) {
            db_insert("tbl_assign_project")->fields(array(
                'nid' => $node->nid,
                'uid' => $user->uid,
                'client' => 0,
                'created' => time(),
                'status' => 0
            ))->execute();
            if ($user->uid != 2) {
                db_insert("tbl_assign_project")->fields(array(
                    'nid' => $node->nid,
                    'uid' => 2,
                    'client' => 0,
                    'created' => time(),
                    'status' => 0
                ))->execute();
            }
            db_insert("tbl_history")->fields(array(
                'nid' => $node->nid,
                'uid' => $user->uid,
                'title' => $node->title,
                'content' => 'created the project',
                'link' => 'node/' . $node->nid,
                'created' => time()
            ))->execute();
            $url = $base_url . '/node/' . $node->nid;
            $path = str_replace('all' . $ds . 'modules' . $ds . 'project', 'default' . $ds . 'files' . $ds . 'projects', dirname(__FILE__));
            if (!file_exists($path . $ds . $node->nid) || !is_dir($path . $ds . $node->nid)) {
                mkdir($path . $ds . $node->nid);
                chmod($path . $ds . $node->nid, 0755);
            }
            copy_project($template->nid, $node->nid);
            return json_encode(array('error' => 0, 'url' => $url, 'content' => drupal_render(node_view($node))));
        }
        else
            return json_encode(array('error' => 1, 'message' => 'The system can\'t create new project. Please try again.'));
    } else {
        $with_clients = isset($vars['with_clients']) ? 1 : 0;
        $node = new stdClass();
        $node->nid = 0;
        $node->uid = $user->uid;
        $node->type = 'project';
        $node->title = $vars['project']['name'];
        $node->body = array('und' => array(0 => array('value' => $vars['project']['description'])));
        $node->field_color = array('und' => array(0 => array("value" => $color)));
        $node->field_with_clients = array('und' => array(0 => array('value' => $with_clients)));
        $node->language = 'und';
        $node->status = 1;
        if($vars['category']>0)
        $node->field_project_category =array('und'=>array(0=>array('tid'=>$vars['category'])));
        if(isset($vars['tags'])&&count($vars['tags'])>0){
            foreach ($vars['tags'] as $tag){
                if(!empty($tag)){
                $node->field_project_tags['und'][]['tid'] = $tag;
                }
            }
        }if(isset($vars['new_tags'])&&count($vars['new_tags'])>0){
            foreach ($vars['new_tags'] as $tag){
                if(!empty($tag)){
                    $term = new stdClass();
                            $term->name = $tag;
                            $term->vid = 7;
                            taxonomy_term_save($term);
                            if($term->tid>0){
                             $node->field_project_tags['und'][]['tid'] = $term->tid;
                }
                }
            }
        }

         $file_picture = $_FILES['image_project'];
            if (!empty($file_picture['name']) && $file_picture['error'] == 0) {
                $picture = $file_picture;
                $source = $file_picture['tmp_name'];
                $path = str_replace('all' . DS . 'modules' . DS . 'project', 'default' . DS . 'files' . DS . 'img_project' . DS , dirname(__FILE__));
                $destination = $path . $picture['name'];
                $filename = basename($picture['name']);
                list($width, $height, $type, $attr) = getimagesize($source);
                if (file_exists($destination)) {
                    $ext = substr($filename, strrpos($filename, '.') + 1);
                    $basename = substr($filename, 0, strrpos($filename, '.'));
                    $filename = $basename . '_' . time() . '.' . $ext;
                    $destination = $path . $filename;
                }
                if (move_uploaded_file($source, $destination)) {
                    $node->field_project_image_text['und'][0]['value'] = $filename;
                }
            }
        
        if (isset($vars['project']['is_client_project']) && $vars['project']['is_client_project'])
            $node->comment = 1;
        $node->created = time();
        node_save($node);
        if ($node->nid) {
            db_insert("tbl_assign_project")->fields(array(
                'nid' => $node->nid,
                'uid' => $user->uid,
                'client' => 0,
                'created' => time(),
                'status' => 0
            ))->execute();
            if ($user->uid != 2) {
                db_insert("tbl_assign_project")->fields(array(
                    'nid' => $node->nid,
                    'uid' => 2,
                    'client' => 0,
                    'created' => time(),
                    'status' => 0
                ))->execute();
            }
            db_insert("tbl_history")->fields(array(
                'nid' => $node->nid,
                'uid' => $user->uid,
                'title' => $node->title,
                'content' => 'created the project',
                'link' => 'node/' . $node->nid,
                'created' => time()
            ))->execute();
            if (isset($vars['invitees']) && !empty($vars['invitees'])) {
                if (!isset($vars['permissions']))
                    $vars['permissions'] = array();
                $team_message = '';
                if (isset($vars['include_team_message']))
                    $team_message = $vars['message'];
                  invite_project_members($node, $author, $vars['invitees'], $vars['permissions'], $team_message);
            }
            if (isset($vars['client_invitees']) && !empty($vars['client_invitees'])) {
                $client_message = '';
                if (isset($vars['include_client_message']))
                    $client_message = $vars['client_message'];
                 invite_project_clients($node, $author, $vars['client_invitees'], $client_message);
            }

            $alias = 'node/' . $node->nid;
            $path = str_replace('all' . $ds . 'modules' . $ds . 'project', 'default' . $ds . 'files' . $ds . 'projects', dirname(__FILE__));
            if (!file_exists($path . $ds . $node->nid) || !is_dir($path . $ds . $node->nid)) {
                mkdir($path . $ds . $node->nid);
                chmod($path . $ds . $node->nid, 0755);
            }
            return json_encode(array('error' => 0, 'url' => $base_url . '/' . $alias, 'content' => drupal_render(node_view($node))));
        }
        else
            return json_encode(array('error' => 1, 'message' => 'The system can\'t create new project. Please try again.'));
    }
}

function invite_project_members($node, $author, $invitees, $permissions, $team_message) {
    global $base_url, $user;
    if (empty($user->timezone))
        $user->timezone = 'Asia/Bangkok';
    //$user->timezone = 'America/Los_Angeles';
    date_default_timezone_set($user->timezone);
    $members = array();
    $names = array();
    foreach ($invitees as $invitee) {
        if (!is_email($invitee['email_address']))
            continue;
        $result = db_query("SELECT uid FROM users WHERE mail = '" . $invitee['email_address'] . "'");
        $uid = 0;
        foreach ($result as $object)
            $uid = $object->uid;
        $account = NULL;
        if (!$uid) {
            $password = user_password(8);
            $edit = array(
                'name' => $invitee['email_address'],
                'pass' => $password,
                'mail' => $invitee['email_address'],
                'status' => 0,
                'signature' => random_string(),
                'language' => 'en',
                'init' => $invitee['email_address'],
                'roles' => array(2 => 'authenticated user'),
                'status' => 1,
                'timezone' => $user->timezone
            );
            $edit['field_profile_picture'] = array('und' => array(0 => array('value' => get_avatar())));
            $edit['field_manager'] = array('und' => array(0 => array('uid' => $user->uid)));
            $edit['field_permissions'] = array('und' => array());
            if (isset($permissions[$invitee['email_address']]['admin'])) {
                $edit['field_permissions']['und'][] = array('value' => 'Admin');
                $edit['field_permissions']['und'][] = array('value' => 'Create');
            } else if (isset($permissions[$invitee['email_address']]['can_create_projects']))
                $edit['field_permissions']['und'][] = array('value' => 'Create');
            if (empty($edit['field_permissions']['und']))
                $edit['field_permissions'] = array();
            $edit['field_manager'] = array('und' => array(0 => array('uid' => $user->uid)));
            $account = user_save(NULL, $edit);
            if ($account->uid) {
                $account->full_name = $account->mail;
                $uid = $account->uid;
                $account->new_account = true;
            }
        } else {
            $id = 0;
            $result = db_query("SELECT id FROM tbl_assign_project WHERE nid = " . $node->nid . " AND uid = " . $uid);
            foreach ($result as $object)
                $id = $object->id;
            $account = user_load($uid);
            $account->full_name = '';
            if (isset($account->field_profile_fname['und'][0]['value']))
                $account->full_name .= $account->field_profile_fname['und'][0]['value'] . ' ';
            if (isset($author->field_profile_lname['und'][0]['value']))
                $account->full_name .= $account->field_profile_lname['und'][0]['value'];
            if (empty($account->full_name))
                $account->full_name = $account->mail;
            if ($id)
                $uid = 0;
            $account->new_account = false;
        }
        if ($uid) {
            $id = db_insert("tbl_assign_project")->fields(array(
                        'nid' => $node->nid,
                        'uid' => $uid,
                        'client' => 0,
                        'created' => time(),
                        'status' => 0
                    ))->execute();
            if ($id) {
                $members[$account->uid] = $account;
                $names[] = $account->full_name;
            }
        }
    }
    $emails = array();
    foreach ($members as $member) {

        $message = '<table><tbody><tr><td><img src="' . $base_url . '/sites/default/files/profile/' . $author->field_profile_picture['und'][0]['value'] . '" style="width:96px;border-radius:48px;"/></td><td><p style="color:red;font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 19px">' . $author->full_name . ' added you to this project on Freebasecamp.</p></td></tr></tbody></table>';
        $site = arg(3, $base_url);
        if (!empty($team_message))
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px">' . $team_message . '</p>';

        if ($member->new_account)
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px"><a href="http://www.freebasecamp.org/invitation?site=' . $site . '&action=accept&uid=' . $uid . '&signature=' . $member->signature . '">Accept this invitation to get started</a></p>';

        else {
            $message .= '<p><strong>Project: ' . $node->title . '</strong></p>';
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px"><a href="' . $base_url . '/node/' . $node->nid . '">Visit this project on Freebasecamp</a></p>';
        }
        $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px">Hi there. We\'ll be using Freebasecamp to share ideas, gather feedback, and track progress during this project.</p>';

        if ($member->new_account)
            $message.= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px;display:none">You can also <a href="http://www.freebasecamp.org/invitation?site=' . $site . '&action=decline&uid=' . $uid . '&signature=' . $member->signature . '">decline this invitation</a> if you don&rsquo;t want to participate.</p>';
        $others = $names;
        unset($others[$member->uid]);
        if (!empty($others)) {
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48pxx">The other people added to this project are:<br/>' . implode(', ', $others) . '</p>';
        }

        send_mail('info@freebasecamp.org', $member->mail, 'You\'ve been added to the ' . $node->title . ' project on Freebasecamp', $message);

        if ($member->new_account) {
            db_update("users")->fields(array('invitation' => time()))->condition("uid", $member->uid, "=")->execute();
            $emails[] = $member->mail;
        }
    }
   // add_to_main_site($emails, $base_url);
    if (!empty($names)) {
        $name_list = '';
        $length = count($names);
        if ($length == 1)
            $name_list = $names[0];
        else {
            $name_list = $names[$length - 1];
            unset($names[$length - 1]);
            $name_list = implode(', ', $names) . ' and ' . $name_list;
        }
        db_insert("tbl_history")->fields(array(
            'nid' => $node->nid,
            'uid' => $user->uid,
            'title' => $node->title,
            'content' => 'gave ' . $name_list . ' access to the project',
            'created' => time()
        ))->execute();
    }
}

function invite_project_clients($node, $author, $invitees, $client_message) {
    global $base_url, $user;
    if (empty($user->timezone))
        $user->timezone = 'Asia/Bangkok';
    //$user->timezone = 'America/Los_Angeles';
    date_default_timezone_set($user->timezone);
    $members = array();
    $names = array();
    foreach ($invitees as $invitee) {
        if (!is_email($invitee['email_address']))
            continue;
        $result = db_query("SELECT uid FROM users WHERE mail = '" . $invitee['email_address'] . "'");
        $uid = 0;
        foreach ($result as $object)
            $uid = $object->uid;
        $account = NULL;
        if (!$uid) {
            $password = user_password(8);
            $edit = array(
                'name' => $invitee['email_address'],
                'pass' => $password,
                'mail' => $invitee['email_address'],
                'status' => 0,
                'signature' => random_string(),
                'language' => 'en',
                'init' => $invitee['email_address'],
                'roles' => array(2 => 'authenticated user'),
                'status' => 1,
                'timezone' => $user->timezone
            );
            $edit['field_profile_picture'] = array('und' => array(0 => array('value' => get_avatar())));
            $edit['field_manager'] = array('und' => array(0 => array('uid' => $user->uid)));
            $account = user_save(NULL, $edit);
            if ($account->uid) {
                $account->full_name = $account->mail;
                $uid = $account->uid;
                $account->new_account = true;
            }
        } else {
            $id = 0;
            $result = db_query("SELECT id FROM tbl_assign_project WHERE nid = " . $node->nid . " AND uid = " . $uid);
            foreach ($result as $object)
                $id = $object->id;
            if ($id)
                $uid = 0;
            $account = user_load($uid);
            $account->full_name = '';
            if (isset($account->field_profile_fname['und'][0]['value']))
                $account->full_name .= $account->field_profile_fname['und'][0]['value'] . ' ';
            if (isset($author->field_profile_lname['und'][0]['value']))
                $account->full_name .= $account->field_profile_lname['und'][0]['value'];
            if (empty($account->full_name))
                $account->full_name = $account->mail;
            $account->new_account = false;
        }
        if ($uid) {
            $id = db_insert("tbl_assign_project")->fields(array(
                        'nid' => $node->nid,
                        'uid' => $uid,
                        'client' => 1,
                        'created' => time(),
                        'status' => 0
                    ))->execute();
            if ($id) {
                $members[$account->uid] = $account;
                $names[] = $account->full_name;
            }
        }
    }
    $emails = array();
    $site = arg(3, $base_url);
    foreach ($members as $member) {

        $message = '<table><tbody><tr><td><img src="' . $base_url . '/sites/default/files/profile/' . $author->field_profile_picture['und'][0]['value'] . '" style="width:96px;border-radius:48px;"/></td><td><p style="color:red;font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 19px">' . $author->full_name . ' added you to this project on Freebasecamp.</p></td></tr></tbody></table>';

        if (!empty($client_message))
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px">' . $client_message . '</p>';

        if ($member->new_account)
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px"><a href="http://www.freebasecamp.org/invitation?site=' . $site . '&action=accept&uid=' . $uid . '&signature=' . $member->signature . '">Accept this invitation to get started</a></p>';

        else {
            $message .= '<p><strong>Project: ' . $node->title . '</strong></p>';
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px"><a href="' . $base_url . '/node/' . $node->nid . '">Visit this project on Freebasecamp</a></p>';
        }
        $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px">Hi there. We\'ll be using Freebasecamp to share ideas, gather feedback, and track progress during this project.</p>';

        if ($member->new_account)
            $message.= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48px;display:none">You can also <a href="http://www.freebasecamp.org/invitation?site=' . $site . '&action=decline&uid=' . $uid . '&signature=' . $member->signature . '">decline this invitation</a> if you don&rsquo;t want to participate.</p>';
        $others = $names;
        unset($others[$member->uid]);
        if (!empty($others)) {
            $message .= '<p style="font-size:15px;line-height:19px;font-family:&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif;color:#000000;margin:0 0 20px 48pxx">The other people added to this project are:<br/>' . implode(', ', $others) . '</p>';
        }
        @send_mail('info@freebasecamp.org', $member->mail, 'You\'ve been added to the ' . $node->title . ' project on Freebasecamp', $message);

        if ($member->new_account) {
            db_update("users")->fields(array('invitation' => time()))->condition("uid", $member->uid, "=")->execute();
            $emails[] = $member->mail;
        }
    }
    //add_to_main_site($emails, $base_url);
    if (!empty($names)) {
        $name_list = '';
        $length = count($names);
        if ($length == 1)
            $name_list = $names[0];
        else {
            $name_list = $names[$length - 1];
            unset($names[$length - 1]);
            $name_list = implode(', ', $names) . ' and ' . $name_list;
        }
        db_insert("tbl_history")->fields(array(
            'nid' => $node->nid,
            'uid' => $user->uid,
            'title' => $node->title,
            'content' => 'gave ' . $name_list . ' access to the project',
            'created' => time()
        ))->execute();
    }
}

function get_suggested_users($vars) {
    global $user, $msw_detect;
    $author = user_load($user->uid);
    $can_admin = can_admin($author);
    $keyword = $vars['keyword'];
    $users = viewable_users($can_admin);
    $disabled = array();
    if (isset($vars['project'])) {
        $result = db_query("SELECT uid FROM tbl_assign_project WHERE nid = " . $vars['project']);
        foreach ($result as $object)
            $disabled[$object->uid] = $object->uid;
    }
    $output = '';
    $companies = array();
    if (!isset($vars['user_only'])) {
        $result = db_query("SELECT c.id,c.title,GROUP_CONCAT(s.uid) as uids FROM tbl_companies c LEFT JOIN tbl_staffs s ON c.id = s.company WHERE c.title LIKE '%" . $keyword . "%' GROUP BY c.id ORDER BY c.title");
        foreach ($result as $object) {
            if (!empty($object->uids))
                $object->uids = explode(',', $object->uids);
            else
                $object->uids = array();
            $found = false;
            foreach ($object->uids as $uid) {
                if (!isset($disabled[$uid])) {
                    $found = true;
                    break;
                }
            }
            $object->disabled = 1;
            if ($found)
                $object->disabled = 0;
            $companies[$object->id] = $object;
        }
    }
    $groups = array();
    if (!isset($vars['user_only'])) {
        $result = db_query("SELECT g.id, g.title, GROUP_CONCAT(s.uid) as uids, g.company FROM tbl_groups g LEFT JOIN tbl_group_staffs s ON g.id = s.group_id WHERE g.title LIKE '%" . $keyword . "%' GROUP BY g.id ORDER BY g.title");
        foreach ($result as $object) {
            if (!empty($object->uids))
                $object->uids = explode(',', $object->uids);
            else
                $object->uids = array();
            $found = false;
            foreach ($object->uids as $uid) {
                if (!isset($disabled[$uid])) {
                    $found = true;
                    break;
                }
            }
            $object->disabled = 1;
            if ($found)
                $object->disabled = 0;
            $groups[$object->id] = $object;
        }
    }
    $i_name = (isset($vars['type']) && $vars['type'] == 'client') ? 'client_invitees' : 'invitees';
    $output_mobile = "";
    foreach ($groups as $group) {
        if ($group->disabled == 0) {
            $output .= '
				<li data-suggestion-id="group_' . $group->id . '" data-behavior="select_suggestion" class="enabled option suggestion" onclick="select_suggest_group(this)" onmouseover="suggest_person_mouseover(this)" onmouseleave="suggest_person_mouseleave(this)">
				    <strong class="name">' . $group->title . '</strong> <span>Group at ' . $companies[$group->company]->title . ' with ' . count($group->uids) . ' people</span>
				    <div style="display:none;" class="group-' . $group->id . '">
						<div class="group invitee field">
					  		<input type="hidden" name="groups[][id]" value="1' . $group->id . '">
					  		<div class="icon"></div>
					  		<div class="input">
								<input type="text" value="' . $group->title . '" spellcheck="false" readonly="">
								<input type="text" style="background:white; opacity:0;" disabled="">
					  		</div>
					  		<a class="remove" onclick="remove_group(this,event)"></a>
						</div>
					  	<div data-role="enabled_invitees">';
            $group_disabled = array();
            $count = 0;
            foreach ($group->uids as $uid) {
                if (!isset($disabled[$uid]))
                    $output .= '
					  		<div class="grouped person invitee field">
					  			<div class="autocomplete_people">
						  			<input type="hidden" name="' . $i_name . '[][email_address]" data-role="email_address_input" value="' . $users[$uid]->mail . '">
					  				<div class="icon"></div>
					  				<div class="input">
						  				<input type="text" value="' . $users[$uid]->full_name . '" spellcheck="false" readonly="">
						  				<input type="text" style="background:white; opacity:0;" disabled="">
					  				</div>
					  				<div class="suggestions" data-role="suggestions_view"></div>
									<a class="remove" onclick="remove_invitee(event,this)"></a>
								</div>
							</div>';
                else {
                    $count++;
                    $group_disabled[] = $uid;
                }
            }
            $ouput .= '
						</div>';
            if ($count > 0) {
                $output .= '
						<h3>' . ($count == 1 ? '1 person is' : 'These people are') . ' already on the project</h3>
					  	<div data-role="disabled_invitees">';
                foreach ($group_disabled as $uid) {
                    $output .= '
					  		<div class="grouped person invitee field disabled">
					  			<div class="autocomplete_people">
					  				<div class="icon"></div>
									<div class="input">
									  	<input type="text" value="' . $users[$uid]->full_name . '" spellcheck="false" readonly="">
									  	<input type="text" style="background:white; opacity:0;" disabled="">
									</div>
					  				<div class="suggestions" data-role="suggestions_view"></div>
								</div>
							</div>';
                }
                $output .= '
						</div>';
            }
            $output .= '
					</div>
				</li>';
        }
    }
    foreach ($companies as $company) {
        if ($company->disabled == 0) {
            $output .= '
				<li data-suggestion-id="company_' . $company->id . '" data-behavior="select_suggestion" class="enabled option suggestion" onclick="select_suggest_company(this)" onmouseover="suggest_person_mouseover(this)" onmouseleave="suggest_person_mouseleave(this)">
				    <strong class="name">' . $company->title . '</strong> <span>Company with ' . count($company->uids) . ' people</span>
				    <div style="display:none;" class="company-' . $company->id . '">
						<div class="group invitee field">
					  		<input type="hidden" name="companies[][id]" value="1' . $company->id . '">
					  		<div class="icon"></div>
					  		<div class="input">
								<input type="text" value="' . $company->title . '" spellcheck="false" readonly="">
								<input type="text" style="background:white; opacity:0;" disabled="">
					  		</div>
					  		<a class="remove" onclick="remove_group(this,event)"></a>
						</div>
					  	<div data-role="enabled_invitees">';
            $company_disabled = array();
            $count = 0;
            foreach ($company->uids as $uid) {
                if (!isset($disabled[$uid]))
                    $output .= '
					  		<div class="grouped person invitee field">
					  			<div class="autocomplete_people">
						  			<input type="hidden" name="' . $i_name . '[][email_address]" data-role="email_address_input" value="' . $users[$uid]->mail . '">
					  				<div class="icon"></div>
					  				<div class="input">
						  				<input type="text" value="' . $users[$uid]->full_name . '" spellcheck="false" readonly="">
						  				<input type="text" style="background:white; opacity:0;" disabled="">
					  				</div>
					  				<div class="suggestions" data-role="suggestions_view"></div>
									<a class="remove" onclick="remove_invitee(event,this)"></a>
								</div>
							</div>';
                else {
                    $count++;
                    $company_disabled[] = $uid;
                }
            }
            $ouput .= '
						</div>';
            if ($count > 0) {
                $output .= '
						<h3>' . ($count == 1 ? '1 person is' : 'These people are') . ' already on the project</h3>
					  	<div data-role="disabled_invitees">';
                foreach ($company_disabled as $uid) {
                    $output .= '
					  		<div class="grouped person invitee field disabled">
					  			<div class="autocomplete_people">
					  				<div class="icon"></div>
									<div class="input">
									  	<input type="text" value="' . $users[$uid]->full_name . '" spellcheck="false" readonly="">
									  	<input type="text" style="background:white; opacity:0;" disabled="">
									</div>
					  				<div class="suggestions" data-role="suggestions_view"></div>
								</div>
							</div>';
                }
                $output .= '
						</div>';
            }
            $output .= '
					</div>
				</li>';
        }
    }
    $keyword = strtolower($keyword);
    $members = array();
    foreach ($users as $member) {
        if (isset($vars['disabled']) && !empty($vars['disabled'])) {
            $more_disabled = explode(',', trim($vars['disabled'], '[]'));
            foreach ($more_disabled as $uid)
                $disabled[$uid] = 1;
        }
        if ((strpos(strtolower($member->full_name), $keyword) !== false) || (strpos($member->mail, $keyword) !== false)) {
            $members[] = $member->uid;
            if (!isset($disabled[$member->uid])) {
                $output .= '
					<li data-suggestion-id="person_' . $member->uid . '" class="enabled option suggestion" onclick="select_suggest_user(this)" onmouseover="suggest_person_mouseover(this)" onmouseleave="suggest_person_mouseleave(this)">
						<span class="name">' . $member->full_name . '</span>
						<span class="email">' . $member->mail . '</span>
						<div style="display:none;">
							<input type="text" readonly="" spellcheck="false" value="' . $member->full_name . '">
							<input type="text" disabled="" style="background:white; opacity:0;">
  						</div>
					</li>';
                $output_mobile.='<li><a data-person-id="person_' . $member->uid . '" onclick="select_suggest_user(this)" onmouseover="suggest_person_mouseover(this)" onmouseleave="suggest_person_mouseleave(this)"><span class="name">' . $member->full_name . '</span></a>
                                <div style="display:none;"><input class="email" type="text" readonly="" spellcheck="false" value="' . $member->mail . '">
                                </li>';
            }
        }
    }
    if (empty($output)) {
        if (!empty($groups)) {
            foreach ($groups as $group) {
                $output .= '<li class="disabled hint suggestion">Everyone from <span class="name">' . $group->title . '</span> is already on the project.</li>';
            }
        }
        if (!empty($companies)) {
            foreach ($companies as $company) {
                $output .= '<li class="disabled hint suggestion">Everyone from <span class="name">' . $company->title . '</span> is already on the project.</li>';
            }
        }
        if (!empty($members)) {
            foreach ($members as $uid) {
                $output .= '<li class="disabled hint suggestion"><span class="name">' . $users[$uid]->full_name . '</span> is already on the project.</li>';
            }
        }
    }
    if (!empty($output)) {
        if ($msw_detect->isMobile()) {
            return $output_mobile;
        } else {
            $output = '<ol class="suggestions">' . $output . '</ol>';
            return $output;
        }
    }
}

function get_suggested_users_mobile($vars) {
    global $user, $msw_detect;
    $author = user_load($user->uid);
    $can_admin = can_admin($author);
    $keyword = $vars['keyword'];
    $users = viewable_users($can_admin);
    $disabled = array();
    if (isset($vars['project'])) {
        $result = db_query("SELECT uid FROM tbl_assign_project WHERE nid = " . $vars['project']);
        foreach ($result as $object)
            $disabled[$object->uid] = $object->uid;
    }
    $output = '';
    $companies = array();
    if (!isset($vars['user_only'])) {
        $result = db_query("SELECT c.id,c.title,GROUP_CONCAT(s.uid) as uids FROM tbl_companies c LEFT JOIN tbl_staffs s ON c.id = s.company WHERE c.title LIKE '%" . $keyword . "%' GROUP BY c.id ORDER BY c.title");
        foreach ($result as $object) {
            if (!empty($object->uids))
                $object->uids = explode(',', $object->uids);
            else
                $object->uids = array();
            $found = false;
            foreach ($object->uids as $uid) {
                if (!isset($disabled[$uid])) {
                    $found = true;
                    break;
                }
            }
            $object->disabled = 1;
            if ($found)
                $object->disabled = 0;
            $companies[$object->id] = $object;
        }
    }
    $groups = array();
    if (!isset($vars['user_only'])) {
        $result = db_query("SELECT g.id, g.title, GROUP_CONCAT(s.uid) as uids, g.company FROM tbl_groups g LEFT JOIN tbl_group_staffs s ON g.id = s.group_id WHERE g.title LIKE '%" . $keyword . "%' GROUP BY g.id ORDER BY g.title");
        foreach ($result as $object) {
            if (!empty($object->uids))
                $object->uids = explode(',', $object->uids);
            else
                $object->uids = array();
            $found = false;
            foreach ($object->uids as $uid) {
                if (!isset($disabled[$uid])) {
                    $found = true;
                    break;
                }
            }
            $object->disabled = 1;
            if ($found)
                $object->disabled = 0;
            $groups[$object->id] = $object;
        }
    }
    $i_name = (isset($vars['type']) && $vars['type'] == 'client') ? 'client_invitees' : 'invitees';
    $output = "";
    $keyword = strtolower($keyword);
    $members = array();
    $loop = 0;
    $text_name = "";
    foreach ($users as $member) {
        if (isset($vars['disabled']) && !empty($vars['disabled'])) {
            $more_disabled = explode(',', trim($vars['disabled'], '[]'));
            foreach ($more_disabled as $uid)
                $disabled[$uid] = 1;
        }
        if ((strpos(strtolower($member->full_name), $keyword) !== false) || (strpos($member->mail, $keyword) !== false)) {
            $members[] = $member->uid;
            if (!isset($disabled[$member->uid])) {
                $text_name = $member->full_name;
                $output.='<div class="loop_in_confirmation"><a class="action_button" onclick="select_suggest_user(this)" data-email-address="' . $member->full_name . '" data-uid="' . $member->uid . '">Loop in ' . $member->full_name . '</a></div>';
                $loop++;
            }
        }
    }
    if (!empty($output)) {
        $output = '<p>' . ($loop > 1 ? ' These people' : $text_name) . ' can’t see this project, but you can loop them in to share this by email only:</p> ' . $output;
        return $output;
    }
}

function add_template($vars) {
    global $base_url, $user;
    if (empty($user->timezone))
        $user->timezone = 'Asia/Bangkok';
    //$user->timezone = 'America/Los_Angeles';
    date_default_timezone_set($user->timezone);
    $colors = array('3185c5', '000099', '3B9E93', '009900', 'aa0000', 'ec61a2', 'cc6633', '666666', '000000', '660099', 'ff9c00', 'e207c1', 'a5460d', '650606', '482a15', '2c5322', 'b3a543', '46647c');
    $color = $colors[array_rand($colors, 1)];
    $node = new stdClass();
    $node->nid = 0;
    $node->uid = $user->uid;
    $node->type = 'template';
    $node->title = 'Untitled Template';
    $node->body = array('und' => array(0 => array('value' => '')));
    $node->field_color = array('und' => array(0 => array('value' => $color)));
    $node->field_with_clients = array('und' => array(0 => array('value' => 0)));
    $node->language = 'und';
    $node->status = 1;
    $node->created = time();
    node_save($node);
    if ($node->nid) {
        db_insert("tbl_assign_project")->fields(array(
            'nid' => $node->nid,
            'uid' => $user->uid,
            'client' => 0,
            'created' => time(),
            'status' => 0
        ))->execute();
        if ($user->uid != 2) {
            db_insert("tbl_assign_project")->fields(array(
                'nid' => $node->nid,
                'uid' => 2,
                'client' => 0,
                'created' => time(),
                'status' => 0
            ))->execute();
        }
        db_insert("tbl_history")->fields(array(
            'nid' => $node->nid,
            'uid' => $user->uid,
            'title' => $node->title,
            'content' => 'created the template',
            'link' => 'node/' . $node->nid,
            'created' => time()
        ))->execute();
        $alias = 'node/' . $node->nid;
        $ds = DIRECTORY_SEPARATOR;
        $path = str_replace('all' . $ds . 'modules' . $ds . 'project', 'default' . $ds . 'files' . $ds . 'projects', dirname(__FILE__));
        if (!file_exists($path . $ds . $node->nid) || !is_dir($path . $ds . $node->nid)) {
            mkdir($path . $ds . $node->nid);
            chmod($path . $ds . $node->nid, 0755);
        }
        return json_encode(array('error' => 0, 'url' => $base_url . '/' . $alias, 'header' => '', 'content' => drupal_render(node_view($node))));
    }
    else
        return json_encode(array('error' => 1, 'message' => 'The system can\'t create new template. Please try again.'));
}

function load_content($vars) {
    global $base_url, $user;
    $content = $vars['content'];
    switch ($content) {
        case 'projects':
            if (isset($vars['project_view']))
                $_SESSION['project_view'] = $vars['project_view'];
            return projects(false);
            break;
        case 'templates':
            return templates(false);
            break;
        case 'add_project':
            return add_project_content();
            break;
        case 'template':
            $nid = $_REQUEST['nid'];
            $node = node_load($nid);
            $alias = 'node/' . $node->nid;
            return json_encode(array('url' => $base_url . '/' . $alias, 'content' => drupal_render(node_view($node))));
            break;
    }
}

function add_project_content() {
    global $base_url, $user, $msw_detect;
    $templates = array();
    if ($user->uid == 2)
        $result = db_query("SELECT n.nid FROM node n WHERE n.type='template' AND n.status <> -1 ORDER BY n.created asc");
    else
        $result = db_query("SELECT n.nid FROM node n INNER JOIN tbl_assign_project a ON n.nid = a.nid WHERE n.type='template' AND n.status <> -1 AND a.uid = $user->uid ORDER BY n.created asc");

    foreach ($result as $object) {
        $template = node_load($object->nid);
        $template->alias = 'node/' . $object->nid;
        $user_assign = db_query("SELECT * FROM tbl_assign_project WHERE nid = $template->nid");
        foreach ($user_assign as $user_info) {
            $user_info = user_load($user_info->uid);
            $template->assigned_user[] = $user_info;
        }
        $templates[] = $template;
    }
    $categories = taxonomy_term_load_multiple(array(),array('vid'=>2));
    $list_groups = node_load_multiple(array(), array('type' => 'group', 'status' => 1)) ;
    $ds = DIRECTORY_SEPARATOR;
    $path = dirname(__FILE__) . $ds . 'themes' . $ds;
    if ($msw_detect->isMobile()) {
        return frender($path . 'add_project_mobile.tpl.php', array('project_view' => $templates));
    } else {
        return frender($path . 'add_project.tpl.php', array('templates' => $templates,'categories'=>$categories,'groups'=>$list_groups));
    }
}

function invite() {
    global $base_url, $user, $msw_detect;
    if (empty($user->timezone))
        $user->timezone = 'Asia/Bangkok';
    //$user->timezone = 'America/Los_Angeles';
    date_default_timezone_set($user->timezone);
    $nid = arg(1);
    $ds = DIRECTORY_SEPARATOR;
    $path = dirname(__FILE__) . $ds . 'themes' . $ds;
    $author = user_load($user->uid);
    $author->fname = '';
    if (isset($author->field_profile_fname['und'][0]['value']))
        $author->fname .= $author->field_profile_fname['und'][0]['value'] . ' ';
    if (isset($author->field_profile_lname['und'][0]['value']))
        $author->fname .= $author->field_profile_lname['und'][0]['value'];
    if (empty($author->fname))
        $author->fname = $author->mail;
    $project = node_load($nid);
    $project->alias = 'node/' . $nid;
    $users = array();
    $result = db_query("SELECT * FROM tbl_assign_project WHERE nid = " . $nid . " AND client = 0 ORDER BY uid ASC");
    $newest = '1998-09-01';
    foreach ($result as $object) {
        $date_assign = date('Y-m-d H:i', $object->created);
        if ($date_assign > $newest)
            $newest = $date_assign;
        $member = user_load($object->uid);
        $name = '';
        if (isset($member->field_profile_fname['und'][0]['value']) && !empty($member->field_profile_fname['und'][0]['value']))
            $name = $member->field_profile_fname['und'][0]['value'];
        if (isset($member->field_profile_lname['und'][0]['value']) && !empty($member->field_profile_lname['und'][0]['value']))
            $name .= ' ' . $member->field_profile_lname['und'][0]['value'];
        $name = trim($name);
        if (empty($name))
            $name = $member->mail;
        $member->fname = $name;
        $member->assign_project = $object->created;
        $users[$member->uid] = $member;
    }
    $clients = array();
    $result = db_query("SELECT * FROM tbl_assign_project WHERE nid = " . $nid . " AND client = 1");
    foreach ($result as $object) {
        $date_assign = date('Y-m-d H:i', $object->created);
        if ($date_assign > $newest)
            $newest = $date_assign;
        $member = user_load($object->uid);
        $name = '';
        if (isset($member->field_profile_fname['und'][0]['value']) && !empty($member->field_profile_fname['und'][0]['value']))
            $name = $member->field_profile_fname['und'][0]['value'];
        if (isset($member->field_profile_lname['und'][0]['value']) && !empty($member->field_profile_lname['und'][0]['value']))
            $name .= ' ' . $member->field_profile_lname['und'][0]['value'];
        $name = trim($name);
        if (empty($name))
            $name = $member->mail;
        $member->fname = $name;
        $member->assign_project = $object->created;
        $clients[] = $member;
    }
    $is_client = 0;
    $result = db_query("SELECT client FROM tbl_assign_project WHERE nid = " . $project->nid . " AND uid = " . $user->uid);
    foreach ($result as $object)
        $is_client = $object->client;
    if ($msw_detect->isMobile())
        return frender($path . 'invite_mobile.tpl.php', array('author' => $author, 'project' => $project, 'users' => $users, 'is_client' => $is_client, 'clients' => $clients, 'newest' => $newest));
    else{
        if(isset($_REQUEST['from_workspace']))
            print frender($path . 'invite.tpl.php', array('author' => $author, 'project' => $project, 'users' => $users, 'is_client' => $is_client, 'clients' => $clients));
            else
        return frender($path . 'invite.tpl.php', array('author' => $author, 'project' => $project, 'users' => $users, 'is_client' => $is_client, 'clients' => $clients));
    }
}

function calendar_events() {
    global $user, $msw_detect;
    if (empty($user->timezone))
        $user->timezone = 'Asia/Bangkok';
    //$user->timezone = 'America/Los_Angeles';
    date_default_timezone_set($user->timezone);
    $project = node_load($_GET['nid']);
    $ds = DIRECTORY_SEPARATOR;
    $path = dirname(__FILE__) . $ds . 'themes' . $ds;
    $with_clients = 0;
    if (isset($project->field_with_clients['und'][0]['value']))
        $with_clients = $project->field_with_clients['und'][0]['value'];
    $is_client = 0;
    $result = db_query("SELECT client FROM tbl_assign_project WHERE uid = " . $user->uid . " AND nid = " . $project->nid);
    foreach ($result as $object)
        $is_client = $object->client;
    if ($msw_detect->isMobile()) {
        if (isset($_GET['action']) && $_GET['action'] == 'filter') {
            print frender($path . 'calendar_events_mobile_filter.tpl.php', array('project' => $project, 'with_clients' => $with_clients, 'is_client' => $is_client));
        }else
            return frender($path . 'calendar_events_mobile.tpl.php', array('project' => $project, 'with_clients' => $with_clients, 'is_client' => $is_client));
    }
    else{
        if(isset($_GET['from_workspace']))
            print frender($path . 'calendar_events.tpl.php', array('project' => $project, 'with_clients' => $with_clients, 'is_client' => $is_client));
            else
        return frender($path . 'calendar_events.tpl.php', array('project' => $project, 'with_clients' => $with_clients, 'is_client' => $is_client));
    }
}

function project_sortable() {
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
    switch ($action) {
        case 'todolist':
            if (isset($_POST['list'])) {
                $weight = 0;
                $list = explode("/", $_POST['list']);
                foreach ($list as $item) {
                    $tid = str_replace("sortable_todolist_", "", $item);
                    db_update('tbl_todolist')->fields(array('weight' => $weight))->condition('tid', $tid, "=")->execute();
                    $weight++;
                }
                //   variable_set('position' . $_POST['nid'], $_POST['list']);   
            }
            break;
        case 'todos':
            if (isset($_POST['list'])) {
                $aid = isset($_POST['aid']) ? $_POST['aid'] : 0;
                $tid = isset($_POST['tid']) ? $_POST['tid'] : 0;
                $result['todo_times'] = '';
                if ($aid > 0) {
                    $todolist_old = db_query("SELECT t.* FROM tbl_project_attribute ta INNER JOIN tbl_todolist t ON ta.tid = t.tid WHERE ta.aid=$aid")->fetchObject();

                    if ($todolist_old->tid == $tid) {
                        $weight = 1;
                        $list = explode("/", $_POST['list']);
                        foreach ($list as $item) {
                            $todo_id = str_replace("todo_", "", $item);
                            db_update('tbl_project_attribute')->fields(array('weight' => $weight))->condition('aid', $todo_id, "=")->execute();
                            if ($todo_id == $aid) {
                                $info = db_query("SELECT * FROM tbl_project_attribute  WHERE aid = $aid")->fetchObject();
                                $positions = ($weight == 1 ? 'top' : ($weight == 2 ? $weight . "nd" : ($weight == 3 ? $weight . "rd" : $weight . "th")));
                                $string = "moved a to-do to the $positions position of the list";
                                $info->link = "todos/$aid";
                                history('insert', $info, $string);
                            }
                            $weight++;
                        }
                    } else {
                        $todolist_new = db_query("SELECT t.* FROM tbl_todolist t WHERE tid = $tid")->fetchObject();
                        db_update('tbl_project_attribute')->fields(array('tid' => $tid, 'send_client' => $todolist_new->send_client, 'modified' => time()))->condition('aid', $aid, "=")->execute();

                        $info = db_query("SELECT * FROM tbl_project_attribute  WHERE aid = $aid")->fetchObject();
                        $string = "moved a to-do from '" . $todolist_old->name . "' to '" . $todolist_new->name . "'";
                        $info->link = "todos/$aid";
                        history('insert', $info, $string);
                        $weight = 0;
                        $list = explode("/", $_POST['list']);
                        foreach ($list as $item) {
                            $todo_id = str_replace("todo_", "", $item);
                            db_update('tbl_project_attribute')->fields(array('weight' => $weight))->condition('aid', $todo_id, "=")->execute();
                            $weight++;
                        }
                    }
                    $result['todo_times'] = reload_layout_select_todo_time($todolist_old->nid);
                }
                print json_encode($result);
            }
            break;
        case 'milestone':
            $aid = isset($_POST['aid'])?$_POST['aid']:0;
            db_update('tbl_project_attribute')->fields(array('parent_id'=>0))->condition('aid', $aid, "=")->execute();
            $nid = db_query("SELECT nid FROM tbl_project_attribute WHERE aid=$aid")->fetchField();
            print $nid;
            break;
    }
}

function project_edit() {
    $path_theme = dirname(__FILE__) . DS . 'themes' . DS;
    global $base, $user;
    $nid = arg(1);
    if ($nid > 0) {
        $project = node_load($nid);
        if (isset($_POST['commit'])) {
            $project->title = $_POST['project']['name'];
            $project->body['und'][0]['value'] = $_POST['project']['description'];
            $project->sticky = $_POST['project']['archived'];
            $project->promote = $_POST['project']['promote'];
            node_save($project);
             drupal_goto("node/".$project->nid);
        }
        return frender($path_theme . 'edit_project_mobile.tpl.php', array('project' => $project));
    }else
        drupal_goto();
}

function accesses_new() {
    $path_theme = dirname(__FILE__) . DS . 'themes' . DS;
    global $base, $user;
    $nid = isset($_GET['nid']) ? $_GET['nid'] : '';
    if ($nid > 0) {
        $project = node_load($nid);
        $members_assign = db_query("SELECT * FROM tbl_assign_project WHERE nid= $nid");
        $uids = array(1);
        foreach ($members_assign as $member) {
            $uids[] = $member->uid;
        }
        return frender($path_theme . 'invite_mobile_add_new.tpl.php', array('project' => $project, 'uids' => $uids));
    }
    else
        drupal_goto();
}
function iframe_mile(){
  
    global $base, $user;
    
   print theme('iframe_mile');
}
function add_milestone(){
     global $base, $user;
    
   return theme('add_milestone');
    
}
function todolist_filter() {
    $path_theme_attach = str_replace("modules" . DS . "project", "modules" . DS . "administration" . DS . "themes", dirname(__FILE__) . DS);
    global $base_url, $user;

    if (isset($_POST["nid"])) {
        if ($_POST["nid"] > 0) {
            $nid = $_POST["nid"];

            $project_info = get_info_project($nid, 'members');
            $project = $project_info['project'];
            $member_client = $project_info['member_client'];
            $members_all = $project_info['members_all'];
            $todolist = $list_assign_date = array();
            $list_assign_to = array();
            $list_assign_date_tempt = array();
            $assigns = db_query("SELECT pa.assign_to,count(assign_to) as number FROM tbl_project_attribute pa WHERE pa.type=2 AND pa.nid = $nid AND completed =0 AND deleted =0 GROUP BY assign_to ORDER BY number desc");
            foreach ($assigns as $info) {
                $assign = get_name_member($info->assign_to);
                $info->assign_to_mail = !empty($assign['full_name']) ? $assign['full_name'] : "(Unassigned)";
                $list_assign_to[$info->assign_to] = $info;
            }
            $is_client = db_query("SELECT client FROM tbl_assign_project WHERE uid = $user->uid AND nid = $nid")->fetchField();
            $where_show_client = "";
            if ($is_client == 1) {
                $where_show_client = " AND td.send_client=1";
            }
            $page=0;
            $limit=$page *20;
            $next=($page+1)*20;
            
            $next_item=0;
             if($_POST['sort']=="by_date"){
                $categories = db_query("SELECT td.* FROM tbl_todolist td WHERE td.deleted=0 AND td.nid = $nid AND completed =0 $where_show_client order by weight asc,created asc ");    
                 
             }else{
                 $categories = db_query("SELECT td.* FROM tbl_todolist td WHERE td.deleted=0 AND td.nid = $nid AND completed =0 $where_show_client order by weight asc,created desc LIMIT $limit , 20");
                 $next_item=db_query("SELECT COUNT(td.tid) FROM tbl_todolist td WHERE td.deleted=0 AND td.nid = $nid AND completed =0 $where_show_client order by weight asc,created desc LIMIT $next , 20")->fetchField();
              
             }
          
            foreach ($categories as $cat) {
                if (isset($_POST["filter"])) {
                    $date_assign = $_POST["date"];
                    $date_assign = date("Y-m-d", strtotime($_POST["date"]));
                    $todos = db_query("SELECT pa.* FROM tbl_project_attribute pa WHERE pa.type=2 AND pa.tid = $cat->tid AND pa.nid = $nid AND completed =0 AND deleted =0 AND assign_date='$date_assign' order by weight asc,created desc");
                } else {
                    $todos = db_query("SELECT pa.* FROM tbl_project_attribute pa WHERE pa.type=2 AND pa.tid = $cat->tid AND pa.nid = $nid AND completed =0 AND deleted =0 order by weight asc,created desc");
                }
                foreach ($todos as $todo) {
                    $assign = get_name_member($todo->assign_to);
                    $todo->assign_to_mail = !empty($assign['full_name']) ? $assign['full_name'] : "";
                    $todo->assign_to_date = '';
                    if (!empty($todo->assign_date)) {
                        $time = time_date($todo->assign_date, 'filter_todo');
                        foreach ($time as $time_info) {
                            $key = $time_info['key'];
                            $value = $time_info['value'];
                            if (in_array($key, $list_assign_date_tempt)) {
                                $list_assign_date[$key]['number'] ++;
                            } else {
                                $list_assign_date[$key]['value'] = $value;
                                $list_assign_date[$key]['number'] = 1;
                                $list_assign_date_tempt[] = $key;
                            }
                            $todo->assign_to_date = $key;
                        }
                    }
                    $todo->number_comment = number_comment('todo', $todo->aid, $todo->tid);
                    $cat->todos[] = $todo;
                }
                $cat->number_comment = number_comment('todolist', 0, $cat->tid);
                $todolist[] = $cat;
            }
            $categories_completed = db_query("SELECT td.* FROM tbl_todolist td WHERE td.deleted=0 AND td.nid = $nid AND completed =1 $where_show_client order by weight asc,created desc");
            unset($list_assign_date_tempt);
             
            if($_POST['sort']=="by_date"){
                        if (isset($_POST["filter"])) {
                            print theme("todolist_filter", array('todolist' => $todolist,
                                'project' => $project, 'members_all' => $members_all, 'member_client' => $member_client,
                                'list_assign_to' => $list_assign_to, 'list_assign_date' => $list_assign_date, 'categories_completed' => $categories_completed, 'date_filter' => $_POST["date"]));
                        }
                       
                        else {
                            print frender($path_theme_attach . 'todolists-page.tpl.php', array('todolist' => $todolist,
                                'project' => $project, 'members_all' => $members_all, 'member_client' => $member_client,
                                'list_assign_to' => $list_assign_to, 'list_assign_date' => $list_assign_date, 'categories_completed' => $categories_completed));
                        }
            }
            else{
                 if (isset($_POST["filter"])&& $_POST["filter"]=="fiter_list"){
                            
                            print theme("todolist_filter_bylist", array('todolist' => $todolist,
                                'project' => $project, 'members_all' => $members_all, 'member_client' => $member_client,
                                'list_assign_to' => $list_assign_to, 'list_assign_date' => $list_assign_date, 'categories_completed' => $categories_completed, 'date_filter' => $_POST["date"]));
                        }
                        else{
                             print theme('todolists_page_by_list', array('todolist' => $todolist,
                                'project' => $project, 'members_all' => $members_all, 'member_client' => $member_client,
                                'list_assign_to' => $list_assign_to, 'list_assign_date' => $list_assign_date, 'categories_completed' => $categories_completed,"next_item"=>$next_item));
                        }
                
            }
        }
    } else
        print "Incorrect information";
}

function icon_tool_click(){
    //return theme("milestone_chart");
    //return views_embed_view('milestone', 'block_1'); 
//    print"<pre>".print_r($_REQUEST).print"</pre>";
    global $base_url,$user;
   if(isset($_REQUEST["nid"])){
            if($_REQUEST["nid"]>0){
                $nid=$_REQUEST["nid"];
                $node= node_load($nid);
                $flag='';
                if(isset($_REQUEST["flag"])){
                    $flag=$_REQUEST["flag"];
                }
                if(isset($_REQUEST['filter'])&&!empty($_REQUEST['sdate'])&&!empty($_REQUEST['edate'])){
                    $sdate = isset($_REQUEST['sdate'])?date('Y-m-d',  strtotime($_REQUEST['sdate'])):'';
                    $edate = isset($_REQUEST['edate'])?date('Y-m-d',  strtotime($_REQUEST['edate'])):'';
                     $where =" AND assign_date BETWEEN '$sdate' AND '$edate'";
                }
                if($flag=="milestone_update"){
                    $mile_query="SELECT * FROM tbl_project_attribute WHERE type = '6' AND nid='$nid' $where ORDER BY assign_date ASC";
                    $count_mile=db_query("SELECT count(aid) FROM tbl_project_attribute WHERE type = '6' AND nid=$nid $where ORDER BY assign_date ASC")->fetchField();
                    $miles=  db_query($mile_query);
                    $start_date = date("d-m-Y", $node->created);
                    
                    $result=array();
//                    $result[0]["id"]=$nid;
//                    $result[0]["text"]=$node->title;
//                    $result[0]["start_date"]=$start_date;
//                    $result[0]["duration"]=15;
//                    $result[0]["parent"]=0;
//                    $i=1;
                    $i=0;
                    $string='';
                    $arr_temp=array();
                    
                    foreach($miles as $mile){
                         $colors=db_query("SELECT * FROM tbl_milestone_color WHERE mid= $mile->aid");
                         $assigns=db_query("SELECT * FROM tbl_assign_milestone WHERE milestone_id= $mile->aid");
                         $assign_count=db_query("SELECT COUNT(milestone_id) FROM tbl_assign_milestone WHERE milestone_id= $mile->aid");
                         
                         $ass_arr=array();
                         foreach ($assigns as $assign ){
                             
                             $ass_arr[]=$assign->assign_id;
                         }
                         
                         $colors_count=db_query("SELECT count(mid) FROM tbl_milestone_color WHERE mid= $mile->aid")->fetchField();
                        if(isset($mile->assign_date)&&$mile->assign_date!=''&&$mile->assign_date!=null){
                            $result["content"][$i]["id"]=$mile->aid;
                            $result["content"][$i]["text"]=$mile->subject;
                            $result["content"][$i]["open"]='true';
//                            $parent=$nid;
                            $parent=0;
                           if(isset($mile->parent_id)&&$mile->parent_id>0){
                               $parent=$mile->parent_id;
                               $arr_temp[$mile->parent_id][]=$mile->aid;
                           }

                            $start_date_m = date("d-m-Y", strtotime($mile->assign_date));
                            $duration=1;
                            
                            if(isset($mile->end_date_td)&&$mile->end_date_td!=''&& $mile->end_date_td!=null){
                                $str = strtotime(date($mile->end_date_td)) - (strtotime($mile->assign_date));
                                $a= floor($str/3600/24);
                               // $a=date_diff($mile->assign_date,$mile->end_date_td);
                                if($a>1)
                                    $duration=$a;
                                
                            }
                            if($colors_count>0){
                                foreach ($colors as $color){
                                    $result["colors"][$color->mid]["color"]=$color->color;
                                   
                                }
                            }
                            $diff=date_diff($date1,$date2);
                            $result["content"][$i]["start_date"]=$start_date_m;
                            $result["content"][$i]["duration"]=$duration;
                            $result["content"][$i]["parent"]=$parent;
                            $result["content"][$i]["description"]=$mile->description;
                            $result["content"][$i]["ass_to"]=$mile->assign_to;//assign
                            $result["content"][$i]["assign"]=$ass_arr;
                            $i++;
                        }
                    }
                    $p=0;
                    foreach ($arr_temp as $key=>$mids){
                        for ($k=0;$k<count($mids);$k++){
                            if($k==0){
                                    $result["link"][$p]["id"]=$mids[$k];
                                    $result["link"][$p]["source"]=$key;
                                    $result["link"][$p]["target"]=$mids[$k];
                                    $result["link"][$p]["type"]=1;
                                    $p++;
                            }
                            else if($k>0 && $k<count($mids)){
                                
                                    $result["link"][$p]["id"]=$mids[$k];
                                    $result["link"][$p]["source"]=$mids[$k-1];
                                    $result["link"][$p]["target"]=$mids[$k];
                                    $result["link"][$p]["type"]=0;
                                    $p++;
                            }
//                        print "---".$key."---";
//                        print"<pre>".print_r($result["link"])."</pre>";
                        }
                        
                    }
                   if($count_mile>0){
                        print json_encode($result);
                   }
                   else{
                       print 1;
                   }
                   // print $string;
                }
                else if($flag=="calendar_list"){
                        $path_theme_calendar = str_replace("project",  "calendar" . DS . "themes", dirname(__FILE__) . DS);
                        if (empty($user->timezone))
                            $user->timezone = 'Asia/Bangkok';
                        //$user->timezone = 'America/Los_Angeles';
                        $author = user_load($user->uid);
                        date_default_timezone_set($user->timezone);
                        $calendars = array();
                        $projects = array();
                        $result = db_query("SELECT nid FROM tbl_assign_project WHERE uid = " . $user->uid); //. " AND nid = ". $node->nid
                        $shared = false;
                        $nids = array();
                        foreach ($result as $object)
                            $nids[] = $object->nid;
                        $res = db_query("SELECT COUNT(aid) as total FROM tbl_project_attribute WHERE nid = " . $node->nid . " AND deleted = 0 AND (type = 5 OR (type = 2 AND assign_date IS NOT NULL AND assign_date <> ''))");
//                        foreach ($res as $obj)
//                            if ($obj->total > 0)
                                $projects[$node->nid] = $node;
                        //  }
                        $nid = 0;
                        if (!empty($projects)) {
                            foreach ($projects as $item) {
                                $nid = $item->nid;
                                break;
                            }
                        }
                        $inviters = array();
                        if ($nid) {
                            $inviters = get_inviters(array('nid' => $nid, 'encode' => false));
                        }
                        $calendar_node = 1;
                        if (isset($_GET['from_workspace']))
                            print frender($path_theme_calendar . 'calendar.tpl.php', array('author' => $author, 'calendars' => $calendars, 'shared' => $shared, 'projects' => $projects, 'inviters' => $inviters, 'calendar_node' => $calendar_node, 'node_id_calendar' => $node->nid));
                        else
                            print frender($path_theme_calendar . 'calendar.tpl.php', array('author' => $author, 'calendars' => $calendars, 'shared' => $shared, 'projects' => $projects, 'inviters' => $inviters, 'calendar_node' => $calendar_node, 'node_id_calendar' => $node->nid));
                    
                } else if($flag=='calendar_group'){
                    $path_theme_calendar = str_replace("project",  "calendar" . DS . "themes", dirname(__FILE__) . DS);
                        if (empty($user->timezone))
                            $user->timezone = 'Asia/Bangkok';
                        $author = user_load($user->uid);
                        date_default_timezone_set($user->timezone);
                   $time_current = isset($_POST['date'])?$_POST['date']:time();
                   $day_current = date('w',$time_current);
               $week_start = date('Y-m-d', ($time_current-(24*3600*$day_current)));
                $week_end = date('Y-m-d', ($time_current+(24*3600*(6-$day_current))));
                $date_current = date('Y-m-d',$time_current);
                 $tomorrow = date('Y-m-d',($time_current+24*3600));
                $nid=isset($_POST['nid'])?$_POST['nid']:0;
                        $calendars=array();
                        $lists =db_query("SELECT * FROM tbl_project_attribute pa 
                            INNER JOIN tbl_events tbl ON pa.aid = tbl.aid
                            WHERE nid = " . $nid . " AND deleted = 0 AND type = 5 AND start_date BETWEEN '$week_start' AND '$week_end' ORDER BY start_date asc ,at_time asc");
                        $results=array();
                       // $colors = array('#ae5757','#57c9ba','red','green','#e9e7ff','#fe9901','#fe99ea','#9a0000','#9a8a00','#ff32cb','#fff3cb','#ff8945');
                        $times_disable=array();
                        $list_appointment = array();
                        foreach ($lists as $info){
                            $member_info = get_name_member($info->uid);
                            $info->member_info=$member_info;
                            if($date_current==$info->start_date ||$tomorrow==$info->start_date){
                            $list_appointment[$info->start_date][]=$info;
                            }
                           // $color=array_rand ($colors);
                        //    $new_color='#d9cccc';
                            $kq = new stdClass();
                            $kq->aid=$info->aid;
                            //$kq->subject=(substr($info->at_time,0,5)).(($info->at_time!=$info->end_time&&!empty($info->end_time))?'-'.substr($info->end_time,0,5):'') .'-'.$info->subject .'-'.$member_info['full_name'];
                            $kq->subject=$member_info['full_name'];
                           $kq->at_time=$info->at_time;
                           $kq->end_time=!empty($info->end_time)?$info->end_time:$info->at_time;
                             $kq->color='#d9cccc';
                          //  if($info->at_time!=$info->end_time){
                                $shour = $info->at_time;
                                list($shr,$sm,$si) = explode(":",$shour);
                                $ehour = !empty($info->end_time)?$info->end_time:$info->at_time;
                                $get_time =(strtotime($ehour) - strtotime($shour))/60/60;
                                $time_repeat = round($get_time);
                                $value_extract = $get_time-floor($get_time);
                                $loop =($value_extract==0)?3:2;
                                for ($i = 0; $i <$time_repeat; $i++) {
                                    $m = ($sm<30)?0:$sm;
                                    $sh = $shour + $i;
                                     $hours=($sh < 10 ? ('0' . $sh) : $sh);
                                    for ($j = 0; $j < $loop; $j++) {
                                        $minutes =($m < 10 ? '0' . $m : $m);
                                        if($minutes==60){
                                           $hours+=1; 
                                           $minutes='00';
                                           $m=0;
                                        }
                                        $at_time = $hours. ':' . $minutes . ':00';
                                        $thu=date('l', strtotime($info->start_date));
                                      // $array_colors[$thu][$at_time] = $new_color;
                                           $times_disable[$thu][] =$at_time;
                                        $m+=30;
                                    }
                                }
                              
                           // }
                            $results[$info->at_time][$info->start_date] = $kq;
                        }
                        
                         for ($i = 6; $i < 22; $i++) {
                            $m = 0;
                            $sh =($i < 10 ? ('0' . $i) : $i);
                            for ($j = 0; $j < 2; $j++) {
                                $at_time = $sh . ':' . ($m < 10 ? ('0' . $m) : $m).":00";
                                if(array_key_exists($at_time,$results)){
                                for($t=0;$t<7;$t++){
                                    $date=date('Y-m-d',(strtotime($week_start)+($t*24*3600)));
                                    if(array_key_exists($date,$results[$at_time])){
                                         $calendars[$at_time][$date]= $results[$at_time][$date];    
                                    }else {
                                        $calendars[$at_time][$date]='';    
                                    }
                                }    
                                }
                                else {
                                    for($t=0;$t<7;$t++){
                                    $date=date('Y-m-d',(strtotime($week_start)+($t*24*3600)));
                                    $calendars[$at_time][$date]= '';    
                                }
                                }
                                $m+=30;
                            }
                        }
                        $array_colors =array();
                        $group = node_load($nid);
                        if (isset($_GET['from_workspace']))
                            print frender($path_theme_calendar . 'calendar_group.tpl.php', array('calendars'=>$calendars,
                                'week_start'=>$week_start,'week_end'=>$week_end,'time_current'=>$time_current,'results'=>$results,'group'=>$group,'times_disable'=>$times_disable,'array_colors'=>$array_colors,'list_appointment'=>$list_appointment));
                        else
                            print frender($path_theme_calendar . 'calendar_group.tpl.php', array('calendars'=>$calendars,
                                'week_start'=>$week_start,'week_end'=>$week_end,'time_current'=>$time_current,'results'=>$results,'group'=>$group,'times_disable'=>$times_disable,'array_colors'=>$array_colors,'list_appointment'=>$list_appointment));
                } else if($flag=='appointment_by_date'){
                    $date = isset($_GET['date'])?$_GET['date']:'';
                    $nid = isset($_GET['nid'])?$_GET['nid']:'';
                    $lists =db_query("SELECT * FROM tbl_project_attribute pa 
                            INNER JOIN tbl_events tbl ON pa.aid = tbl.aid
                            WHERE nid = " . $nid . " AND deleted = 0 AND type = 5 AND start_date = '".date('Y-m-d',strtotime($date))."' ORDER BY at_time asc");
                    $format_date=date('F d',  strtotime($date));
                    if(isset($_REQUEST['type_show'])&&$_REQUEST['type_show']=='small_calendar'){
                        $result =array('date'=>date('F d',  strtotime($date)),'content'=>'');
                        $content ='';
                    foreach ($lists as $info){
                         $title =(substr($info->at_time,0,5)).(($info->at_time!=$info->end_time&&!empty($info->end_time))?' - '.substr($info->end_time,0,5):'');
                         $content.='<div class="item_info"><span>'.$title.'</span> <span>'.$info->subject.'</span></div>';
                    }
                    $result['content']=$content;
                    print json_encode($result);
                    }else {
                    $table ='<h3> '.$format_date.'</h3><table style="width:100%"><tr class="todo_content_calendar"> <td>Time</td><td>Name</td><td>Contact</td><td>Notes</td></tr>';
                    foreach ($lists as $info){
                        $member_info = get_name_member($info->uid);
                        $title =(substr($info->at_time,0,5)).(($info->at_time!=$info->end_time&&!empty($info->end_time))?' - '.substr($info->end_time,0,5):'');
                        $table.='<tr><td>'.$title.'</td><td>'.$member_info['full_name'].'</td><td>'.$member_info['mail'].'</td><td>'.$info->subject.'</td></tr>';
                    }
                    $table.='</table>';
                    print $table;
                    }
                } else if($flag=='calendar_group_month'){
                               if (empty($user->timezone))
                            $user->timezone = 'Asia/Bangkok';
                        $author = user_load($user->uid);
                        date_default_timezone_set($user->timezone);
                        $calendars = array();
                        $projects = array();
                        $nid = isset($_REQUEST['nid'])?$_REQUEST['nid']:0;
                         $inviters = array();
                        $calendar_node = 1;
                        $group = node_load($nid);
                         print theme('calendar_group_month', array('calendars'=>$calendars,'prj'=>$group));
                }
                 else if($flag=="list_file"){
					
                      $path_theme_attach = str_replace("project",  "administration" . DS . "themes", dirname(__FILE__) . DS);
                        $nid = $node->nid;
                        $uid = $user->uid;
                        $page = 1;
                        $limit = "limit " . ($page - 1) * 20 . ",30";
                        $list_labels = array();
                        $folder_id=0;
                            if(isset($_REQUEST["id_folder"])){
                                $folder_id=$_REQUEST["id_folder"];
                                
                            }
                        $project = new stdClass();
						$where='';
						$file_type='default';
						$file_user='default';
						//get_files_filter
						if(isset($_REQUEST["file_type"])){
							if($_REQUEST["file_type"]!="default")
								$file_type=$_REQUEST["file_type"];
						}
						if(isset($_REQUEST["file_user"])){
							if($_REQUEST["file_user"]!="default")
								$file_user=$_REQUEST["file_user"];
						}
						
						if($file_type!='default' || $file_user!='default'){
							
							if($file_type!='default'){
								$where.=" AND name LIKE '%.".$file_type."' ";
							}
							if($file_user!='default'){
								$where.=" AND uid = ".$file_user." ";
							}
							
						}
						if(isset($_REQUEST["date_file"])){
							$date=strtotime($_REQUEST['date_file']);
							$date1=strtotime($_REQUEST['date_file'] ." 00:00:00");
							//$date3=date('m/d/y H:i:s',strtotime($_POST['date_document'] ." 23:59:59"));
							$date2=strtotime($_REQUEST['date_file'] ." 23:59:59");
							$where=" AND (created BETWEEN $date1  AND $date2 ) ";
						}
						
                        if (is_numeric($nid) && $nid > 0) {
                            
                            $project_info = db_query("SELECT n.nid,n.title,n.sticky FROM node n WHERE n.nid=" . $nid);
                            $is_client = db_query("SELECT client FROM tbl_assign_project WHERE uid = $user->uid AND nid = $nid")->fetchField();
                            foreach ($project_info as $project){
									if(isset($_REQUEST["file_type"]) || isset($_REQUEST["file_user"])){
										$list_files = get_files_filter('project',$where, $is_client, $limit, $project->nid);
									}else{
										if(isset($_REQUEST["date_file"])){
											$list_files = get_files_filter('project',$where, $is_client, $limit, $project->nid);
										}
										else{
											$list_files = get_files_infolder('project',$folder_id, $is_client, $limit, $project->nid);
										}
									
									}
								}
                            $list_labels = db_query("SELECT * FROM tbl_file_label tl INNER JOIN tbl_files tf ON tl.fid = tf.fid WHERE tl.nid = $project->nid GROUP BY label order by label");
                            $number_labels = db_query("SELECT count(*) FROM tbl_file_label tl INNER JOIN tbl_files tf ON tl.fid = tf.fid WHERE tl.nid = $project->nid GROUP BY label order by label")->fetchField();
                        } else if (is_numeric($uid) && $uid > 0) {
							if(isset($_REQUEST["file_type"]) || isset($_POST["file_user"])){
								$list_files = get_files_filter('user',$where, 0, $limit, $uid);
							}else{
								if(isset($_REQUEST["date_file"])){
									$list_files = get_files_filter('user',$where, 0, $limit, $uid);
								}else{
									$list_files = get_files_infolder('user',$folder_id,0, $limit, $uid);
								}
								
							}
								
                        }
						$date_filter=0;
						if(isset($_REQUEST["date_file"])){
							$date_filter=1;
						}
                    if(isset($_REQUEST["type"])){
                        $fol_id= isset($_REQUEST["id_folder"])?$_REQUEST["id_folder"]:0;

                        $sort="asc";
                            if(isset($_REQUEST["sort"])){
                                $sort=$_REQUEST["sort"];
                            }
                        print theme("attachments_page_sort", array('list_files' => $list_files, 'project' => $project, 'list_labels' => $list_labels, 'number_labels' => $number_labels,'file_type'=>$file_type,'file_user'=>$file_user,'date_filter'=>$date_filter,"folder_id"=>$folder_id,'sort'=>$_REQUEST["type"],"sort_order"=>$sort));
                        
                    }
                    else{
                        if (isset($_GET['from_workspace']))
                            print frender($path_theme_attach . 'attachments-page.tpl.php', array('list_files' => $list_files, 'project' => $project, 'list_labels' => $list_labels, 'number_labels' => $number_labels,'file_type'=>$file_type,'file_user'=>$file_user,'date_filter'=>$date_filter,"folder_id"=>$folder_id));
                        else
                            print frender($path_theme_attach . 'attachments-page.tpl.php', array('list_files' => $list_files, 'project' => $project, 'list_labels' => $list_labels, 'number_labels' => $number_labels,'file_type'=>$file_type,'file_user'=>$file_user,'date_filter'=>$date_filter,"folder_id"=>$folder_id));
                    }

                     
                     
                 }
                 else if($flag=="list_note"){
                     $nid = $node->nid;
                     $path_theme_attach = str_replace("project",  "administration" . DS . "themes", dirname(__FILE__) . DS);
                    $project_info = get_info_project($nid, 'members');
                    $project = $project_info['project'];
                    $is_client = db_query("SELECT client FROM tbl_assign_project WHERE uid = $user->uid AND nid = $nid")->fetchField();
					$filter=0;
                                        $date_to_sort=0;
					if(isset($_POST['date_document'])&& $_POST['date_document']){
                                            $result = array('content'=>'','filter_date'=>date("M d",  strtotime($_POST['date_document'])));
                                            $date_to_sort=$_POST['date_document'];
					$filter=1;
						$date=strtotime($_POST['date_document']);
						$date1=strtotime($_POST['date_document'] ." 00:00:00");
						//$date3=date('m/d/y H:i:s',strtotime($_POST['date_document'] ." 23:59:59"));
						$date2=strtotime($_POST['date_document'] ." 23:59:59");
						//print $date3;
						$document=get_document_filter_date($nid, $is_client,$date1,$date2);
                                               $result['content'] =theme('document_layout', array('list_document' => $document['list_document']));
                                                print json_encode($result);
					}
					else{
						$document = get_document($nid, $is_client);
                                                 if (isset($_GET['from_workspace']))
                        print frender($path_theme_attach . 'documents-page.tpl.php', array('list_document' => $document['list_document'], 'project' => $project, 'number_all_document' => $document['number_all'],'filter'=>$filter,"date_to_sort"=>$date_to_sort));
                    else
                        print frender($path_theme_attach . 'documents-page.tpl.php', array('list_document' => $document['list_document'], 'project' => $project, 'number_all_document' => $document['number_all'],'filter'=>$filter,"date_to_sort"=>$date_to_sort));
					}

                   
          
                     
                 }
                  else if($flag=="list_discussion"){
                       $nid = $node->nid;
                    $path_theme_attach = str_replace("project",  "administration" . DS . "themes", dirname(__FILE__) . DS);
                    $list_discussions = array();
                    $project_info = db_query("SELECT n.nid,n.title,n.sticky FROM node n WHERE n.nid=" . $nid);
                    foreach ($project_info as $project_dis) {

                    }
                    $is_client = db_query("SELECT client FROM tbl_assign_project WHERE uid = $user->uid AND nid = $nid")->fetchField();
                    $discussions = get_discussions($nid, $is_client);

                    $list_discussions = $discussions['list_discussions'];
                    if (isset($_GET['from_workspace']))
                        print frender($path_theme_attach . 'topics-page.tpl.php', array('list_discussions' => $list_discussions, 'project' => $project_dis));
                    else
                        print frender($path_theme_attach . 'topics-page.tpl.php', array('list_discussions' => $list_discussions, 'project' => $project_dis));
                      
                  }
                   else if($flag=="create_folder"){
                       $nid=isset($_POST["nid"])?$_POST["nid"]:0;
                        if($nid>0){//name_folder
                            $name_folder=isset($_POST["name_folder"])?$_POST["name_folder"]:'';
                            if(trim($name_folder)==''){
                                $name_folder="Undifined";
                                
                            }
                            $parent=isset($_POST["parent"])?$_POST['parent']:0;
                            
                            $aid = db_insert("tbl_project_attribute")->fields(array(
                                 'nid' => $nid,
                                 'uid' => $user->uid,
                                 'type' => 9,
                                 'subject' =>$name_folder,
                                 'description' =>'folder',
                                 'created' => time(),
                                 'modified' => time(),
                                 'parent_id'=>$parent
                             ))->execute();
                        }
                        $da=date("m/d/Y H:m:s a",time());
                        if($aid>0){
                            print json_encode(array('aid' => $aid, 'created' => $da,'nid'=>$nid));
                        }
                   }
                   else if($flag=="move_file"){
                       $fid=  isset($_POST["fid"])?$_POST["fid"]:0;
                       $folder_id= isset($_POST["folder_id"])?$_POST["folder_id"]:-1;
                       if($fid>0 && $folder_id>-1){
                           
                           $kq=db_update('tbl_files')->fields(array('folder_id' => $folder_id))->condition('fid', $fid, "=")->execute();
                           if($kq>0){
                               print 1;
                           }
                           else{
                               print 0;
                           }
                       }
                       else{
                           print 0;
                       }
                       
                   }
                   else if($flag=="move_multi_file"){
                       $arr_fid=  isset($_POST["arr_fid"])?$_POST["arr_fid"]:array();
                       $folder_id= isset($_POST["folder_id"])?$_POST["folder_id"]:-1;
                       $arr_folder_aid=isset($_POST["arr_fol_aid"])?$_POST["arr_fol_aid"]:array();//arr_fol_aid
                       $kq=$kq1=0;
                       if(count($arr_fid)>0 && $folder_id>-1){
                           
                           $kq=db_update('tbl_files')->fields(array('folder_id' => $folder_id))->condition('fid', $arr_fid, "IN")->execute();
                          
                       }
                       
                       if(count($arr_folder_aid)>0 && $folder_id>-1){
                       
                           for($i=0;$i<count($arr_folder_aid);$i++)
                           {
                               if($arr_folder_aid[$i]!=$folder_id)
                                $kq1=db_update('tbl_project_attribute')->fields(array('parent_id' => $folder_id))->condition('aid', $arr_folder_aid[$i], "=")->execute();
                           }
                           
                       }
                       if($kq>=0||$kq1>0){
                           print 1;
                       }
                       else{
                           print 0;
                       }
                   }
                   else if($flag=="delete_file"){
                       $fid=  isset($_POST["fid"])?$_POST["fid"]:0;
                       if($fid>0){
                           $kq=db_update('tbl_files')->fields(array('deleted' => '1','deleted_by'=>$user->uid,'deleted_date'=>time()))->condition('fid', $fid, "=")->execute();
                           if($kq>0){
                               print 1;
                           }
                           else{
                               print 0;
                           }
                       }
                       else{
                           print 0;
                       }
                       
                   }
                   else if($flag=="delete_multi_file"){
                       $arr_fid=  isset($_POST["arr_fid"])?$_POST["arr_fid"]:array();
                       $arr_fol_aid=isset($_POST["arr_fol_aid"])?$_POST["arr_fol_aid"]:array();
                       $kq=$kq1=0;
                       if(count($arr_fid)>0 ){
                           
                           $kq=db_update('tbl_files')->fields(array('deleted' => '1','deleted_by'=>$user->uid,'deleted_date'=>time()))->condition('fid', $arr_fid, "IN")->execute();
                          
                       }
                      if(count($arr_fol_aid>0)){
                          $kq1=db_update('tbl_project_attribute')->fields(array('deleted' => '1','deleted_by'=>$user->uid,'deleted_date'=>time()))->condition('aid', $arr_fol_aid, "IN")->execute();
                      }
                      if($kq>0 || $kq1>0){
                          print 1;
                      }
                      else{
                          print 0;
                      }
                   }
                   
                   
                   else if($flag=="move_to_parent"){
                       $arr_fid=  isset($_POST["arr_fid"])?$_POST["arr_fid"]:array();
                       $folder_id= isset($_POST["folder_id"])?$_POST["folder_id"]:0;
                       $arr_folder_aid=isset($_POST["arr_fol_aid"])?$_POST["arr_fol_aid"]:array();//arr_fol_aid
                       $kq=$kq1=0;
                       $parent=-1;
                       if($folder_id>0){
                           $parent_fl=  db_query("SELECT parent_id FROM tbl_project_attribute WHERE aid=$folder_id and type=9");
                           foreach ($parent_fl as $pr_id){
                               $parent=$pr_id->parent_id;
                           }
                           
                       }
                       if(count($arr_fid)>0 && $parent>-1){
                           
                           $kq=db_update('tbl_files')->fields(array('folder_id' => $parent))->condition('fid', $arr_fid, "IN")->execute();
                          
                       }
                       
                       if(count($arr_folder_aid)>0 && $parent>-1){
                       
                           for($i=0;$i<count($arr_folder_aid);$i++)
                           {
                               if($arr_folder_aid[$i]!=$parent)
                                $kq1=db_update('tbl_project_attribute')->fields(array('parent_id' => $parent))->condition('aid', $arr_folder_aid[$i], "=")->execute();
                           }
                           
                       }
                       if($kq>=0||$kq1>0){
                           print 1;
                       }
                       else{
                           print 0;
                       }
                       
                       
                   }
                   else if($flag=="share_file"){
                        $arr_fid=  isset($_POST["arr_fid"])?$_POST["arr_fid"]:array();
                       $arr_link=array();
                       //$base_url."/sites/default/files/projects/". $file->nid . '/' . $file->path;
                       if(count($arr_fid)>0){
                           
                           foreach ($arr_fid as $file){
                               $arr_link[]=$base_url."/sites/default/files/projects/". $nid . '/' . $file;
                           }
                       }
                       $email=isset($_POST["email"])?$_POST["email"]:array();
                       if(count($email)>0){
                           $to='';
                            $subject="[SHARE FILES]";
                            $message="<div>You have been shared file from <b>$user->mail</b></div> <br>";
                                 foreach($arr_link as $link){

                                     $message.="<br><a href='".$link."'>".$link."</a>";
                                 }
                            $error='';
                             foreach ($email as $mail){
                                     $to=$mail;
                                
                                    $kq=project_mail($from = 'default_from', $to, $subject, $message);
                                    if($kq!=true){
                                       $error.= "Email ".$mail." not correct";
                                    }
                                           
                            }
                            if($$error==''){
                                print "Email has been sent!";
                            }
                            else{
                                print $error;
                            }
                       }
                       else{
                            print "Email's address not correct!";
                       }
                       
                      
                       
                   }    
                   
                   
                   
                   
                 
                 else if($flag=="information_member"){
                     $nid=$_POST["nid"];
                     $uid=isset($_POST["uid"])?$_POST["uid"]:0;
                     $arr_mb_id=isset($_POST["arr_mb_id"])?$_POST["arr_mb_id"]:array();
                     if($uid>1){
                         print theme("member_info",array("nid"=>$nid,"uid"=>$uid,"arr_mb_id"=>$arr_mb_id));
                         
                     }
                     
                 }
                  else if($flag=="update_team_project"){
                       $page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $feed_project = array();
                        if(isset($_POST["filter"])&& $_POST["filter"]==1){
                            $start_feed=  isset($_POST["start_date"])?$_POST["start_date"]:"";
                            $end_feed=  isset($_POST["end_date"])?$_POST["end_date"]:"";
                            
                            if(!empty($start_feed) && !empty($end_feed)){
                                $start_feed= strtotime(date("Y/m/d 00:00:00",strtotime($start_feed)));
                                $end_feed=  strtotime(date("Y/m/d 23:59:59",strtotime($end_feed)));
                                 $qr_feed=db_query("SELECT * FROM tbl_history WHERE nid=$nid AND created >=$start_feed AND created <=$end_feed ORDER BY created DESC LIMIT 0,100");
                            }
                            elseif(empty($start_feed) && !empty($end_feed)){
                                
                               
                                $end_feed=  strtotime(date("Y/m/d 23:59:59",strtotime($end_feed)));
                                 $qr_feed=db_query("SELECT * FROM tbl_history WHERE nid=$nid AND  created <=$end_feed ORDER BY created DESC LIMIT 0,100");
                            }
                            elseif(!empty($start_feed) && empty($end_feed)){
                                $start_feed= strtotime(date("Y/m/d 00:00:00",strtotime($start_feed)));
                                
                                 $qr_feed=db_query("SELECT * FROM tbl_history WHERE nid=$nid AND created >=$start_feed  ORDER BY created DESC LIMIT 0,100");
                            }
                            else{
                                $qr_feed=db_query("SELECT * FROM tbl_history WHERE nid=$nid ORDER BY created DESC LIMIT 0,100");
                            }
                            
                         }else{
                            $qr_feed=db_query("SELECT * FROM tbl_history WHERE nid=$nid ORDER BY created DESC LIMIT 0,100");
                         }
                       foreach ($qr_feed as $feed){
                           $feed_project[]=$feed;
                       }
                       
                      print theme("update_team",array("project_feed"=>$feed_project,"nid"=>$nid));
                  } else if($flag=="add_member_project"){
                     
                      $qr_list_member=  db_query("SELECT us.uid FROM users us WHERE us.uid NOT IN (SELECT tb_as.uid FROM tbl_assign_project tb_as WHERE tb_as.nid=$nid) AND us.uid > 1");
                      $arr_uid=array();
                      foreach ($qr_list_member as $mb){
                          $arr_uid[]=$mb->uid;
                      }
                      //print json_encode($arr_uid);
                      print theme("add_member_project",array("list_non_member"=>$arr_uid,"nid"=>$nid));
                  }else if($flag=="insert_member_to_project"){
                     $arr_mb=isset($_POST["arr_mb"]) ?$_POST["arr_mb"]:array();
                     if(count($arr_mb)>0){
                         foreach ($arr_mb as $n_mb){
//                             db_insert("tbl_history")->fields(array(
//                                'nid' => $nid,
//                                'uid' => $user->uid,
//                                'title' => $node->title,
//                                'content' => 'restored the ' . $node->type,
//                                'link' => 'node/' . $node->nid,
//                                'action' => 0,
//                                'created' => time()
//                            ))->execute();
                             db_insert("tbl_assign_project")->fields(array(
                                'nid' => $nid,
                                'uid' => $n_mb,
                                'client'=>0,
                                'created' => time()
                            ))->execute();
                         }
                         
                     }
                      
                  }
            }
     }
}


function zipFilesAndDownload(){
    global $base_url;
    if(isset($_REQUEST["nid"])){
        if($_REQUEST["nid"]>0){
            $nid=$_REQUEST["nid"];
                    $error = ""; //error holder
                    global $base_path,$base_root;
                   // print $base_path.'----'.$base_root;
                    $post = $_POST;
                    $path=  dirname(__FILE__);
                    $file_names=$post['arr_fid'];
                     $file_path = str_replace("all".DS."modules".DS."project",  "default" . DS . "files". DS ."projects". DS . $nid .DS, $path);
                     $archive_file_name = time() . ".zip"; // Zip name
                     //$post['arr_fid'];


                   // print "<pre>".print_r($post['arr_fid'])."</pre>";
                        // $file_folder = $path; // folder to load files


                    // if (!is_writable($file_path)) { die('directory not writable'); }

                    $zip = new ZipArchive();
                    //create the file and throw the error if unsuccessful
                    if ($zip->open($file_path.$archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
                    exit("cannot open <$archive_file_name>\n");
                    }
                    //add each files of $file_name array to archive
                    foreach($file_names as $files)	{
                         if (!file_exists($file_path.$files)) { die($files.' does not exist'); }
                          if (!is_readable($file_path.$files)) { die($files.' not readable'); }
                          //print $zip->addFromString($file_path.$files,$files);
                          $zip->addFromString(basename($file_path.$files),  file_get_contents($file_path.$files));
                    }
                   // $zip->sendZip("PHP_Compress_specific_file_example.zip");
                    $zip->close();
//                    $zipped_size = filesize($archive_file_name);
//                    header("Content-Description: File Transfer");
//                    header("Content-type: application/zip"); 
//                    header("Content-Type: application/force-download");// some browsers need this
//                    header("Content-Disposition: attachment; filename=$archive_file_name");
//                    header('Expires: 0');
//                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
//                    header('Pragma: public');
//                    header("Content-Length:". " $zipped_size");
//                    readfile($archive_file_name);
                       
                      $link_file= $base_url."/sites/default/files/projects/".$nid."/$archive_file_name";
                       print json_encode(array('filename' =>$archive_file_name, 'link_file' => $link_file));
    }
    
  }
  }




function get_document_filter_date($nid, $is_client,$date1, $date2) {
    $where_show_client = "";
    if ($is_client == 1)
        $where_show_client = " AND send_client=1";
    $document = $list = array();
    $number_more = 0;
    $list_document = db_query("SELECT pa.* FROM tbl_project_attribute pa WHERE pa.type=3 AND pa.nid =$nid AND (pa.created BETWEEN  $date1 AND $date2) AND deleted =0 $where_show_client order by pa.subject, pa.created desc $limit");
	
	foreach ($list_document as $info) {
        $info->number_comment = number_comment('todo', $info->aid);
        $list[] = $info;
    }
    $number_all = db_query("SELECT count(*) FROM tbl_project_attribute pa WHERE pa.type=3 AND pa.nid =$nid AND (pa.created BETWEEN  $date1 AND $date2) AND deleted =0 $where_show_client order by pa.created desc")->fetchField();
    if ($number_all > 6) {
        $number_more = $number_all - 6;
    }
    $document['list_document'] = $list;
    $document['number_more'] = $number_more;
    $document['number_all'] = $number_all;
    return $document;
}

function get_files_filter($flag,$where, $is_client = 0, $limit = '', $id = 0, $tid = 0, $aid = 0, $fid = 0) {
    $list_files = array();
    global $user;
    switch ($flag) {
        case 'project':
            $attachfiles = db_query("SELECT * FROM tbl_files WHERE nid = $id  $where AND deleted = 0 ORDER BY created desc " . $limit);
			
            foreach ($attachfiles as $file) {
                $info_label = db_query("SELECT * FROM tbl_file_label WHERE fid=$file->fid");
                foreach ($info_label as $label) {
                    $file->labels[] = $label;
                }
                if ($file->tid > 0 && $file->aid == 0) {
                    $file->send_client = db_query("SELECT send_client FROM tbl_todolist WHERE tid=$file->tid")->fetchField();
                    $file->number_comment = db_query("SELECT count (*) FROM tbl_project_comment WHERE tid = $file->tid AND aid=0 AND deleted=0")->fetchField();
                } else {
                    $file->send_client = db_query("SELECT send_client FROM tbl_project_attribute WHERE aid=$file->aid")->fetchField();
                    $file->number_comment = db_query("SELECT count (*) FROM tbl_project_comment WHERE aid = $file->aid AND deleted=0")->fetchField();
                }


                if ($is_client == 1 && $file->send_client == 1) {
                    $list_files[] = $file;
                } else if ($is_client == 0) {
                    $list_files[] = $file;
                }
            }
            return $list_files;
            break;
        case 'file':
			
            $attachfiles = db_query("SELECT * FROM tbl_files WHERE fid= $fid $where AND  deleted = 0 ORDER BY created desc " . $limit);
            foreach ($attachfiles as $file) {
                $info_label = db_query("SELECT * FROM tbl_file_label WHERE fid=$file->fid");
                foreach ($info_label as $label) {
                    $file->labels[] = $label;
                }
                if ($file->tid > 0 && $file->aid == 0) {
                    $file->send_client = db_query("SELECT send_client FROM tbl_todolist WHERE tid=$file->tid")->fetchField();
                    $file->number_comment = db_query("SELECT count (*) FROM tbl_project_comment WHERE tid = $file->tid AND aid=0 AND deleted=0")->fetchField();
                } else {
                    $file->send_client = db_query("SELECT send_client FROM tbl_project_attribute WHERE aid=$file->aid")->fetchField();
                    $file->number_comment = db_query("SELECT count (*) FROM tbl_project_comment WHERE aid = $file->aid AND deleted=0")->fetchField();
                }

                $list_files[] = $file;
            }
            return $list_files;
            break;
        case 'user':
			
            $attachfiles = db_query("SELECT * FROM tbl_files WHERE uid= $id $where AND deleted = 0 ORDER BY created desc " . $limit);
            foreach ($attachfiles as $file) {
                $info_label = db_query("SELECT * FROM tbl_file_label WHERE fid=$file->fid");
                foreach ($info_label as $label) {
                    $file->labels[] = $label;
                }
                if ($file->tid > 0 && $file->aid == 0) {
                    $file->send_client = db_query("SELECT send_client FROM tbl_project_attribute WHERE tid=$file->tid")->fetchField();
                    $file->number_comment = db_query("SELECT count (*) FROM tbl_project_comment WHERE tid = $file->tid AND aid=0 AND deleted=0")->fetchField();
                } else {
                    $file->send_client = db_query("SELECT send_client FROM tbl_project_attribute WHERE aid=$file->aid")->fetchField();
                    $file->number_comment = db_query("SELECT count (*) FROM tbl_project_comment WHERE aid = $file->aid AND deleted=0")->fetchField();
                }

                $list_files[] = $file;
            }
            return $list_files;
            break;
    }
}






function discover($page = true) {
    global $base_url, $user, $msw_detect;
    $me = user_load($user->uid);
    $preference = isset($me->field_discover_preference['und'][0]['value']) ? unserialize($me->field_discover_preference['und'][0]['value']) : '';
    $latitude = isset($me->field_profile_latitude['und'][0]['value'])?$me->field_profile_latitude['und'][0]['value']:'';
    $longitude = isset($me->field_profile_longitude['und'][0]['value'])?$me->field_profile_longitude['und'][0]['value']:'';
    $looking = isset($preference['looking']) ? $preference['looking'] : '';
    $type = isset($preference['type']) ? $preference['type'] : '';
    $select_state = isset($preference['state']) ? $preference['state'] : '';
    $city = isset($preference['city']) ? $preference['city'] : '';
    $select_category = isset($preference['category']) ? $preference['category'] : array();
    $tags = isset($preference['tags']) ? $preference['tags'] : array();
    $keywords = isset($preference['keywords']) ? $preference['keywords'] : array();
    $distance = isset($preference['distance']) ? $preference['distance'] : 0;
    $cards = $starred = $list = $archived = $updates = array();
    $authors = array();
    $where="";
    $where = (!empty($looking)) ? " AND client = $looking" : "";
    $uids = array();
    if(!empty($select_state)){
        $uids[]=0;
        $listuids = db_query("SELECT entity_id FROM field_data_field_profile_state WHERE field_profile_state_tid = $select_state");
        foreach ($listuids as $info)
        $uids[] = $info->entity_id;
    }
    if(count($uids)>0){
        $where.=" AND n.uid IN (".implode(",",$uids).")";
    }if(count($select_category)>0){
       $where.=" AND fc.field_project_category_tid IN (".implode(",",$select_category).")";
    }if(count($tags)>0){
        $where.=" AND ft.field_project_tags_tid IN (".implode(",",$tags).")";
    } if(count($keywords)>0){
        foreach ($keywords as $keyword){
            if(!empty($keyword)){
            $where.=" AND fb.body_value like '%".$keyword."%'";
            }
        }
    } if(!empty($city)){
        $where.="fcity.field_profile_city_value like'%".$city."%'";
    }
    $uids_distance= distance($latitude, $longitude, $distance);
   if(count($uids_distance)>0){
      $where.=" AND n.uid IN (".implode(",",$uids_distance).")";
   }
        $result = db_query("SELECT n.nid,n.promote as star,n.sticky as active 
            FROM node n
            INNER JOIN tbl_assign_project as tp ON n.nid = tp.nid
            LEFT JOIN field_data_field_project_category as fc ON fc.entity_id = n.nid
            LEFT JOIN field_data_field_project_tags ft ON n.nid = ft.entity_id
             LEFT JOIN field_data_field_profile_city fcity ON n.nid = fcity.entity_id
            INNER JOIN field_data_body fb ON n.nid = fb.entity_id
            WHERE n.type='project' AND n.sticky = 0 AND n.status = 1 $where
            GROUP BY n.nid
            ORDER BY n.promote desc,n.created asc");
    foreach ($result as $object) {
        $project = node_load($object->nid);
        $project->alias = 'node/' . $object->nid;
        $project->star = $object->star;
        $project->active = $object->active;
        $project->assigned_user = array();
        $user_assign = db_query("SELECT * FROM tbl_assign_project WHERE nid = $project->nid");
        foreach ($user_assign as $user_info) {
            $user_info = user_load($user_info->uid);
            $project->assigned_user[$user_info->uid] = $user_info;
        }
        $cards[] = $project;
    }
    if ($msw_detect->isMobile()) {
        $path_theme = dirname(__FILE__) . DS . 'themes' . DS;
        return frender($path_theme . 'projects_mobile.tpl.php', array('cards' => $cards, 'starred' => $starred, 'list' => $list, 'archived' => $archived, 'updates' => $updates, 'authors' => $authors, 'page' => $page));
    } else {
        if (isset($_GET['from_workspace']))
            print theme('project_all', array('cards' => $cards, 'starred' => $starred, 'list' => $list, 'archived' => $archived, 'updates' => $updates,
                        'authors' => $authors, 'page' => $page));
        else
            return theme('project_all', array('cards' => $cards, 'starred' => $starred, 'list' => $list, 'archived' => $archived, 'updates' => $updates,
                        'authors' => $authors, 'page' => $page));
    }
}

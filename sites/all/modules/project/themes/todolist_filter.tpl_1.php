<?php
global $base_url, $user;
$exist_todo_completed = 0;
foreach ($categories_completed as $info) {
    $exist_todo_completed++;
    break;
}
?>
<div id="todolists_page" class="panel sheet project inactive" <?php if($project->sticky) print' data-behavior ="read_only" data-status="archived"'?>>
    <header>   <h1 class="title"><a href="<?= $base_url ?>/node/<?= $project->nid ?>" class="link_page" data-nid="<?=$project->nid?>"><?= $project->title ?></a></h1>    </header>
    <div id="popup-done" style="display: none"> </div>
    <div class="panel sheet todolists has_sidebar <?= ($exist_todo_completed == 0 && count($todolist) == 0) ? 'blank_slate' : '' ?>">
        <header class="has_buttons header_title_button">
            <h1 class="inactive_title">See all to-do lists</h1>
            <div class="active_title"><h1>To-do lists</h1></div>
            <span class="position_reference"><button class="action_button" onclick="action_todolist(this,'add_new_todolist')" data-behavior="new_todolist">Add to-do list</button><div class="blank_slate_arrow"></div></span>
            <div class="clear"></div>
        </header>
        <div id="content_page" class="sheet_body">

            <?php if ($exist_todo_completed > 0 && count($todolist) == 0) { ?>
                <div class="only_completed">
                    <div class="notice">
                        <h3>All of the to-dos on this project are complete.</h3>
                        <p><button class="action_button" onclick="action_todolist(this,'add_new_todolist_only_completed')"> Add to-do list</button> or <a href="<?= $base_url ?>/todolists/completed?nid=<?= isset($_GET['nid']) ? $_GET['nid'] : 0 ?>">see all completed to-dos</a></p>
                    </div>
                </div>
            <?php } else { ?>
                <article class="blank_slate">
                    <header>
                        <img width="223" height="171" src="<?= $base_url ?>/sites/all/themes/adaptivetheme/basecamp/images/blank_slate_icon_todos.png" alt="Todo Lists">
                    </header>

                    <div class="blank_slate_body">
                        <h1>Create your first To-do list and get organized.</h1>
                        <h3>To-dos help you keep track of all the little things that need to get done. You can add them for yourself or assign them to someone else.</h3>
                    </div>
                </article>
            <?php } ?>
            <section id="todolists" class="todos <?php if (isset($_POST['filter']) && (is_numeric($_POST['assign_uid']) || !empty($_POST['assign_date']))) print 'filtered'; ?>">
                <h3 data-behavior="todo_filter_header" class="filtered_header" id="showing-to-dos-assigned">
                    Showing to-dos
                    <span data-behavior="assigned_to" style="display: inline;">
                        <?php
                        if (isset($_POST['assign_uid']) && !empty($_POST['assign_uid'])) {
                            print 'assigned to <span data-behavior="value" style="display: inline;">';
                            if ($_POST['assign_uid'] > 0) {
                                print $list_assign_to[$_POST['assign_uid']]->assign_to_mail;
                            } else
                                print '(Unassigned)';
                            print '</span>';
                        }
                        ?>
                    </span>
                    <?php
                    if (isset($_POST['assign_uid']) && isset($_POST['assign_date']) && !empty($_POST['assign_uid']) && !empty($_POST['assign_date']))
                        print '<span data-behavior="filter_and">and</span>';
                    if (isset($_POST['assign_date'])) {
                        ?>
                        <span data-behavior="filter_due">
                            due <span data-behavior="value"><?= strtolower($list_assign_date[$_POST['assign_date']]['value']) ?></span>
                        </span>
                    <?php }
                    ?>
                    - <a onclick="show_all_todos()" class="decorated">Show all to-dos</a>
                </h3>
                <article class="todolist new category-todo-new" style="display: none">
                    <?php print theme('add_todolist_layout', array('nid' => $project->nid, 'member_client' => $member_client, 'with_clients' => $project->field_with_clients['und'][0]['value'])) ?>
                </article>
                <div class="left_todo_content" >
                <ul class="todolists show_by_list" style="display: none">                
                    <?php
                    $list_category_todo = array();
                    foreach ($todolist as $category_todo) {
                        $list_category_todo[] = $category_todo;
                        ?>
                        <li id="sortable_todolist_<?= $category_todo->tid ?>">
                            <article data-behavior="" class="todolist" id="todolist_<?= $category_todo->tid ?>">
                                <div class="box_category_todo" id="box_category_todo_<?= $category_todo->tid ?>">
                                    <?php print theme('category_todolist_layout', array('category_todo' => $category_todo, 'member_client' => $member_client, 'nid' => $project->nid, 'with_clients' => $project->field_with_clients['und'][0]['value'])) ?>
                                </div>
                                <ul class="todos empty"> 
                                    <?php
                                    if (isset($category_todo->todos)) {
                                        foreach ($category_todo->todos as $todo) {
                                            print theme('todolist_layout', array('todo' => $todo, 'members_all' => $members_all, 'new_todo' => 'append', 'filter_to_dos' => 1, 'show_record_completed' => 1,'project'=>$project));
                                        }
                                    }
                                    ?>
                                </ul>
                                <?php print theme('add_a_todo_layout', array('members_all' => $members_all, 'category_todo' => $category_todo, 'nid' => $project->nid)) ?>
                                <ul class="completed truncated"> </ul>
                            </article>
                        </li>
                    <?php } ?>
                </ul>
                
                
                <ul class="todolists show_by_date">                
                    <?php
                    $list_category_todo = array();
                    $pass=array();
                    $now_todo=array();
                    $tomorrow_todo=array();
                    $feature_todo=array();
                    $str_list_date=array();
                    $list_todo=array();
                    foreach ($todolist as $category_todo) {
                        $list_category_todo[] = $category_todo;
                        ?>
                       
                        <?php
                        if (isset($category_todo->todos)) {
                            foreach ($category_todo->todos as $todo) {
                                if($todo->assign_date!=''){
                                    $list_todo[]=$todo;
                                        $now_td=date('Y-m-d');
                                   $today=strtotime($now_td);
                                   $tomorrow_td=strtotime("+1 day". $now_td);
                                   $assign_date=$todo->assign_date;
                                   $lst_assign=  explode("-", $assign_date);
                                   
//                                   $date_for_js= $assign_date;
                                   $date_for_js=date("Y-n-j", strtotime($assign_date));
                                   $lst_assign_js=  explode("-", $date_for_js);
                                   $str_list_date[]=$lst_assign_js[1]."/".$lst_assign_js[2]."/".$lst_assign_js[0];
                                   $assign_date=strtotime($assign_date);
                                   if($assign_date<$today){
                                       $pass[]=$todo;

                                   }
                                   else if($assign_date==$today){

                                       $now_todo[]=$todo;
                                   }
                                   else if($assign_date>$today){
                                       if($assign_date==$tomorrow_td)
                                            $tomorrow_todo[]=$todo;
                                       else if($assign_date>$tomorrow_td){
                                           $feature_todo[]=$todo;
                                       }
                                   }
                                  }
                                  else{
                                      
                                      $feature_todo[]=$todo;
                                  }
                              

                            }
                    }}
                        ?>
                             
                 
                        
                        <div class="content_tomorrow_todo all_content_todo">
                            <div class="title_todo"><?=$date_filter?></div>
                            <div class="list_tomorrow_todo">
                                <?php if(count($list_todo)>0):?>
                                    <?php for($i=0;$i<count($list_todo);$i++):?>
                                
                                        <?php print theme('todolist_layout', array('todo' => $list_todo[$i], 'members_all' => $members_all, 'new_todo' => 'append', 'filter_to_dos' => 1, 'show_record_completed' => 1,'project'=>$project));?>
                                
                                    <?php endfor;?>
                                <?php endif;?>
                                
                            </div>
                        </div>
                       
                    
                    
                </ul>
                
                </div>
                <div class="right_calendar_date">
                    <div id="datepicker_todo"></div>
                    
                </div>
            </section>
        </div>
        
        
        
        <aside style="display: none">
            <?php if (count($todolist) > 0) { ?>
                <p>
                    Show to-dos assigned to <br>
                    <select onchange="filter(this,'assign_to')" id="assign_to">
                        <option value="">Anyone</option>
                        <?php foreach ($list_assign_to as $key_assign_to => $assign) {
                            ?>
                            <option value="<?= $key_assign_to ?>" <?php if (isset($_POST['assign_uid']) && $_POST['assign_uid'] == $key_assign_to && is_numeric($_POST['assign_uid'])) print 'selected=""' ?>><?= $assign->assign_to_mail ?> (<?= $assign->number ?>)</option>
                        <?php }
                        ?>
                    </select>
                </p>
                <p>
                    Show to-dos that are due <br>
                    <select onchange="filter(this,'assign_date')" id="assign_date">
                        <option value="">Anytime</option>
                        <?php foreach ($list_assign_date as $key_assign_date => $assign) {
                            ?>
                            <option value="<?= $key_assign_date ?>" <?php if (isset($_POST['assign_date']) && $_POST['assign_date'] == $key_assign_date) print 'selected=""' ?>><?= $assign['value'] ?> (<?= $assign['number'] ?>)</option>
                        <?php } ?>
                    </select>
                </p>
            <?php } ?>
            <p><a href="<?= $base_url ?>/todolists/completed?nid=<?= isset($_GET['nid']) ? $_GET['nid'] : 0 ?>" class="decorated link_page">See completed to-dos</a></p>
            <?php if (count($list_category_todo) > 0) { ?>
                <h5>Current to-do lists</h5>
                <ul class="category_todolist">
                    <?php foreach ($list_category_todo as $category_todo) { ?>
                        <li><a href="<?= $base_url ?>/todolist/<?= $category_todo->tid ?>" class="decorated link_page"><?= $category_todo->name ?></a></li>
                    <?php } ?>
                </ul>
            <?php } ?>
            <h5>Completed to-do lists</h5>
            <ul class="category_todolist">
                <?php foreach ($categories_completed as $category_todo) { ?>
                    <li><a href="<?= $base_url ?>/todolist/<?= $category_todo->tid ?>" class="decorated link_page"><?= $category_todo->name ?></a></li>
                <?php } ?>
            </ul>

        </aside>
    </div>

</div>

<style type="text/css">
	.specialDate a{ background-color: #444 !important; }
	</style>
<script src="<?= $base_url ?>/sites/all/libraries/jquery.autosize.min.js" ></script>
<script>
    jQuery("document").ready(function(){

                var arr_test=[];
                 var datesArray=[];
                <?php for($i=0;$i<count($str_list_date);$i++):?>
                    datesArray.push('<?=$str_list_date[$i]?>') ;  
                <?php endfor;?>

         
		jQuery("#datepicker_todo").datepicker({
			 inline: true,
			 beforeShowDay: function (date) {
				var theday = (date.getMonth()+1) +'/'+ 
							date.getDate()+ '/' + 
							date.getFullYear();
					return [true,jQuery.inArray(theday, datesArray) >=0?"specialDate":''];
				}, 
                         onSelect: function(dateText) {
                               // alert("Selected date: " + dateText + "; input's current value: " + this.value);
                                jQuery.post("<?= $base_url ?>/todolist_filter",{"nid":"<?= $project->nid ?>",date:this.value,'filter':1}, function (data){
                                    jQuery(".content_todos").append(data);
                                });
                              }
                                
		});
	
          
          
         action_tooltip_todo();
         load_page();
          jQuery('.todo_content').autosize();
        <?php if($project->sticky==0) {?>
                sortable_todo();
                sortable_todolist();
                <?php } ?>
                    
       
    });
    function filter(me,flag){
        if(flag=='assign_to'||flag=='assign_date'){
            //jQuery('.todo.show').hide();
            var assign_uid=jQuery("#assign_to").val();
            var assign_date=jQuery("#assign_date").val();
            jQuery.post("<?= $base_url ?>/filter-todolist",{"nid":"<?= $project->nid ?>","assign_uid":assign_uid,"assign_date":assign_date,'filter':1}, function (data){
                jQuery("#block-system-main").html(data);
            });
           
        }
        
    }
    function show_all_todos(){
        jQuery.post("<?= $base_url ?>/filter-todolist",{"nid":"<?= $project->nid ?>","assign_uid":'',"assign_date":''}, function (data){
            jQuery("#block-system-main").html(data);
        });
    }
</script>




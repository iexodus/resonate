<?php
global $base_url, $user;
$exist_todo_completed = 0;
foreach ($categories_completed as $info) {
    $exist_todo_completed++;
    break;
}
?>

<div id="todolists_page" class="panel sheet project inactive" <?php if($project->sticky) print' data-behavior ="read_only" data-status="archived"'?>>
   
    <div id="popup-done" style="display: none"> </div>
    <div class="panel sheet todolists has_sidebar <?= ($exist_todo_completed == 0 && count($todolist) == 0) ? 'blank_slate' : '' ?>">
        <header class="has_buttons header_title_button">
            <h1 class="inactive_title">See all to-do lists</h1>
            <div class="active_title"><h1>To-do lists</h1></div>
            <div class="header_filter"><div class="title_project_header"></div><div class="conteiner_filter "><span class="filter_date filter_all" onclick="date_filter(this)" nid="<?=$project->nid?>">Date</span><span class="filter_list filter_all active"  >List</span><span class="position_reference"><button class="action_button" onclick="action_todolist(this,'add_new_todolist')" data-behavior="new_todolist">Add to-do list</button><div class="blank_slate_arrow"></div></span></div></div>
            
            <div class="clear"></div>
        </header>
         <div class="left_todo_content">
        <div id="content_page" class="sheet_body">

           
            <section id="todolists" class="todos <?php if (isset($_POST['filter']) && (is_numeric($_POST['assign_uid']) || !empty($_POST['assign_date']))) print 'filtered'; ?>">
               
                <article class="todolist new category-todo-new" style="display: none">
                    <?php print theme('add_todolist_layout', array('nid' => $project->nid, 'member_client' => $member_client, 'with_clients' => $project->field_with_clients['und'][0]['value'])) ?>
                </article>
                <ul class="todolists">
                    
                    <?php
                    $fl=0;
                    $list_category_todo = array();
                    $count_todo=0;
                    $now_todo=array();
                    foreach ($todolist as $category_todo) {
                        
                        if (isset($category_todo->todos)) {
                            foreach ($category_todo->todos as $todo) {
                                if($todo->assign_date!=''){
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
                                  
                                    if($assign_date==$today){

                                       $now_todo[]=$todo;
                                   }
                                  
           

                            }
                           }
                        
                        }
                        
                        
                        $list_category_todo[] = $category_todo;
                        $class="list_one";
                        if($fl%2==0){
                             $class="list_one";
                        }
                        else{
                             $class="list_two";
                        }
                        ?>
                        <li id="sortable_todolist_<?= $category_todo->tid ?>" class="<?=$class?>">
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
                    <?php $fl++;} ?>
                </ul>

            </section>
        </div>
       <?php if(isset($next_item)&&$next_item>0):?>
             <div class="show_more_todo"><a>Show more..</a></div>
     <?php endif;?>
    </div>
    <div class="right_calendar_date">
                        <div id="datepicker_todo"></div>
                        <div class="today_list_todo">
                           <?php
                                if(count($now_todo)>0):?>
                            <div class="today_title" style="margin-top:20px; font-weight: bold;font-size: 18px">
                                <?php
                                    $newDate = date("M d", strtotime($now_todo[0]->assign_date));
                                ?>
                                <?=$newDate?>
                                
                            </div>
                             <?php for($i=0;$i<count($now_todo);$i++):?>
                                
                                        <?php print theme('todolist_layout', array('todo' => $now_todo[$i], 'members_all' => $members_all, 'new_todo' => 'append', 'filter_to_dos' => 1, 'show_record_completed' => 1,'project'=>$project));?>
                                
                                    <?php endfor;?>
                               
                            <?php endif;  ?>
                        </div>
                        <div class="append_filter_list"></div>

      </div>
</div>

</div>
 
<script src="<?= $base_url ?>/sites/all/libraries/jquery.autosize.min.js" ></script>
<script>
    jQuery("document").ready(function(){
        
        
        
//         var arr_test=[];
//                 var datesArray=[];
//                <?php for($i=0;$i<count($str_list_date);$i++):?>
//                    datesArray.push('<?=$str_list_date[$i]?>') ;  
//                <?php endfor;?>
//
//         
		jQuery("#datepicker_todo").datepicker({
			 inline: true,
			
                         onSelect: function(dateText) {
                              //alert("Selected date: " + dateText + "; input's current value: " + this.value);
//                              var d = new Date();
//                                    var strDate =(d.getMonth()+1) + "/" + d.getDate() + "/" +d.getFullYear() ;
//                                  var click=this.value;
//                                  if(click==strDate){
//                                      alert("ok");
//                                  }
//                                  else alert("no");
                                jQuery.post("<?= $base_url ?>/todolist_filter",{"nid":"<?= $project->nid ?>",date:this.value,'filter':"fiter_list"}, function (data){
                                    jQuery(".append_filter_list").html(" ").append(data);
                                });
                              }
                                
		});
    //jQuery("#datepicker_todo").datepicker();
         action_tooltip_todo();
         load_page();
          jQuery('.todo_content').autosize();
       
    });
    function date_filter(me){
         var nid = jQuery(me).attr("nid");
                jQuery(".sub_content_todos").html(" ");
                jQuery.post("<?= $base_url ?>/todolist_filter", {"nid":nid, 'sort':"by_date"}, function(data) {
                    jQuery(".sub_content_todos").html(data);
                });
                jQuery(".content_all").hide();
                jQuery(".content_todos").show();
        
    }
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
            jQuery(".sub_content_todos").html(data);
        });
    }
</script>

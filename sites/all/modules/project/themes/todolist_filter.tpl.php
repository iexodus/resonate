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
            <div class="header_filter"><div class="title_project_header"></div><div class="conteiner_filter"><span class="filter_date filter_all active" >Date</span><span class="filter_list filter_all" onclick="list_filter(this)" nid="<?=$project->nid?>">List</span><span class="position_reference"><button class="action_button" onclick="action_todolist(this,'add_new_todolist')" data-behavior="new_todolist">Add to-do list</button><div class="blank_slate_arrow"></div></span></div></div>
            <div class="goback_todolist">Go back</div>
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
            <section id="todolists" class="todos">
              
                <div class="left_todo_content" >
               
                
                
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
                                    jQuery(".content_todos").html(data);
                                });
                              }
                                
		});
                jQuery(".goback_todolist").click(function(){
                        jQuery.post("<?= $base_url ?>/todolist_filter",{"nid":"<?= $project->nid ?>",'sort':"by_date"}, function (data){
                                    jQuery(".content_todos").html(data);
                                });
                
                });
	
          
          
         action_tooltip_todo();
         load_page();
          jQuery('.todo_content').autosize();
        <?php if($project->sticky==0) {?>
                sortable_todo();
                sortable_todolist();
                <?php } ?>
                    
       
    });
    function list_filter(me){
         var nid = jQuery(me).attr("nid");
                jQuery(".sub_content_todos").html(" ");
                jQuery.post("<?= $base_url ?>/todolist_filter", {"nid":nid, 'sort':"by_list"}, function(data) {
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
                jQuery(".sub_content_todos").html(data);
            });
           
        }
        
    }
    function show_all_todos(){
        jQuery.post("<?= $base_url ?>/filter-todolist",{"nid":"<?= $project->nid ?>","assign_uid":'',"assign_date":''}, function (data){
            jQuery("#block-system-main").html(data);
        });
    }
</script>




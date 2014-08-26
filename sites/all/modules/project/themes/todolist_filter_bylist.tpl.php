<?php
global $base_url, $user;
$exist_todo_completed = 0;
foreach ($categories_completed as $info) {
    $exist_todo_completed++;
    break;
}
?>

<div <?php if($project->sticky) print' data-behavior ="read_only" data-status="archived"'?>>
   
    
    <div class="panel sheet todolists has_sidebar <?= ($exist_todo_completed == 0 && count($todolist) == 0) ? 'blank_slate' : '' ?>">
       
        

            
            <section class="todos <?php if (isset($_POST['filter']) && (is_numeric($_POST['assign_uid']) || !empty($_POST['assign_date']))) print 'filtered'; ?>">
                
          
                
                <ul class="todolists ">                
                    <?php
                    $list_category_todo = array();
                    $todo_arr=array();
                    $pass=array();
                    $now_todo=array();
                    $tomorrow_todo=array();
                    $feature_todo=array();
                    $str_list_date=array();
                    foreach ($todolist as $category_todo) {
                        $list_category_todo[] = $category_todo;
                        ?>
                       
                        <?php
                        if (isset($category_todo->todos)) {
                            foreach ($category_todo->todos as $todo) {
                               $todo_arr[]=$todo;

                            }
                    }}
                        ?>
                             
                    <div class="">
                       
                        
                        <div class="content_today_todo  all_content_todo">
                            <div class="list_today_todo">
                                <?php if(count($todo_arr)>0):?>
                                        <div class="today_title" style="margin-top:20px; font-weight: bold;font-size: 18px">
                                        <?php
                                            $newDate = date("M d", strtotime($todo_arr[0]->assign_date));
                                        ?>
                                        <?=$newDate?>

                                    </div>
                                    <?php for($i=0;$i<count($todo_arr);$i++):?>
                                
                                        <?php print theme('todolist_layout', array('todo' => $todo_arr[$i], 'members_all' => $members_all, 'new_todo' => 'append', 'filter_to_dos' => 1, 'show_record_completed' => 1,'project'=>$project));?>
                                
                                    <?php endfor;?>
                                <?php endif;?>
                                
                            </div>
                        </div>
                        
                   
                        
                    </div>
                    
                    
                </ul>
                
          
               
            </section>
       
        
        
        
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
            
        </aside>
    </div>

</div>

<style type="text/css">
	.specialDate a{ background-color: #444 !important; }
	</style>
<script src="<?= $base_url ?>/sites/all/libraries/jquery.autosize.min.js" ></script>
<script>
    jQuery("document").ready(function(){

//            

         
		jQuery("#datepicker_todo").datepicker({
			 inline: true,
			 beforeShowDay: function (date) {
//				var theday = (date.getMonth()+1) +'/'+ 
//							date.getDate()+ '/' + 
//							date.getFullYear();
//					return [true,jQuery.inArray(theday, datesArray) >=0?"specialDate":''];
				}, 
                         onSelect: function(dateText) {
                               // alert("Selected date: " + dateText + "; input's current value: " + this.value);
                               jQuery.post("<?= $base_url ?>/todolist_filter",{"nid":"<?= $project->nid ?>",date:this.value,'filter':"by_list"}, function (data){
                                    jQuery(".append_filter_todo").html(data);
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

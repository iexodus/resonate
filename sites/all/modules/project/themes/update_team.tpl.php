<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $base_url,$user;
?>
<div class="container_feed_project">
    <div class="content_feed_project">
        <div class="filter_feed_container">
            <div class="range_date">
                <label for="from">From</label>
                <input type="text" id="from" name="from" class="feed_from">
                <label for="to">to</label>
                <input type="text" id="to" name="to" class="feed_to">
            </div>
            <div class="container_button">
                <input nid="<?=$nid?>" type="button" class="action_button feed_filter" value="Filter" name="filter_feed">
                
            </div>
            <div class="clear"></div>
        </div>
        
         <?php if(count($project_feed)>0):?>
        <div class='content_feed_of_project'>
                                  <?php  foreach ($project_feed as $his)  :?>
                                    <?php
                                        $author=user_load($his->uid);
                                        $full_name = "";
                                                if (isset($author->field_profile_fname['und'][0]['value'])) {
                                                    $full_name.=$author->field_profile_fname['und'][0]['value'];
                                                }
                                                if (isset($author->field_profile_lname['und'][0]['value'])) {
                                                    $full_name.=' '.$author->field_profile_lname['und'][0]['value'];
                                                }
                                                trim($full_name);
                                                if (empty($full_name)) {
                                                    $full_name.=!empty($author->init)?$author->init:$author->mail;
                                                }
                                                
                                                
                                         $today_feed=date("d M Y");
                                         $today_compare=strtotime($today_feed);
                                         $seven_date_ago=strtotime("-7 day",strtotime($today_feed));
                                         $two_week_ago=strtotime("-2 week",strtotime($today_feed));
                                         $one_month_ago=strtotime("-1 month",strtotime($today_feed));
//                                         print date("d M Y",strtotime("-1 month",strtotime($today_feed)));
                                         $class_feed='';
                                         if($his->created==$today_compare){
                                             $class_feed="today_feed";
                                         }
                                         elseif ($his->created<$two_week_ago && $his->created>=$one_month_ago) {
                                             $class_feed="one_month_feed";
                                         }
                                         elseif($his->created<$seven_date_ago && $his->created>=$two_week_ago){
                                             $class_feed="two_week_feed";
                                         }
                                         elseif($his->created<$today_compare && $his->created>=$seven_date_ago){
                                             $class_feed="seven_day_feed";
                                         }
                                         else{
                                              $class_feed="older_feed";
                                         }
                                    ?>
                                <div class='avatar_content_feed <?=$class_feed?>'>
                                <div class='avatar author_avatar'>
                                        <img width="40" height="40" title="<?php print $full_name; ?>" src="<?php print $base_url . '/sites/default/files/profile/' . $author->field_profile_picture['und'][0]['value']; ?>" class="avatar">
                                    
                                </div>
                                <div class='history_feed'>
                                    <div class='name_of_author'><b><?=$full_name?></b></div>
                                    <div class='content_his'>
                                        <?=$his->content?> <a style="color:#1c5c76"><?= " ".$his->title;?></a> - <span style="color: gray;font-size: 13px;font-style: italic;">  <?= date(" M - d, Y" ,$his->created)?></span>
                                    </div>
                                </div>
                                    <div class='clear'></div>
                                </div>
                                
                                <?php  endforeach;?>
                                
                            </div>
           
              
        <?php endif;?>
        
        <div class="clear"></div>
    </div>
    
</div>


<script>
    jQuery(document).ready(function(){
        
        
        jQuery( "#from" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function( selectedDate ) {
        jQuery( "#to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    jQuery( "#to" ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function( selectedDate ) {
        jQuery( "#from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
        
        
        jQuery(".feed_filter").click(function(){
            var nid=jQuery(this).attr("nid");
            var start_date=jQuery(".feed_from").val();
            var end_date=jQuery(".feed_to").val();
//            alert(start_date+" "+end_date);
//            console.log(start_date+" "+end_date);
             var nid=jQuery(this).attr("nid");
                        jQuery.post("<?= $base_url ?>/icon_tool_click",{"nid":nid,"flag":"update_team_project","filter":1,"start_date":start_date,"end_date":end_date},function(data){
                           jQuery(".content_update_team").html(' ').append(data);
                            
                        });
                        jQuery(".content_all").hide();
                        jQuery(".content_update_team").show();
      
        });
       
    });
    
    </script>
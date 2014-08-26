<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $base_url,$user;
?>
<div class="content_list_people">
   <?php if(count($list_non_member)>0):?>
    <?php foreach ($list_non_member as $non_member):    ?>
        <?php 
            $non_mb=get_name_member($non_member);
        ?>
    <div class="sub_container_people" uid="<?=$non_member?>">
        
        <div class="headshot" original_style=" ">
              <input type="checkbox" class="ckb_add_member" uid="<?=$non_member?>" value="<?=$non_member?>"><a title="<?= $non_mb['full_name'] ?>" name="<?=$non_mb['full_name'] ?>" avt="<?= $non_mb['avatar'] ?>" class="mb_non_<?=$non_member?>"><img src="<?= $base_url ?>/sites/default/files/profile/<?= $non_mb['avatar'] ?>"> <?=$non_mb['full_name'] ?></a>
         </div>
    </div>
         
    <?php endforeach;?>
    <div class="clear"></div>
    <input type="button" class="action_button cancel_add_people" nid="<?=$nid?>" value="Cancel">
    <input type="button" class="action_button add_people" nid="<?=$nid?>" value="Add">
    <?php endif;?>
   
</div>
<script>
    jQuery(document).ready(function(){
         jQuery(".cancel_add_people").click(function(){
            
                
               jQuery(".container_list_member").html(" ");
                
            
            
        });
        jQuery(".add_people").click(function(){
            var nid=jQuery(this).attr("nid");
            var arr_mb=[];
            jQuery(".sub_container_people .ckb_add_member").each(function(){
                if(jQuery(this).is(':checked')){
                    var value=jQuery(this).val();
                    arr_mb.push(value);
                }
                
            });
            if(arr_mb.length>0){
                jQuery.post("<?=$base_url?>/icon_tool_click",{"flag":"insert_member_to_project","arr_mb":arr_mb,"nid":nid},function(data){
                    for(var i=0;i<arr_mb.length;i++){
                        var append='';
                            append+='<a class="member_click" mb_id="'+arr_mb[i]+'" nid="'+nid+'"><div class="content_member_team"> ';
                            append+=' <div class="clear"></div>';
                            append+='           <div class="container_user">';
                            append+='             <div class="box_img_user"></div>';
                            append+='                    <img src="<?=$base_url?>/sites/default/files/profile/'+jQuery(".mb_non_"+arr_mb[i]).attr("avt")+'">';
                            append+='            <div class="content_user">';
                            append+='            <div class="name_user">'+jQuery(".mb_non_"+arr_mb[i]).attr("name")+'</div>';
                            append+='            </div>';
                            append+='            </div>';
                            append+='</div>';
                            append+='</a>';
                         jQuery(".content_team_member .all_member_project").append(append);
                    }
                    jQuery(".container_list_member").hide();
                });
            }
        });
        
    });
</script>
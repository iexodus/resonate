<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?><?php global $user,$base_url?>
<?php
    $member=  user_load($uid);
     $full_name = "";
                                                if (isset($member->field_profile_fname['und'][0]['value'])) {
                                                    $full_name.=$member->field_profile_fname['und'][0]['value'];
                                                }
                                                if (isset($member->field_profile_lname['und'][0]['value'])) {
                                                    $full_name.=' '.$member->field_profile_lname['und'][0]['value'];
                                                }
                                                trim($full_name);
                                                if (empty($full_name)) {
                                                    $full_name.=!empty($member->init)?$member->init:$member->mail;
                                                }
?>

<div class=" conteine_member_infor" >
    <div class="content_member_infor">
        <div class="left_content_member">
            <div class="content_all_member_left" style="float: left;width:300px">
                <div ><h3 >Member</h3></div>
                <?php for($i=0;$i<count($arr_mb_id);$i++):?>
                    <?php $mb=user_load($arr_mb_id[$i]);?>
                    <?php                       
                                                $full_name_mb = "";
                                                if (isset($mb->field_profile_fname['und'][0]['value'])) {
                                                    $full_name_mb.=$mb->field_profile_fname['und'][0]['value'];
                                                }
                                                if (isset($mb->field_profile_lname['und'][0]['value'])) {
                                                    $full_name_mb.=' '.$mb->field_profile_lname['und'][0]['value'];
                                                }
                                                trim($full_name_mb);
                                                if (empty($full_name_mb)) {
                                                    $full_name_mb.=!empty($mb->init)?$mb->init:$mb->mail;
                                                }
                      ?>
                    <a class="member_click" mb_id="<?=$arr_mb_id[$i]?>" nid="<?=$nid?>"><img width="40" height="40" title="<?php print $title; ?>" src="<?php print $base_url . '/sites/default/files/profile/' . $mb->field_profile_picture['und'][0]['value']; ?>" class="avatar">
                        <span class="name_member_relarive"><?=$full_name_mb?></span></a><br>
                <?php  endfor;?>
            </div>
            
        </div>
        <div class="right_content_member" style="float:left; width: 400px" >
            <div ><h3 >Information</h3></div>
            <div style="padding:20px">
            <div class="name_member_mb"><b>Name: </b><?=$full_name?></div>
            <div class="mail_member"><b>Email:</b> <?=$member->mail?></div>
            
             <div class="position_member"><b>Position: </b><?=isset($member->field_profile_position['und'][0]['value'])?$member->field_profile_position['und'][0]['value']:'';?></div>
             <div class="education_member"><b>Education:</b> <?=isset($member->field_profile_education['und'][0]['value'])?$member->field_profile_education['und'][0]['value']:'';?></div>
             <div class="experience_member"><b>Experience:</b> <?=isset($member->field_profile_experience['und'][0]['value'])?$member->field_profile_experience['und'][0]['value']:'';?></div>
             <div class="publications_member"><b>Publications:</b> <?=isset($member->field_profile_publications['und'][0]['value'])?$member->field_profile_publications['und'][0]['value']:'';?></div>
             <div class="area_of_expertise_interest_member"><b>Area of expertise interest: </b><?=isset($member->field_area_of_expertise_interest['und'][0]['value'])?$member->field_area_of_expertise_interest['und'][0]['value']:'';?></div>
             <div class="description_member"><b>Description:</b> <?=isset($member->field_profile_description['und'][0]['value'])?$member->field_profile_description['und'][0]['value']:'';?></div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    
    
    
</div>
<script>
    jQuery(document).ready(function(){
         jQuery(".member_click").click(function(){
                                       var uid=jQuery(this).attr("mb_id");
                                       var nid=jQuery(this).attr("nid");
                                       var arr_mb_id=[];
                                       <?php for($k=0;$k<count($arr_mb_id);$k++):?>
                                               arr_mb_id.push(<?=$arr_mb_id[$k]?>);
                                        <?php endfor;?>
                                        jQuery.post("<?php print $base_url; ?>/icon_tool_click", {'nid':nid,'flag': "information_member","arr_mb_id":arr_mb_id,'uid':uid}, function(data) {
                                            jQuery(".content_team_member>a").hide();
                                            jQuery(".append_content_member").html(' ').append(data);//append_content_member
                                        });
                                       
                                   });
        
    });
</script>
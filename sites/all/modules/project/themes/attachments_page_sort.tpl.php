<?php
global $base_url,$user;

	$project_info = get_info_project($project->nid, 'members');
	$project = $project_info['project'];
	$member_client = $project_info['member_client'];
	$members_all = $project_info['members_all'];
        $resize_img=274;
            $parent=$folder_id; 
?>
<?php

$file_extends = array('doc', 'docx', 'pdf', 'zip', 'xls', 'xlsx', 'mp3', 'mp4', 'txt', 'tif', 'ppt', 'pptx', 'psd','jpg', 'gif', 'png', 'jpeg');
?>
<div id="attachments_page" class="panel sheet project active inactive" <?php if($project->sticky) print' data-behavior ="read_only" data-status="archived"'?>>
    <header> 
       <!-- <h1 class="title">
            <?php if(isset($_GET['uid'])) {
                $uid = $_GET['uid'];
                $me= get_name_member($uid);
                ?>
            <a class="link_page" href="<?= $base_url ?>/user/<?=$uid?>" data-uid="<?= $uid ?>"><?= $me['full_name'] ?></a>
            <?php } else {?>
            <a class="link_page" href="<?= $base_url ?>/node/<?=$project->nid?>" data-nid="<?= $project->nid ?>"><?= $project->title ?></a>    
            <?php }?>
        </h1>-->
    </header>
    <div class="panel sheet index attachments">
       
        <header class="file_all"> <span style="visibility: hidden">Files</span>
        <span class="position_reference" id="button_upload_files">
                        <button class="action_button" onclick="new_upload(this,<?= $project->nid ?>)">Add files</button>
                        <div class="blank_slate_arrow"></div>
                    </span>
            <span class="position_reference" id="button_create_floder">
                <input type="button" class="action_button show_form_folder" value="Create folder">
                        <div id="create_folder" style="display: none">
                            <div class="content_folder">
                               <input name="folder_name" placeholder="Enter folder's name" class="name_folder_file">
                               <div class="content_action_btn">
                                   <input type="button" name="carete_folder " class="create_folder action_button" id="btn_create_folder" value="Create">
                                   <input type="button" name="cancel_folder " class="cancel_folder action_button" id="btn_cancel_folder" value="Cancel">
                                   
                               </div>
                            </div>


            </div>
             </span>
            
            
            
            <?php
                                        $qr_folder_select=  db_query("SELECT * FROM tbl_project_attribute WHERE nid=$project->nid AND type =9 AND parent_id=$parent");
                                        $qr_folder_count=db_query("SELECT COUNT(aid) FROM tbl_project_attribute WHERE nid=$project->nid AND type =9 AND parent_id=$parent")->fetchField();
//                                        if($qr_folder_count==0){
//                                            $qr_folder=  db_query("SELECT * FROM tbl_project_attribute WHERE nid=$file->nid AND type =9 AND parent_id=$file->folder_id");
//                                        }
                                        
                                    ?>
                    
            <div class="tool_for_file" style="float: right">
                                        <div class="file_move_tool tool_file" ><a  class="move_tool_click action_button">Move</a>
                                            <div class="form_select_folder" style="display: none">
                                                <label style="float: left"> Select folder:</label> <select name="select_folder" class="select_folder">
                                                    <?php foreach ($qr_folder_select as $folder):?>
                                                            <option value="<?=$folder->aid?>"><?=$folder->subject?></option>
                                                    <?php endforeach;?>
                                                            <?php if($folder_id>0):?>
                                                        <?php 
                                                      
                                                            $parent_folder=-1;
                                                            
                                                            $parent_folder_qr=  db_query("SELECT parent_id,subject FROM tbl_project_attribute WHERE aid=$folder_id and type=9");
                                                                foreach ($parent_folder_qr as $pr_id){
                                                                    $parent_folder=$pr_id->parent_id;
                                                                }
                                                            ?>
                                                           
                                                            <?php if($parent_folder>-1):?>
                                                                <option value="<?=$parent_folder?>">Parent folder</option>
                                                            <?php endif;?>
                                                        <?php endif;?>
                                                </select>
                                                <div class="container_btn">
                                                    <input type="button" nid="<?=$project->nid?>"  name="move_file" value="Move" class="action_button  move_multifile">
                                                    <input type="button" name="cancel_move_file" value="Cancel" class="action_button cancel_move_file">
                                                   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="file_download_tool tool_file"><a nid="<?=$project->nid?>"  class="download_multi_tool_click action_button" >Download</a></div>
                                        <div class="file_delete_tool tool_file"><a nid="<?=$project->nid?>"  class="delete_multi_tool_click action_button" >Delete</a></div>
                                        <div class="file_share_tool tool_file" style="position: relative">
                                            <a nid="<?=$project->nid?>"  class="share_multi_tool_click action_button" >Share</a>
                                            <div class="form_share_mail" style="display: none">
                                                    <input type="radio" name="select_type_email" id="rd_select" value="select" checked="checked">Select from this site
                                                    <input type="radio" name="select_type_email" id="rd_enter" value="enter">Enter email
                                                    <?php 
                                                        $qr_email=  db_query("SELECT mail FROM users");
                                                        $mails=array();
                                                        foreach ($qr_email as $mail){
                                                            $mails[]=$mail->mail;
                                                        }
                                                    ?>
                                                    <div class="form_mail">
<!--                                                        <select name="email_from_site" class="select_email_from_site email_all">
                                                            <?php foreach($mails as $ml):?>
                                                                <?php if($ml!=''):?>
                                                                    <option value="<?=$ml?>"><?=$ml?></option>
                                                                <?php endif;?>
                                                            <?php endforeach;?>
                                                        </select>-->
                                                        
                                                        
                                                        <div class="content_checkbox_mail">
                                                            <?php $count_mail=0;?>
                                                            <?php foreach($mails as $ml):?>
                                                            <?php if($ml!=''):?>
                                                            <label for="chkb_mail_<?=$count_mail?>" ><input type="checkbox" name="chkb_mail_<?=$count_mail?>" value="<?=$ml?>"><?=$ml?></label>
                                                                <?php $count_mail++;?>
                                                            <?php endif;?>
                                                            <?php endforeach;?>
                                                        </div>
                                                        
                                                        
                                                        
                                                        
                                                        <input type="email" name="input_email" class="input_email email_all" style="display: none">
                                                        <div class="conteiner_btn_share">
                                                        <input nid="<?=$project->nid?>"  type="button" name="share_mail" value="share" class="action_button share_mail_btn">
                                                        <input type="button" name="cancel_mail" value="cancel" class="action_button cancel_mail_btn">
                                                        </div>
                                                    </div>
                                                  </div>
                                        </div>
                                    </div>
            
            
      </header>
       
            <header class="header_title_button has_buttons" style="display: none">
        <?php if($number_labels>0) {?>
                <div class="jump_to_tag has_balloon expanded">
				<span>
					File type: <select class="file_type_filter" name="file_type">
						<option value="default"> --none--</option>
						<?php foreach($file_extends as $type):?>
						<option value="<?=$type?>" <?php if($file_type==$type) print "selected=''"?>> <?=$type?></option>
						<?php endforeach;?>
					</select>
					User: <select class="file_user_filter name="file_user">
						<option value="default"> --none--</option>
						<?php foreach($members_all as $member):?>
						<?php 	$member_info= get_name_member($member->uid);
								$full_name = $member_info['full_name'];
						?>
						<option value="<?=$member->uid?>" <?php if($file_user==$member->uid) print "selected=''"?>> <?=$full_name?></option>
						<?php endforeach;?>
					</select>
					<input class="filter_file_button" type="button" value="Search">
				</span>
                </div>
                <?php } ?>
                <h1 class="active_title"> All files</h1>
                    
            </header>
        
        <div class="sheet_body">
            
         <div id="upload_files"></div>
         <?php 
                
                $parent=$folder_id;   
                $qr_folder= db_query("SELECT * FROM tbl_project_attribute WHERE type=9 AND nid=$project->nid AND parent_id=$parent AND deleted=0");
                $arr_list=array();
                foreach ($list_files as $file){
                   $key="type";
                    if($sort=="type"){
                          $key=$file->type;
                          $key=strtolower($key);
                    }
                    elseif($sort=="name"){
                        $key=isset($file->name)?$file->name:'0';
                        $key=strtolower($key);
                    }
                    else{
                        $key=$file->created;
                    }
                    $arr_list[$key][]=$file;
                }
         ?>
         <?php
            $dsc='';
            if( $sort_order=="asc"){
                 $dsc='dsc';
            }
            else{
                $dsc="asc";
            }
                   
            
         ?>
         <input type="hidden" class="folder_parent_id" name="id_folder_file" value="<?=$parent?>">
         <div class="header_file_upload">
              <div class="select_file_checkbox header_file"></div>
              <div class="file_name header_file"><a class="sort_by_name_file" data-href="<?=$base_url?>/icon_tool_click?flag=list_file&type=name&sort=<?=$dsc?>&nid=<?=$project->nid?>&id_folder=<?=$folder_id?>">Name</a></div>
             <div class="file_type header_file"><a class="sort_by_name_file" data-href="<?=$base_url?>/icon_tool_click?flag=list_file&type=type&sort=<?=$dsc?>&nid=<?=$project->nid?>&id_folder=<?=$folder_id?>">Type</a></div>
             <div class="file_modify header_file">
                    <a class="sort_by_name_file date_modified" data-href="<?=$base_url?>/icon_tool_click?flag=list_file&type=modified&sort=<?=$dsc?>&nid=<?=$project->nid?>&id_folder=<?=$folder_id?>">Modified</a>
                    
                    
             </div>
         </div>
         
       
                <?php foreach ($qr_folder as $fol):?>
                        <?php   
                             $key="type";
                             $fol->type="folder";
                            if($sort=="type"){
                                 $key=$fol->type;
                                 $key=strtolower($key);
                            }
                            elseif($sort=="name"){
                                 $key=!empty($fol->subject)?$fol->subject:'0';
                            }
                            else{
                                $key=$fol->modified;
                            }
                         
                            $arr_list[($key)][]=$fol;
                        ?>
             
                <?php endforeach;?>
             
    
         <?php 
            if($sort_order=="asc"){
                ksort($arr_list);
            }
            else{
                krsort($arr_list);
            }
         ?>

        
         <div class="list_folder">
                <?php foreach ($arr_list as $items):?>
                    <?php $sub_item=array();   ?>
                <?php for($j=0;$j<count($items);$j++):?>
                        <?php
                            if($items[$j]->type=="folder"){
                                $sub_item[$items[$j]->modified][]=$items[$j];
                            }
                            else{
                                $sub_item[$items[$j]->created][]=$items[$j];
                            }
                        ?>
                    
                    <?php endfor;?>
                    <?php   krsort($sub_item); ?>
                    <?php foreach($sub_item as $item):?>    
                        <?php for($i=0;$i<count($item);$i++):?>
                       <?php if($item[$i]->type=="folder"):?>
                        <div class="content_file_all">
                           <div class="select_file_checkbox header_file"><input type="checkbox" name="folder_<?= $fol->aid ?>" value="0" folder_aid="<?= $fol->aid ?>"></div>
                           <div class="file_name">
                               <a class="folder_click" id_project="<?=$project->nid ?>" id_folder="<?=$item[$i]->aid?>">
                                   <div class="content_folder_sub">
                                      <div class="icon_folder"></div>
                                      <div class="name_folder"><?=$item[$i]->subject?></div>
                                   </div>

                               </a>
                            </div>
                        <div class="file_type">Folder</div>
                        <div class="file_modify"><?=date("m/d/Y H:m:s a",$item[$i]->modified)?></div>
                        </div>
                        <?php else:?>
                        <?php
                            $images_extend = array('jpg', 'gif', 'png', 'jpeg');
                            $file_extends = array('doc', 'docx', 'pdf', 'zip', 'xls', 'xlsx', 'mp3', 'mp4', 'txt', 'tif', 'ppt', 'pptx', 'tif','psd');
                            $extends = array();
                            $link_extend = "";
                            $nid = $item[$i]->nid;
                            $extend = strtolower(substr($item[$i]->name, strrpos($item[$i]->name, '.') + 1));
                           
                             
                        ?>
                 <div class="list_files" >
                        <div class="content_file_all">
                             <div class="select_file_checkbox header_file"><input type="checkbox" name="file_<?= $item[$i]->fid ?>" value="<?= $item[$i]->fid ?>"></div>
                             <div class="file_name">
                                <a class="fancybox fancybox.ajax" href="<?= $base_url ?>/attachments-detail?aid=<?= $item[$i]->aid ?>&fid=<?= $item[$i]->fid ?>&cid=<?= $item[$i]->cid ?><?= ($item[$i]->tid > 0) ? '&tid=' . $item[$i]->tid : '' ?><?= $link_extend ?>">
                                    <?php if (in_array($extend, $images_extend)) {
                                           if(isset($resize_img)){
                                            
                                            $path_file =str_replace(' ','%20',($base_url . '/sites/default/files/projects/' . $item[$i]->nid . '/' . $item[$i]->path));
                                            $imagesize = getimagesize($path_file);                                            
                                            $img = new stdClass();
                                            $img->width = $imagesize[0];
                                            $img->height = $imagesize[1];
                                          $info = best_fit($resize_img,$resize_img,$img);
                                          $width_resize ="width:".round($info['width']).'px';
                                          $height_resize = "max-height:".round($info['height']).'px';
                                           }
                                        ?>
                                    <div class="content_folder_sub">
                                        <div class="image_file"><img class="thumbnail " src="<?= $base_url ?>/sites/default/files/projects/<?= $item[$i]->nid . '/' . $item[$i]->path ?>" style="<?= isset($width_resize)?$width_resize:'' ?>;<?=isset($height_resize)?$height_resize:'' ?>"></div>
                                        <div class="name_folder"><?= $item[$i]->name ?></div>
                                     </div>
                                        
                                        <?php
                                    } else {
                                        if (in_array($extend, $file_extends)) {
                                            ?>
                                             <div class="image_file">  <img class="thumbnail file_icon" src="<?= $base_url ?>/sites/all/themes/adaptivetheme/basecamp/images/icons/<?= $extend ?>_86x100.png">   </div>
                                              <div class="name_folder"><?= $item[$i]->name ?></div>
                                        <?php } else { ?>
                                              <div class="image_file"><img class="thumbnail file_icon" src="<?= $base_url ?>/sites/all/themes/adaptivetheme/basecamp/images/icons/file_86x100.png">   </div>
                                               <div class="name_folder"><?= $item[$i]->name ?></div>
                                            <?php
                                        }
                                    }
                                    ?>
                               
                                </a>
                             </div>
                             <div class="file_type"><?=$item[$i]->type?></div>
                             <div class="file_modify">
                                 <div class="date_modified"><?=date("m/d/Y H:m:s a",$item[$i]->created)?></div>
                                  <?php
                                        $nid_file=$item[$i]->nid;
                                        $folder_id=$item[$i]->folder_id;
                                        $qr_folder=  db_query("SELECT * FROM tbl_project_attribute WHERE nid=$nid_file AND type =9 AND parent_id=$folder_id");
                                        $qr_folder_count=db_query("SELECT COUNT(aid) FROM tbl_project_attribute WHERE nid=$nid_file AND type =9 AND parent_id=$folder_id")->fetchField();
//                                        if($qr_folder_count==0){
//                                            $qr_folder=  db_query("SELECT * FROM tbl_project_attribute WHERE nid=$file->nid AND type =9 AND parent_id=$file->folder_id");
//                                        }
                                        
                                    ?>
                                    <div class="tool_for_file">
                                        <div class="file_move_tool tool_file" ><a file_id="<?=$item[$i]->fid?>" class="move_tool_click">Move</a>
                                            <div class="form_select_folder" style="display: none">
                                                <label style="float: left"> Select folder:</label> <select name="select_folder" class="select_folder">
                                                    <?php foreach ($qr_folder as $folder):?>
                                                            <option value="<?=$folder->aid?>"><?=$folder->subject?></option>
                                                    <?php endforeach;?>
                                                </select>
                                                <div class="container_btn">
                                                    <input type="button" nid="<?=$item[$i]->nid?>" file_id="<?=$item[$i]->fid?>" name="move_file" value="Move" class="action_button move_file">
                                                    <input type="button" name="cancel_move_file" value="Cancel" class="action_button cancel_move_file">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="file_download_tool tool_file"><a nid="<?=$item[$i]->nid?>" file_id="<?=$item[$i]->fid?>" href="<?= $base_url ?>/sites/default/files/projects/<?= $item[$i]->nid . '/' . $item[$i]->path ?>" class="download_tool_click download_<?=$item[$i]->fid?>"  download="<?=$item[$i]->path?>">Download</a></div>
                                        <div class="file_delete_tool tool_file"><a nid="<?=$item[$i]->nid?>" file_id="<?=$item[$i]->fid?>" class="delete_tool_click" >Delete</a></div>
                                        
                                    </div>
                                 
                             </div>
                            </div>
                      <?php endif;?>
                     <?php endfor;?>
                 <?php endforeach;?>
                <?php endforeach;?>
             
         </div>
         </div>
    
       </div>
        <div class="content_right_file" style="display:none">
			<?php if(isset($date_filter)&&$date_filter==1):?>
			<div class="file_back" style="text-align:right"><a class="filter_back_file">Back</a></div>
		<?php endif;?>
			 <div id="datepicker_file"></div>
	   </div>
    </div>
</div>
<script>
    jQuery("document").ready(function(){
                 function downloadURI(uri, name) {
                    var link = document.createElement("a");
                    link.download = name;
                    link.href = uri;
                    link.click();
                  }
                  
                   //download multil file
                jQuery(".download_multi_tool_click").click(function(){
                    var arr_fid=[];
                     var nid=jQuery(this).attr("nid");
                     jQuery(".select_file_checkbox input[type='checkbox']").each(function(){
                            if(jQuery(this).is(":checked")){
                                 var fid=jQuery(this).attr("value");
                                var file=jQuery(".download_"+fid).attr("download");
                                 arr_fid.push(file);
                                // console.log(file);
                            }
                     });
                     
                     if(arr_fid.length>0){
                      
                        jQuery.post("<?= $base_url ?>/zipfiles_download",{"nid":nid,"arr_fid":arr_fid,"flag":"download_multi_file"},function(data){
                             
                               downloadURI(data["link_file"], data["filename"]);
                      
                            },'json');
                       
                    }
                });
                //delete multi file
                jQuery(".delete_multi_tool_click").click(function(){
                     var arr_fid=[];
                     var arr_fol_aid=[];
                     var nid=jQuery(this).attr("nid");
                     
                     jQuery(".select_file_checkbox input[type='checkbox']").each(function(){
                            if(jQuery(this).is(":checked")){
                                if(jQuery(this).attr("value")>0){
                                    arr_fid.push(jQuery(this).attr("value"));
                                }
                                else{
                                    arr_fol_aid.push(jQuery(this).attr("folder_aid"));
                                }
                                
                            }
                     });
                     
                     if(arr_fid.length>0 || arr_fol_aid.length>0){
                       if(confirm("Do you want delete selected files?"))  {
                        jQuery.post("<?= $base_url ?>/icon_tool_click",{"nid":nid,"arr_fid":arr_fid,"flag":"delete_multi_file","arr_fol_aid":arr_fol_aid},function(data){
                              if(data>0)
                                  for(var i=0;i<arr_fid.length;i++)
                                    jQuery("#file-attachment-"+arr_fid[i]).remove();
                                   for(var j =0;j<arr_fol_aid.length;j++){
                                       
                                        jQuery(".folder_att_"+arr_fol_aid[j]).remove();
                                   }

                            });
                        }
                    }
                    
                });
                 //share file
                //share_multi_tool_click
                jQuery("#rd_select").click(function(){
                    jQuery(".content_checkbox_mail").show();
                    jQuery(".input_email").hide();
                
                });
                jQuery("#rd_enter").click(function(){
                    jQuery(".content_checkbox_mail").hide();
                    jQuery(".input_email").show();
                
                });
                jQuery(".cancel_mail_btn").click(function(){
                     jQuery(".form_share_mail").hide();
                });
                jQuery(".share_multi_tool_click").click(function(){
                    jQuery(".form_share_mail").show();
                });
                jQuery(".action_button.share_mail_btn").click(function(){
                    var email=[];
                    //rd_select,rd_enter
                    if(jQuery("#rd_select").is(":checked")){
                      
                         jQuery(".content_checkbox_mail input[type='checkbox']").each(function(){
                           if(jQuery(this).is(":checked")){
                                var value=jQuery(this).attr("value");
                                    email.push(value);
                               
                                
                            }
                         });
                        
                          
                    }
                    else{
                        var value_mail=jQuery(".input_email").val();
                        email.push(value_mail);
                      
                    }
                      
                    
                     var arr_fid=[];
                     var folder=0;
                     var nid=jQuery(this).attr("nid");
                     jQuery(".select_file_checkbox input[type='checkbox']").each(function(){
                            if(jQuery(this).is(":checked")){
                                 if(jQuery(this).attr("value")>0){
                                    var fid=jQuery(this).attr("value");
                                   var file=jQuery(".download_"+fid).attr("download");
                                    arr_fid.push(file);
                             }
                             else{
                                folder=1; 
                             }
                                // console.log(file);
                            }
                        });
                        if(folder==1){
                            alert("can not share folder!");
                        }
                       else{
                            if(arr_fid.length>0 && email.length>0){
                               jQuery.post("<?= $base_url ?>/icon_tool_click",{"nid":nid,"arr_fid":arr_fid,"flag":"share_file","email":email},function(data){
                                    console.log(data);
                                    alert(data);
                                   });
                           }
                        }
                     jQuery(".form_share_mail").hide();
                });
                
                //delete one file
                
                jQuery(".delete_tool_click").click(function(){
                    var fid= jQuery(this).attr("file_id");
                    var nid=jQuery(this).attr("nid");
                     if(confirm("Do you want delete selected file?"))  {
                        jQuery.post("<?=$base_url?>/icon_tool_click",{"nid":nid,"flag":"delete_file","fid":fid},function(data){
                         if(data>0){
                                jQuery("#file-attachment-"+fid).remove();
                            }
                        
                        });
                    }
                });
        
        
                jQuery(".action_button.move_multifile").click(function(){
                     var folder_id=jQuery(this).parent().parent().find(".select_folder").val();
                     var arr_fid=[];
                     var arr_fol_aid=[];
                     var nid=jQuery(this).attr("nid");
                     jQuery(".select_file_checkbox input[type='checkbox']").each(function(){
                           if(jQuery(this).is(":checked")){
                                if(jQuery(this).attr("value")>0){
                                    arr_fid.push(jQuery(this).attr("value"));
                                }
                                else{
                                    arr_fol_aid.push(jQuery(this).attr("folder_aid"));
                                }
                                
                            }
                     });
                     if(folder_id >=0 && (arr_fid.length>0 ||arr_fol_aid.length)){
                        jQuery.post("<?= $base_url ?>/icon_tool_click",{"nid":nid,"arr_fid":arr_fid,"flag":"move_multi_file","folder_id":folder_id,"arr_fol_aid":arr_fol_aid},function(data){
                              if(data>0){
                                  for(var i=0;i<arr_fid.length;i++)
                                    jQuery("#file-attachment-"+arr_fid[i]).remove();
                                   
                                   for(var j =0;j<arr_fol_aid.length;j++){
                                       
                                        jQuery(".folder_att_"+arr_fol_aid[j]).remove();
                                   }
                                jQuery(".folder_att_"+folder_id).addClass("background_after_move");
                                   
                                    setTimeout(function(){
                                        jQuery(".folder_att_"+folder_id).removeClass("background_after_move");       
                                    }, 5000);   
                                }
                            });
                    }
                    jQuery(".form_select_folder").hide();
                });
                jQuery(".move_tool_click").click(function(){
                        var fid=jQuery(this).attr("file_id");
                        jQuery(".form_select_folder").hide();
                        jQuery(this).parent().find(".form_select_folder").show();
                        
                    
                });
                jQuery(".action_button.move_file").click(function(){
                    var fid=jQuery(this).attr("file_id");
                    var folder_id=jQuery(this).parent().parent().find(".select_folder").val();
//                    jQuery("#file-attachment-"+fid).remove();
//                    console.log("#file-attachment-"+fid);
                    var nid=jQuery(this).attr("nid");
                    if(folder_id>=0){
                    jQuery.post("<?= $base_url ?>/icon_tool_click",{"nid":nid,"fid":fid,"flag":"move_file","folder_id":folder_id},function(data){
                          if(data>0)
                            jQuery("#file-attachment-"+fid).remove();
                            
                        });
                    }
                    jQuery(".form_select_folder").hide();
                });
                
                
                
                
                 //move to parent folder
                 jQuery(".move_to_parent_folder").click(function(){
                     var folder_id=jQuery(this).attr("current_folder");
                     var arr_fid=[];
                     var arr_fol_aid=[];
                     var nid=jQuery(this).attr("nid");
                     jQuery(".select_file_checkbox input[type='checkbox']").each(function(){
                           if(jQuery(this).is(":checked")){
                                if(jQuery(this).attr("value")>0){
                                    arr_fid.push(jQuery(this).attr("value"));
                                }
                                else{
                                    arr_fol_aid.push(jQuery(this).attr("folder_aid"));
                                }
                                
                            }
                     });
                     if(folder_id >0 && (arr_fid.length>0 ||arr_fol_aid.length)){
                        jQuery.post("<?= $base_url ?>/icon_tool_click",{"nid":nid,"arr_fid":arr_fid,"flag":"move_to_parent","folder_id":folder_id,"arr_fol_aid":arr_fol_aid},function(data){
                              if(data>0)
                                  for(var i=0;i<arr_fid.length;i++)
                                    jQuery("#file-attachment-"+arr_fid[i]).remove();
                                   
                                   for(var j =0;j<arr_fol_aid.length;j++){
                                       
                                        jQuery(".folder_att_"+arr_fol_aid[j]).remove();
                                   }
                            });
                    }
                     jQuery(".form_select_folder").hide();
                });
                
                
                
                 jQuery(".action_button.cancel_move_file").click(function(){
                    jQuery(this).parent().parent().hide();
                 
                 });
        
        
        
                jQuery(".show_form_folder").click(function(){
                    
                    jQuery("#create_folder").show();
                });
                jQuery("#btn_cancel_folder").click(function(){
                    jQuery("#create_folder").hide();
                    
                });
                jQuery(".folder_click").click(function(){
                    var nid=jQuery(this).attr("id_project");
                    var folder_id=jQuery(this).attr("id_folder");
                        jQuery.post("<?= $base_url ?>/icon_tool_click",{"nid":nid,"flag":"list_file","id_folder":folder_id},function(data){
                           jQuery(".content_file").html(' ').append(data);
                            
                        });
                    
                });
                
                jQuery(".sort_by_name_file").click(function(){
                    var nid=jQuery(this).attr("nid");
                    var href=jQuery(this).attr("data-href");
                        jQuery.post(href,function(data){
                           jQuery(".content_file").html(' ').append(data);
                            
                        });
                    
                });
                
                 jQuery("#btn_create_folder").click(function(){
                    var nid=<?= $project->nid ?>;
                    var name_folder=jQuery(".name_folder_file").val();
                    var parent=jQuery(".folder_parent_id").attr("value");
                   
                        
                     
			jQuery.post("<?= $base_url ?>/icon_tool_click",{"nid":nid,"flag":"create_folder",'name_folder':name_folder,'parent':parent},function(data){
//                           jQuery(".list_folder").append(data_folder);
//                            console.log(data);
//                            console.log(data['aid']);
//                            console.log(data.aid);
                            var new_floder= '<div class="content_file_all">';
                                new_floder+= '<div class="select_file_checkbox header_file" ><input type="checkbox" name="folder_'+data['aid']+'"  value="0" folder_aid="'+data['aid']+'"></div>';
                                new_floder+= '    <div class="file_name">';
                                
                                new_floder+='                  <a class="folder_click" id_project="<?=$project->nid ?>" id_folder="'+data['aid']+'">';
                                new_floder+='                        <div class="content_folder_sub">';
                                new_floder+='                           <div class="icon_folder"></div>';
                                new_floder+='                           <div class="name_folder">'+name_folder+'</div>';
                                new_floder+='                        </div>';

                                new_floder+='                    </a>';
                                new_floder+='                 </div>';
                                new_floder+='             <div class="file_type">Folder</div>';
                                new_floder+='             <div class="file_modify">'+data['created']+'</div>';
                                new_floder+='             </div>';
                                  jQuery(".list_folder").append(new_floder);
                        },'json');
                     jQuery("#create_folder").hide();
                });
        
        
		jQuery(".filter_back_file").click(function(){
			var nid=<?= $project->nid ?>;
			jQuery.post("<?= $base_url ?>/icon_tool_click",{"nid":nid,"flag":"list_file"},function(data){
                           jQuery(".content_file").html(' ').append(data);
                            
                        });
		});
		jQuery("#datepicker_file").datepicker({
		 inline: true,
		onSelect: function(dateText) {
		 var nid=<?= $project->nid ?>;
                        jQuery.post("<?= $base_url ?>/icon_tool_click",{"nid":nid,"flag":"list_file","date_file":this.value},function(data){
                           jQuery(".content_file").html(' ').append(data);
                            
                        });
		}
							
	});
		
		jQuery(".filter_file_button").click(function(){
			var file_type=jQuery(".file_type_filter").val();
			var file_user=jQuery(".file_user_filter").val();
			var nid=<?=$project->nid?>;
			if(file_type=="default" && file_user=="default"){
				  
					jQuery.post("<?= $base_url ?>/icon_tool_click",{"nid":nid,"flag":"list_file"},function(data){
					   jQuery(".content_file").html(' ').append(data);
						
					});
			}else{
					jQuery.post("<?= $base_url ?>/icon_tool_click",{"nid":nid,"flag":"list_file","file_type":file_type,"file_user":file_user},function(data){
					   jQuery(".content_file").html(' ').append(data);
						
					});
			}
		});
	
        resize_height_img(); 
        <?php if(isset($_GET['nid'])) {?>
            var array_extend = new Array("nid=<?=$_GET['nid']?>");
        <?php } else if(isset($_GET['uid'])) {?>
            var array_extend = new Array("uid=<?=$_GET['uid']?>");
        <?php }else {?>
                var array_extend="-1";
            <?php } ?>
         load_page();
         if(array_extend!="-1")
            paging_page("attachments",array_extend);
    });
    function process_tag(me,tag_id,expanded_id,tag_value_html,edit_tags,action){
        if(action=='expand'){
            jQuery("#"+tag_id).hide();
            jQuery("#"+expanded_id).show();
        }else if(action=='commit'){
            var object_tag_value_old = jQuery("#"+expanded_id).children('.tag_value_input_old');
            var tag_value = jQuery("#"+expanded_id).children('.tag_value_input').val();
            var tag_value_old = object_tag_value_old.val();
            if(tag_value.length>0){
                jQuery.post("<?= $base_url ?>/edit-tag", {'tag_value':tag_value,'tag_value_old':tag_value_old}, function(data){
                    if(data>0){
                        jQuery("#"+tag_value_html).html(tag_value);
                        jQuery(object_tag_value_old).attr("value", tag_value);
                        jQuery("#"+expanded_id).hide();
                        jQuery("#"+tag_id).show();
                        jQuery("#"+edit_tags).hide();
                        jQuery(".taggings ul.labels li a.linktag").each(function(){
                            if(jQuery(this).html()==tag_value_old)
                                jQuery(this).html(tag_value);
                        });
                         jQuery(".top_left_side.two_cols").hide();
                    }
                });
            }
        } else if(action=='deleted'){
            var tag_value = jQuery("#"+expanded_id).children('.tag_value_input').val();
            if(tag_value.length>0){
                jQuery.post("<?= $base_url ?>/action", {'tag_value':tag_value,'flag':'tag'}, function(data){
                    if(data>0){
                        window.location.href="<?= $base_url ?>/attachments";
                    }
                });
            }
        }
        else if(action=='deleted-label'){
            var tag_value = jQuery("#"+expanded_id).children('.tag_value_input').val();        
            if(tag_value.length>0){
                jQuery.post("<?= $base_url ?>/action", {'tag_value':tag_value,'flag':'tag'}, function(data){
                    if(data>0){
                        jQuery("#"+expanded_id).parent().parent().remove();
                        jQuery(".taggings ul.labels li a.linktag").each(function(){
                            if(jQuery(this).html()==tag_value)
                                jQuery(this).remove();
                        });
                        jQuery(".two_cols.tagged.taggings").hide();
                    }
                });
            }
        }
    }
    function expand_edit_tag(flag,edit_tags,edit_delete_tag,expanded_id){
        if(flag=='show'){
            jQuery(".edit_tag.expanded_content").hide();
            jQuery(".expanded_layout").hide();
            jQuery("#"+edit_tags+" .actions").show();
            jQuery("#"+edit_tags).show();
        }
        else if(flag=='hide') 
            jQuery("#"+edit_tags).hide();
        else if(flag=='show_actions'){
            jQuery("#"+edit_delete_tag).show();
            jQuery("#"+expanded_id).hide();
        }else if(flag=='show_box'){
            jQuery(".expanded_content.two_cols").toggle();
        }
    }
    function confirm_delete(me){
        jQuery(me).parent().hide();
        jQuery(me).parent().parent().children(".confirm_delete").slideDown('slow');
    }
    
</script>


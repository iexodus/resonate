

<?php 
              
                function trim_text($input, $length, $ellipses = true, $strip_html = true) {
                    //strip tags, if desired
                    if ($strip_html) {
                        $input = strip_tags($input);
                    }

                    //no need to trim, already shorter than trim length
                    if (strlen($input) <= $length) {
                        return $input;
                    }

                    //find last space within length
                    $last_space = strrpos(substr($input, 0, $length), ' ');
                    $trimmed_text = substr($input, 0, $last_space);

                    //add ellipses (...)
                    if ($ellipses) {
                        $trimmed_text .= '...';
                    }

                    return $trimmed_text;
                }
              ?>
<?php global $base_url, $user; ?>
<script type="text/javascript" src="<?=$base_url?>/sites/all/themes/adaptivetheme/basecamp/scripts/storyjs-embed.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/sites/all/modules/project/js/jcarousellite.js"></script>
<!--<link href="<?=$base_url?>/sites/all/modules/project/css/custom_css.css" rel="stylesheet">-->
<?php $nproject = 0;?>
<?php $result = db_query("SELECT COUNT(nid) as total FROM node WHERE type = 'project'");?>
<?php foreach($result as $object) $nproject = $object->total;?>
<?php $author = user_load($user->uid);?>
<?php $create_projects = can_create_projects($author);?>
<div data-body-class="home flat_background has_user_menu topnav_root" class="panel home_tab project_index<?php if(empty($cards) && empty($list) && empty($updates)) echo ' blank_slate';?><?php if(!$create_projects && empty($cards) || empty($list) || empty($updates)) print ' no_nav';?>">
    <h3 class="title">Projects <a href="#" data-behavior="new_project bounce" style="float: right"><span>Create Project</span></a>  </h3>
    <?php if($create_projects){?>
    <nav class="projects_nav " style="display: none">
        <a href="#" data-behavior="new_project bounce"><span>Create Project</span></a>      
    </nav>
    <?php } ?>
    <?php if(!empty($cards) || !empty($list) || !empty($updates)){?>
    <div style="display: none" class="project_view<?php if(!$create_projects) print ' top';?>">
        <ul>
            <li><a href="#" data-behavior="change_project_view" class="cards<?php if($project_view == 'cards') print ' on';?>"></a></li>
            <li><a href="#" data-behavior="change_project_view" class="starred<?php if($project_view == 'starred') print ' on';?>"></a></li>
            <li><a href="#" data-behavior="change_project_view" class="list<?php if($project_view == 'list') print ' on';?>"></a></li>
            <li><a href="#" data-behavior="change_project_view" class="update<?php if($project_view == 'update') print ' on';?>"></a></li>
        </ul>
    </div>
    <?php } ?>
    <?php if($create_projects){?>
    <div style="display: none" class="project_templates<?php if(empty($cards) && empty($list) && empty($updates)) echo ' no_active_projects';?>">
        <hr>
        <a href="<?php print $base_url;?>/templates" data-behavior="load_templates">Templates</a>
    </div>
   
      <div style="display: none" class="blank_slate_arrow_notice">
           <div class="blank_slate_arrow_project"></div>
          Click on Project to See Details</div>
    <?php } ?>
    <?php if($create_projects || !empty($cards) || !empty($list) || !empty($updates) || !empty($archived)){?>
    <div id="all_projects" style="display:block;">
    <?php } ?>
<?php if(!empty($cards) ){?>
		<section class="projects cards ">
                    <div class="item_project_block">
                        <?php 
                            $arr_ul=array();
                            $index=$i =0;
                            foreach($cards as $info){
                                if($index%8==0&&$index!=0){
                                    $i++;
                                }
                                $arr_ul[$i][] = $info;
                                $index++;
                            }
                        ?>
                     
  

                       <?php for($i=0;$i< count($arr_ul);$i++):?>
                            <?php
                                $style_ul='';
                                $curent=$i;
                                if($curent!=0){
                                     $style_ul=' style="display:none;" ';
                                }
                                $next=$i+1;
                                $pre=$i-1;
                                if($curent==(count($arr_ul)-1)){
                                    $next=0;
                                }
                                if($curent==0){
                                   $pre=count($arr_ul)-1;
                                }
                            ?>
                            <ul class="slide_project_click slide_<?=$curent?>" curent="<?=$curent?>" next="<?=$next?>"  pre="<?=$pre?>" <?=$style_ul?>>
                                <?php for($j=0;$j<count($arr_ul[$i]);$j++):?>
                                             <li>
				  <article class="card<?php if($arr_ul[$i][$j]->status == 0) print ' draft';?>">
				    

                                       <a title="<?php print $arr_ul[$i][$j]->title;?>" href="<?php print $base_url.'/'.$arr_ul[$i][$j]->alias;?>" class="project_card link_page" data-nid="<?=$arr_ul[$i][$j]->nid?>" type_node="prj">
                                           <h5><?php print $arr_ul[$i][$j]->title;?></h5>
                                           <div class="image_project">
                                      <?php if(isset($arr_ul[$i][$j]->	field_project_image_text["und"][0]["value"])):?>
                                            <?php
                                                $src=$base_url.'/sites/default/files/img_project/'.$arr_ul[$i][$j]->field_project_image_text["und"][0]["value"];
                                            ?>
                                         
                                          <div class="container_img_prj"><div class="img_prj" style="background: url('<?=$src?>');background-size: cover;background-position: center center;"></div></div>
                                      <?php endif;?>
                                      </div>
                                     
				    
                                      
                                      
                                       <div class="description_prj"><?= isset($arr_ul[$i][$j]->body["und"][0]["value"])?trim_text($arr_ul[$i][$j]->body["und"][0]["value"],70):''?></div>
<!--                                       <div class="line"></div>-->
                                       
                                       <div class="milestone" style="display:none;">
                                           <div class="title title_milestone">Milestone:</div>
                                           <div class="content-milestone">
                                                <!--<iframe src="<?=$base_url?>/iframe_mile?nid=<?=$arr_ul[$i][$j]->nid?>" width="465" height="270">   </iframe>-->
                                           </div>
                                           <div class="clear"></div>
                                       </div>
                                       </a>	
                                       <div class="line"></div>
				      <div class="people">
                                          
                                            <?php 
                                                    $arr_ul_people=array();
                                                    $index1=$i1 =0;
                                                    foreach($arr_ul[$i][$j]->assigned_user as $people){
                                                        if($index1%2==0&&$index1!=0){
                                                            $i1++;
                                                        }
                                                        $arr_ul_people[$i1][] = $people;
                                                        $index1++;
                                                    }
                                                ?>

                                       
                                               
                                              
                                           <?php for($h=0;$h< count($arr_ul_people);$h++):?>
                                                <?php
                                                    $style_ul_people='';
                                                    $curent_people=$h;
                                                    if($curent_people!=0){
                                                         $style_ul_people=' style="display:none;" ';
                                                    }
                                                    $next_people=$h+1;
                                                    $pre_people=$h-1;
                                                    if($curent_people==(count($arr_ul_people)-1)){
                                                        $next_people=0;
                                                    }
                                                    if($curent_people==0){
                                                       $pre_people=count($arr_ul_people)-1;
                                                    }
                                                ?>
                                          
                                            <div class="li_member li_member_<?=$curent_people?>" curent="<?=$curent_people?>" next="<?=$next_people?>"  pre="<?=$pre_people?>" <?=$style_ul_people?> >
                                               
                                                <?php $count_pp=0;?>
				      		<?php foreach($arr_ul_people[$h] as $member){?>
                                              
				      		<?php 	if(isset($member->field_profile_picture['und'][0]['value'])){?>
				      		<?php 		$title = '';?>
				      		<?php 		if(isset($member->field_profile_fname['und'][0]['value'])) $title .= $member->field_profile_fname['und'][0]['value'].' ';?>
				      		<?php 		if(isset($member->field_profile_lname['und'][0]['value'])) $title .= $member->field_profile_lname['und'][0]['value'];?>
				      		<?php 		$title = trim($title);?>
				      		<?php 		if(empty($title)) $title = $member->mail;?>
                                                <?php 
                                                        $name=$member->name;
                                                        if(isset($member->field_profile_fname['und'][0]['value'])&&isset($member->field_profile_lname['und'][0]['value']))
                                                        {
                                                            
                                                            $name=$member->field_profile_fname['und'][0]['value']." ".$member->field_profile_lname['und'][0]['value'];
                                                        }
                                                  ?>
				      		<div class="content_member"> 
                                                    <img alt="<?=$name?>" width="40" height="40" title="<?php print $title;?>" src="<?php print $base_url.'/sites/default/files/profile/'.$member->field_profile_picture['und'][0]['value'];?>" class="avatar">
                                                    <div class="name_member" style="">
                                                    
                                                     <?=$name ?>
                                                     
                                                 </div>
                                                 <div class="clear"></div>
                                                </div>
                                         
				      		<?php 	$count_pp++;} ?>
                                              
                                         
                                       
                                                <?php } ?>
                                                <?php if($arr_ul[$i][$j]->assigned_user>2):?>
                                                <div class="next_member"></div>
                                                <?php endif;?>
                                            </div>
                                           <?php endfor;?>
				      </div>
                                      <div class="see_more_people"><a class="link_page" href="<?=$base_url?>/node/<?=$arr_ul[$i][$j]->nid?>" data-nid="<?=$arr_ul[$i][$j]->nid?>" type_node="prj">See more...</a></div>      
                                       
				</article>
                                     
                                </li>
                                
                                
                                        
                                <?php endfor;?>
                                <div class="next_buton_click next "></div>
                            </ul>
                       <?php endfor;?>
                            
                            
                            
                            
                            
                            
		<?php $count = 0;?>
                                <ul>
		<?php foreach($cards as $project){?>

                               
                                
                              
                                
                                    
                                
                                
                <?php }?>
                        </ul>
                        </div>
<!--                   <div id="next_slide" class="next" style="display: none"></div>
                    <div  id="pre_slide" style="display: none" class="prev"></div>-->
			</div>
   
            
		</section>
<?php }?>
<?php if(empty($cards) && empty($list) && empty($updates) && empty($archived)){?>
		<section class="projects cards">
		<?php if($create_projects){?>
			<article class="blank_slate ">
				<div class="blank_slate_arrow"></div>
				<div class="blank_slate_body">
				  <h1>Get started by adding your first project!</h1>
				  <p>Freebasecamp helps you manage projects and collaborate with your team and your clients like never before. Create your first project to get started.</p>
				</div>
			</article>
		<?php } else { ?>
			<article class="blank_slate no_projects">
				<div class="blank_slate_body">
				  <h1>There aren't any projects yet.</h1>
				  <p>The owner of your Freebasecamp account hasn't created any projects.<br>Hold tight! In the meantime, watch Maru:</p>
				  <iframe width="420" height="315" frameborder="0" allowfullscreen="" src="http://www.youtube.com/embed/TbiedguhyvM?showinfo=0"></iframe>
				</div>
			</article>
		<?php } ?>
		</section>
<?php } ?>
<?php if(!empty($starred) && $project_view == 'starred'){?>
		<section class="projects cards stars_only">
			<div class="row">
		<?php $count = 0;?>
		<?php foreach($starred as $project){?>
				  <article class="card<?php if($project->status == 0) print ' draft';?>">
				    <span class="draft">unpublished</span>
				    <span title="Starring a project will highlight it on this page" data-project-id="<?php print $project->nid;?>" data-behavior="project_star" class="star<?php if($project->star) print ' on';?>"></span>
				    <a title="<?php print $project->title;?>" href="<?php print $base_url.'/'.$project->alias;?>" class="project_card link_page" data-nid="<?=$project->nid?>">
				      <h5><?php print $project->title;?></h5>
				      <p style="margin-bottom: 5px;"></p>
				      <p style="font-size: 12px;">
				        Last updated <time datetime="<?php print date('Y-m-d H:i:s',$project->changed);?>" data-time-ago="<?php print time_ago($project->changed);?>"><?php print time_ago($project->changed);?></time>
				      </p>
				      <div class="people">
				      		<?php foreach($project->assigned_user as $member){?>
				      		<?php 	if(isset($member->field_profile_picture['und'][0]['value'])){?>
				      		<?php 		$title = '';?>
				      		<?php 		if(isset($member->field_profile_fname['und'][0]['value'])) $title .= $member->field_profile_fname['und'][0]['value'].' ';?>
				      		<?php 		if(isset($member->field_profile_lname['und'][0]['value'])) $title .= $member->field_profile_lname['und'][0]['value'];?>
				      		<?php 		$title = trim($title);?>
				      		<?php 		if(empty($title)) $title = $member->mail;?>
				      		 <img width="40" height="40" title="<?php print $title;?>" src="<?php print $base_url.'/sites/default/files/profile/'.$member->field_profile_picture['und'][0]['value'];?>" class="avatar">
				      		<?php 	} ?>
				      		<?php } ?>
				      </div>
					</a>          
				</article>
		<?php 	if($count%3 == 2){?>
			</div>
			<div class="row">
		<?php 	} ?>
		<?php 	$count++;?>
		<?php } ?>
		<?php $length = count($starred);?>
		<?php if($length == 1){?>
				<article class="card blank stars" data-behavior="bounce_nav">&nbsp;</article>
				<article class="card blank stars" data-behavior="bounce_nav">&nbsp;</article>
		<?php }else if($length == 2){?>
				<article class="card blank stars" data-behavior="bounce_nav">&nbsp;</article>
		<?php } ?>
			</div>
		</section>
		<?php } else if(!empty($list) && $project_view == 'starred'){?>
		<section class="projects cards stars_only">
			<div class="stars_blank_slate">
		  		<div class="blank_slate_arrow"></div>
		  		<div class="blank_slate_body">
					<h1>Star your favorite projects!</h1>
		   	 		<p>Click the star next to any project's name, and that project will show up here. (It will stay in the A-Z list below, too.)</p>
		  		</div>
		  		<div class="blank_slate_sample"></div>
			</div>
		  	<div class="row">
			  <article class="card blank stars">&nbsp;</article>
			  <article class="card blank stars">&nbsp;</article>
			  <article class="card blank stars">&nbsp;</article>
		  	</div>
		</section>
		<?php } ?>
		<?php if(!empty($updates)){?>
		<section class="projects cards update">
			<div class="row">
		<?php foreach($updates as $project){ ?>
			  <article class="card<?php if($project->status == 0) print ' draft';?>">
				<span class="draft">unpublished</span>
				<span title="Starring a project will highlight it on this page" data-project-id="<?php print $project->nid;?>" data-behavior="project_star" class="star<?php if($project->star) print ' on';?>"></span>
				<a title="<?php print $project->title;?>" href="<?php print $base_url.'/'.$project->alias;?>" class="project_card link_page" data-nid="<?=$project->nid?>">
				  <h5><?php print $project->title;?></h5>
				  <p style="margin-bottom: 5px;"></p>
				  <p style="font-size: 12px;">
					Last updated <time datetime="<?php print date('Y-m-d H:i:s',$project->changed);?>" data-time-ago=""><?php print time_ago($project->changed);?></time>
				  </p>
				  <div class="people">
				  		<?php foreach($project->assigned_user as $member){ ?>
				  		<?php 	if(isset($member->field_profile_picture['und'][0]['value'])){ ?>
				  		<?php 		$title = '';?>
				  		<?php 		if(isset($member->field_profile_fname['und'][0]['value'])) $title .= $member->field_profile_fname['und'][0]['value'].' '; ?>
				  		<?php 		if(isset($member->field_profile_lname['und'][0]['value'])) $title .= $member->field_profile_lname['und'][0]['value']; ?>
				  		<?php 		$title = trim($title);?>
				  		<?php 		if(empty($title)) $title = $member->mail; ?>
				  		 <img width="40" height="40" title="<?php print $title; ?>" src="<?php print $base_url.'/sites/default/files/profile/'.$member->field_profile_picture['und'][0]['value']; ?>" class="avatar">
				  		<?php 	} ?>
				  		<?php } ?>
				  </div>
				</a>          
			 </article>
			 <ul class="updates">
			 	<li class="title">Latest project updates</li>
			 	<?php if(empty($project->updates)){?>
			 	<li>No update</li>
			 	<?php } else { ?>
			 	<?php 	foreach($project->updates as $history){?>
			 	<li>
			 		<span class="date"><?php print date('M d',$history->created);?></span>
			 		<span><?php print $authors[$history->uid]->full_name.' '.$history->content;?>: <a href="<?php if(!empty($history->link)) print $base_url.'/'.$history->link; else print '#';?>"><?php print $history->title;?></a></span>
			 	</li>
			 	<?php 	} ?>
                                <li class="more"><a href="<?php print $base_url;?>/latest-project-updates?nid=<?php print $project->nid;?>" class="link_page">See all updates</a></li>
			 	<?php } ?>
			 </ul>
		<?php } ?>
			</div>
		</section>
		<?php } ?>
       	<a style="display:none" href="<?php print $base_url;?>/trash" data-replace-stack="true" class="trash link_page">Trash can</a>
		<?php if(!empty($list)){?>
        <section class="projects alpha list" style="display: none">
			<header>
				<h1>
			  		All Projects A-Z
			  		<input type="search" tabindex="1" placeholder="Filter projects…" incremental="true" data-behavior="domfilter" class="domfilter staff_only">
				</h1>
		  	</header>
		<?php if(!empty($starred) && $project_view == 'list'){ ?>
			<div class="stars_list">
				<div data-role="grouping">
					<div data-role="heading">
						<hr>
						<div class="letter">
							<img width="15" height="14" src="<?php print $base_url;?>/sites/default/files/styles/star-icon2x.png" alt="Star icon2x">
						</div>
		  			</div>
		<?php 	foreach($starred as $project){ ?>
					<article class="project" data-behavior="has_hover_content">
						<div data-project-id="<?php print $project->nid;?>" data-behavior="project_star" class="star on"></div>
                                                <h3><a href="<?php print $base_url.'/'.$project->alias;?>" class="link_page" data-nid="<?=$project->nid?>"><?php print $project->title;?></a></h3>
					</article>
		<?php 	} ?>
				</div>
			</div>
		<?php } ?>
		<?php $group = '';
			 $groups = array("0","1","2","3","4","5","6","7","8","9");
			 foreach($list as $project){
			 	$first_letter = substr($project->title,0,1);
			 	if(in_array($group,$groups)) $first_letter = '0-9';
			 	if($group != $first_letter){ 
			 		if($group != '') { ?>
			</div>
		<?php 		}
			 	
		 			$group = $first_letter;
		?>
			<div data-role="grouping">
				<div data-role="heading">
				  <hr>
				  <div class="letter"><?php print $group;?></div>
				</div>
		<?php 	} ?>
				<article data-behavior="has_hover_content" class="project">
					<div data-project-id="<?php print $project->nid;?>" data-behavior="project_star" class="star<?php if($project->star) print ' on';?>"></div>
                                        <h3><a href="<?php print $base_url.'/'.$project->alias;?>" class="link_page" data-nid="<?= $project->nid?>"><?php print $project->title;?></a></h3>
			  	</article>
		<?php } ?>
			</div>
		</section>
		<?php } ?>
	<?php if($create_projects || !empty($cards) || !empty($list) || !empty($updates) || !empty($archived)){?>
	</div>
	<?php } ?>
<section class="projects alpha" style="display: none">
	<?php if(!empty($archived)){?>
		<div data-behavior="expandable" class="archived">
			<div class="collapsed_content">
	 			<a href="#" data-behavior="expand_on_click"><?php print count($archived);?> archived project</a>
			</div>
			<div class="expanded_content">
	  			<header>
	    			<h1><?php print count($archived);?> archived project</h1>
	  			</header>
			<?php $group = '';
				 $groups = array("0","1","2","3","4","5","6","7","8","9");
				 foreach($archived as $project){
				 	$first_letter = substr($project->title,0,1);
				 	if(in_array($group,$groups)) $first_letter = '0-9';
				 	if($group != $first_letter){ 
				 		if($group != '') { ?>
				</div>
			<?php 		}
				 	
			 			$group = $first_letter;
			?>
				<div data-role="grouping">
					<div data-role="heading">
					  <hr>
					  <div class="letter"><?php print $group;?></div>
					</div>
			<?php 	} ?>
					<article data-behavior="has_hover_content" class="project">
						<div data-project-id="<?php print $project->nid;?>" data-behavior="project_star" class="star<?php if($project->star) print ' on';?>"></div>
                                                <h3><a href="<?php print $base_url.'/'.$project->alias;?>" class="link_page" data-nid="<?=$project->nid?>"><?php print $project->title;?></a></h3>
				  	</article>
			<?php } ?>
				</div>
			</div>
  		</div>
  	<?php } ?>
	</section>
    <div style="display: none" class="starred_project_notification">This project will now appear as a card at the top of the page.</div>
</div>

<!--<script type="text/javascript" src="http://cdn.knightlab.com/libs/timeline/latest/js/storyjs-embed.js"></script>-->

      
            

	
	


<script >
	jQuery(document).ready(function(){
        load_page();
        add_menu_active();
		jQuery(".star").bind("click",function(){
			var actions = jQuery(this).attr("data-behavior").split(' ');
			if(actions.length>0){
				for(var i = 0; i<actions.length; i++)
					js_process(this,actions[i]);
			}
		});
		jQuery(".list article").hover(function(){
			var star = jQuery(this).children(".star");
			if(!jQuery(star).hasClass("on")){
				jQuery(star).addClass("showing");
				jQuery(star).css("visibility","visible");
			}
		},function(){
			var star = jQuery(this).children(".star");
			if(!jQuery(star).hasClass("on")){
				jQuery(star).removeClass("showing");
				jQuery(star).css("visibility","hidden");
			}
		});
                
//            jQuery.get("<?=$base_url?>/iframe_mile",{nid:60},function(data){alert(data);
//                jQuery(".iframe_60").append(data);                
//                
//            }); 
               
                
                
                
		data_behavior();
	});
      
	function expand_on_click(me){
		jQuery(me).parents(".archived").addClass("expanded");
	}
	function project_star(me){
		star = 1;
		if(jQuery(me).hasClass("on"))
			star = 0;
		if(star == 0)
			jQuery(me).removeClass("on");
		else jQuery(me).addClass("on");
		var nid = jQuery(me).attr("data-project-id");
		jQuery.post("<?php print $base_url;?>/ajax-process",{action:"project_star",star:star,nid:nid},function(data){
			jQuery("#block-system-main").html(data);
		});
	}
	function change_project_view(me){
		jQuery(".project_view").addClass("busy");
		var view = jQuery(me).attr("class");
		jQuery.post("<?php print $base_url;?>/ajax-process",{action:"load_content",content:"projects",project_view:view,page:false},function(data){
			jQuery("#block-system-main").html(data);
		});
	}
//	function new_project(me){
//		jQuery(".projects").animate({"padding-top":"100px"},500);
//		jQuery("header").animate({opacity:0},500,"swing");
//		jQuery(".container").animate({opacity:0},500,"swing",function(){
//			jQuery("body").append('<div id="new_project_dialog" style="display: block; padding: 0px;" data-behavior="new_project_dialog"><article class="new" style="opacity: 1; width: 0; height: 0; overflow: hidden;"></div></div>');
//			jQuery("#new_project_dialog > article").animate({width:640, height:283}, 500, "swing", function(){
//				jQuery.post("<?php print $base_url;?>/ajax-process",{action:"load_content",content:"add_project"},function(data){
//					jQuery("#new_project_dialog > article").html(data);
//					jQuery("#new_project_dialog > article").css("height","auto");
//				});
//			});
//		});
//	}
//	function cancel_new_project(me){
//		var height = jQuery("#new_project_dialog > article").height();
//		jQuery("#new_project_dialog > article").css("height",height+"px");
//		jQuery("#new_project_dialog > article").html("");
//		jQuery("#new_project_dialog > article").animate({width:0, height:0}, 500, "swing", function(){
//			jQuery("#new_project_dialog").remove();
//			jQuery(".projects").animate({"padding-top":"0"},500);
//			jQuery("header").animate({opacity:1},500,"swing");
//			jQuery(".container").animate({opacity:1},500,"swing");
//		});
//	}
//	function start_blank_project(me){
//		jQuery(".project_template").css("display","none");
//		jQuery("#new_project").css("display","block");
//	}
//	function load_templates(me){
//		open_page_loading();
//		jQuery.post("<?php print $base_url;?>/ajax-process",{action:"load_content",content:"templates"},function(data){
//			jQuery("#workspace").html(data);
//			window.history.replaceState('Object', 'Title', jQuery(me).attr('href'));
//		});
//	}
</script>
<style>
    .contact_support_button{display: none!important}
    
    div.workspace > div.container {
        margin: 0 auto;
        width: 1570px;
    }
    
    
    html, #workspace{
        background: white;
        
    }
    #workspace {
        
        border:none;
    }

    div.panel.home_tab section.projects.cards article.card a.project_card{
        color:#9A9A9A;
        
    }
    body{
        
        margin:0px;
    }
/*    #mp-menu > .mp-level{
        margin-top: 0px;
    }*/
  
</style>
<script>

jQuery(document).ready(function () {
        jQuery(".next_buton_click").click(function(){
                var effect = 'slide';
 
                // Set the duration (default: 400 milliseconds)
                var duration = 800;

            var next=jQuery(this).parent().attr("next");
            jQuery( this ).parent().hide( effect, { direction: 'left' }, duration);
            jQuery( ".slide_"+next ).show( effect, { direction: 'right' }, duration);
            
        });
       jQuery(".next_member").click(function(){
                 var effect = 'slide';
                 jQuery(this).hide();
                // Set the duration (default: 400 milliseconds)
                var duration = 800;

            var next=jQuery(this).parent().attr("next");
            jQuery( this ).parent().hide( effect, { direction: 'left' }, duration);
            jQuery(this).parent().parent().parent().find( ".li_member_"+next ).show( effect, { direction: 'right' }, duration);
            jQuery(this).show();
            
        });
//      jQuery(".item_project_block").jCarouselLite({
//           btnNext: ".next",
//            btnPrev: ".prev",
//            visible: 4
//      });
      
      
    
       
});


//   
//    jQuery(function() {
//    jQuery(".item_project_block").jCarouselLite({
//        btnNext: ".next",
//        btnPrev: ".prev",
//        visible: 3,
//    });
//});
//
//        jQuery( ".home #block-system-main" ).hover(
//       function() {
//       jQuery( "#next_slide" ).fadeIn(500);
//       jQuery( "#pre_slide" ).fadeIn(500);
//        }, function() {
//        jQuery("#next_slide").fadeOut(500);
//        jQuery("#pre_slide").fadeOut(500);
//        }
//        );


</script>
<!--<script type="text/javascript" src="<?= $base_url ?>/sites/all/libraries/timeline.js"></script>-->


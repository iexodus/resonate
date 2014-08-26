


<?php global $base_url, $user; ?>

<script type="text/javascript" src="<?php echo $base_url; ?>/sites/all/modules/project/js/jcarousellite.js"></script>
<link href="<?=$base_url?>/sites/all/modules/project/css/custom_css.css" rel="stylesheet">
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
                        <ul>
<!--			<div class="row ">-->
		<?php $count = 0;?>
		<?php foreach($cards as $project){?>
                         
                                <li>
				  <article class="card<?php if($project->status == 0) print ' draft';?>">
<!--				    <span class="draft">unpublished</span>-->
				    <!--<span title="Starring a project will highlight it on this page" data-project-id="<?php print $project->nid;?>" data-behavior="project_star" class="star<?php if($project->star) print ' on';?>"></span>-->
                                    
				      <p style="margin-bottom: 5px;"></p>
<!--				      <p style="font-size: 12px;">
				        Last updated <time datetime="<?php print date('Y-m-d H:i:s',$project->changed);?>" data-time-ago=""><?php print time_ago($project->changed);?></time>
				      </p>-->
                                       <a title="<?php print $project->title;?>" href="<?php print $base_url.'/'.$project->alias;?>" class="project_card link_page" data-nid="<?=$project->nid?>">
                                      <div class="image_project">
                                      <?php if(isset($project->	field_project_image_text["und"][0]["value"])):?>
                                            <?php
                                                $src=$base_url.'/sites/default/files/img_project/'.$project->field_project_image_text["und"][0]["value"];
                                            ?>
                                          <img src="<?=$src?>">
                                      <?php endif;?>
                                      </div>
                                     
				      <h5><?php print $project->title;?></h5>
                                      
                                      
                                       <div class="description_prj"><?= isset($project->body["und"][0]["value"])?$project->body["und"][0]["value"]:''?></div>
                                       <div class="line"></div>
                                       
<!--                                       <div class="milestone">
                                           <div class="title title_milestone">Milestone:</div>
                                           <div class="content-milestone">
                                               <?php 
                                                    $list_milestone=  db_query("SELECT * FROM tbl_project_attribute WHERE type='6' AND nid='$project->nid' ORDER BY aid DESC LIMIT 0,10");
                                                    $count_milestone=  db_query("SELECT COUNT(aid) FROM tbl_project_attribute WHERE type='6' AND  nid='$project->nid' ORDER BY aid DESC LIMIT 0,10")->fetchField();
                                                    
                                               ?>
                                               <?php if($count_milestone>0):?>
                                                    <?php foreach ($list_milestone as $milestone):?>
                                                        <?php 
                                                            $date_mile=0;
                                                            $fl=0;
                                                            if($milestone->assign_date!=''){
                                                                    $date_mile=$milestone->assign_date;
                                                                    $fl=1;
                                                            }
                                                            if($fl==0){
                                                                 $date_mile=0;
                                                            }
                                                            else{
                                                                $date_mile=strtotime($date_mile);
                                                            }
                                                             $today=date('Y-m-j')   ;
                                                            
                                                             $today=strtotime($today);
                                                            
                                                        ?>
                                                    <?php  if($date_mile>= $today):?>
                                                    <?php 
                                                        $ls_date=  explode("-", $milestone->assign_date);
                                                        $dt=$ls_date[1]."/".$ls_date[2];
                                                    ?>
                                               <div class="container_mile">
                                                   <div class="date_mile"><?=$dt?></div>
                                                   <div class="title_mile">-<?=$milestone->subject?></div>
                                                   
                                               </div>
                                                    <?php endif;?>
                                                    <?php endforeach;?>
                                               
                                               <?php endif;?>
                                               

                                           </div>
                                       </div>-->
                                       </a>	
                                       <div class="line"></div>
<!--				      <div class="people">
                                                <?php $count_pp=0;?>
				      		<?php foreach($project->assigned_user as $member){?>
                                                <?php //if($count_pp<2){?>
				      		<?php 	if(isset($member->field_profile_picture['und'][0]['value'])){?>
				      		<?php 		$title = '';?>
				      		<?php 		if(isset($member->field_profile_fname['und'][0]['value'])) $title .= $member->field_profile_fname['und'][0]['value'].' ';?>
				      		<?php 		if(isset($member->field_profile_lname['und'][0]['value'])) $title .= $member->field_profile_lname['und'][0]['value'];?>
				      		<?php 		$title = trim($title);?>
				      		<?php 		if(empty($title)) $title = $member->mail;?>
				      		<div class="content_member"> 
                                                <img width="40" height="40" title="<?php print $title;?>" src="<?php print $base_url.'/sites/default/files/profile/'.$member->field_profile_picture['und'][0]['value'];?>" class="avatar">
                                                 <div class="name_member">
                                                     <?php 
                                                        $name=$member->name;
                                                        if(isset($member->field_profile_fname['und'][0]['value'])&&isset($member->field_profile_lname['und'][0]['value']))
                                                        {
                                                            
                                                            $name=$member->field_profile_fname['und'][0]['value']." ".$member->field_profile_lname['und'][0]['value'];
                                                        }
                                                         ?>
                                                     <?=$name?>
                                                     
                                                 </div>
                                                
                                                </div>
                                          <div class="clear"></div>
				      		<?php 	$count_pp++;} ?>
                                                <?php }//} ?>
				      </div>-->
				          
				</article>
                                </li>
                <?php }?>
                        </ul>
                        </div>
                    <div id="next_slide" class="next" style="display: none"></div>
			</div>
    <button  class="next_down">v</button>
            
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

    
    html, #workspace{
        background: white;
        
    }
    #workspace {
        
        border:none;
    }
    .home #block-system-main{
        
         border:none !important;
    }
    div.panel.home_tab section.projects.cards article.card a.project_card{
        color:#9A9A9A;
        
    }
</style>
<script>

   
//      jQuery(function() {
//    jQuery(".item_project_block").jCarouselLite({
//        btnNext: ".next",
//        btnPrev: ".prev",
//        visible: 2,
////       init: function(opts, lis) { alert(lis.length);
//////        if ($lis.length > opts.visible) {
//////          $('.container_all_project_block').append('<a class="prevnext prev">previous</a> <a class="prevnext next">next</a>');
//////        } else {
//////          return false;
//////        }
////      }
//    });
//});

        jQuery( ".home #block-system-main" ).hover(
       function() {
       jQuery( "#next_slide" ).fadeIn(500);
        }, function() {
        jQuery("#next_slide").fadeOut(500);
        }
        );


</script>
<script>
    jQuery(document).ready(function(){
        
        
        
        
        data_behavior();
            var click=1;
          jQuery(".next_down").click(function() {
           var scl=click*250;
           jQuery('html, body').animate({
                scrollTop: jQuery("#all_projects").offset().top+scl
            }, 200);
            click++;
        });

        
        
        
    });
    function new_project_mobile(me){
            jQuery.post("<?php print $base_url; ?>/ajax-process",{action:"load_content",content:"add_project"},function(data){
                jQuery(".panel").html(data);
                 jQuery(".panel").addClass("sheet");
           jQuery(".panel").attr("data-behavior","expandable");
                jQuery(".panel").css("height","auto");
            });
    }
                  
</script>


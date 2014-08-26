<?php global $base_url,$user;?>
<?php $nproject = 0;?>
<?php $result = db_query("SELECT COUNT(nid) as total FROM node WHERE type = 'project'");?>
<?php foreach($result as $object) $nproject = $object->total;?>
<?php $is_about = arg(1);?>
<?php if(!$is_about){?>
<div class="container stack_container" data-container-id="3" style="width: 960px;">
    <div id="block-system-main" class="block block-system no-title">
	<div class="panel sheet project_templates has_sidebar">
		<title>Project Templates</title>
		<header class="has_buttons">
		  <h1>Project Templates</h1>
		    <span class="position_reference">
		      <form method="post" id="new_template" data-remote="true" class="button_to" action="<?php print $base_url;?>/ajax-process?action=add_template">
		      	<div>
		      		<input type="submit" value="Add a new project template" class="action_button">
		      		<input type="hidden" value="I0gQBDOaXDhQ49+i81c0+fi/p8yG9cSlbBPHB9B2KKE=" name="authenticity_token">
		      	</div>
		      </form>
		      <div class="blank_slate_arrow"></div>
		    </span>
		</header>

		<div class="sheet_body">
		  
		  <section class="project_templates">
		  <?php foreach($templates as $template){?>
		      <article data-creator-id="<?php print $template->nid;?>" data-behavior="expandable expand_exclusively" class="card">
		        <div>
		          <a href="#" data-behavior="show_template_actions">
		            <header></header>
		            <h5><?php print $template->title;?></h5>
		            <p></p>
		            <p class="meta"><?php print $template->discussions;?> discussion<?php if($template->discussions>1 || $template->discussions == 'no') print 's';?>, <?php print $template->todos;?> to-do<?php if($template->todos>1 || $template->todos == 'no') print 's';?>, <?php print $template->files;?> file<?php if($template->files>1 || $template->files == 'no') print 's';?>, <?php print $template->documents;?> document<?php if($template->documents>1 || $template->documents == 'no') print 's';?>, <?php print $template->emails;?> email<?php if($template->emails>1 || $template->emails == 'no') print 's';?>, <?php print $template->dates;?> date<?php if($template->dates>1 || $template->dates == 'no') print 's';?>, <?php echo $template->times;?> time<?php if($template->times>1 || $template->times == 'no') print 's';?></p>

		            <div class="people">
			      		<?php foreach($template->assigned_user as $member){?>
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
		          <div class="actions expanded_content">
		            <ul>
		              <li class="draft"><a href="#<?php print $template->nid;?>" data-behavior="create_draft_project">Start a new project with this template</a></li>
		              <li class="edit"><a title="<?php print $template->title;?>" href="#<?php print $template->nid;?>" data-replace-sheet="true" data-behavior="edit_template">Edit</a></li>
		              <li data-visible-to="admin creator" class="delete"><a href="#" data-behavior="confirm_delete_template">Delete</a></li>
		            </ul>
		            <div class="confirm_delete">
		              <h4>We'll only delete the template. No projects will be deleted.</h4>
		              <p><a rel="nofollow" href="#<?php print $template->nid;?>" data-remote="true" data-method="post" class="action_button button" data-behavior="delete_template">Delete this template</a></p>
		              <p class="cancel"><a data-behavior="cancel_delete_template" href="#">Nevermind</a></p>
		            </div>
		          </div>
		        </div>
		      </article>
		  <?php } ?>
		  </section>
		</div>
		<aside>
		  <h4>About project templates</h4>
		  <p>Find yourself creating the same project over and over? Save time and use a project template instead. Every message, to-do list, file, text document, and date will be added to your project automatically.</p>
		  <p><a href="<?php print $base_url;?>/templates/about" class="decorated">Learn more</a> about who can create, edit, and delete templates, how notifications are handled, and how project dates get added to the calendar.</p>
		</aside>
	</div>
        </div>
</div>
<script>
	var options = { 
		beforeSubmit:  showRequest,
		success:       showResponse
	}; 
	
	function showRequest(formData, jqForm, options) { 
		jQuery("#new_template").addClass("busy");
	}
	
	function showResponse(responseText, statusText, xhr, form) { 
		var result = jQuery.parseJSON(responseText);
		if(result.error == 1){
			jQuery("#new_template").removeClass("busy");
		}
		else{
			window.history.replaceState('Object', 'Title', result.url);
			jQuery("#workspace").before(result.header);
			//jQuery("#workspace > .container").html(result.content);
                        jQuery("#block-system-main").html(result.content);
		}
	}
	function show_template_actions(me){
		var card = jQuery(me).parents(".card").first();
		if(jQuery(card).hasClass("expanded"))
			return false;
		jQuery(".card").removeClass("expanded");
		jQuery(card).addClass("expanded");
		var expanded_content = jQuery(me).next();
		jQuery(expanded_content).css("margin-top","30px");
		jQuery(expanded_content).animate({"margin-top":0,opacity:1},250);
	}
	function confirm_delete_template(me){
		var actions = jQuery(me).parents(".actions");
		jQuery(actions).children("ul").css("display","none");
		jQuery(actions).children(".confirm_delete").css({"display":"block","opacity":1});
	}
	function cancel_delete_template(me){
		var actions = jQuery(me).parents(".actions");
		jQuery(actions).children("ul").css("display","block");
		jQuery(actions).children(".confirm_delete").css({"display":"none","opacity":0});
		jQuery(actions).parents(".card").removeClass("expanded");
	}
	function edit_template(me){
		jQuery(".card").addClass("inactive");
		jQuery(".card a").addClass("inactive");
		var card = jQuery(me).parents(".card");
		jQuery(card).removeClass("expanded");
		jQuery(card).addClass("loading");
		var secs = me.href.split('#');
		var nid = secs[1];
		jQuery.post("<?php print $base_url;?>/ajax-process",{action:"load_content",content:"template",nid:nid},function(data){
			open_page_loading();
			window.history.replaceState('Object', 'Title', data.url);
			jQuery(".panel").html(data.content);
			jQuery(".panel").removeClass("loading");
		},"json");
	}
	function delete_template(me){
		jQuery(".card").addClass("inactive");
		jQuery(".card a").addClass("inactive");
		var card = jQuery(me).parents(".card");
		jQuery(card).removeClass("expanded");
		jQuery(card).addClass("loading");
		var secs = me.href.split('#');
		var nid = secs[1];
		jQuery.post("<?php print $base_url;?>/ajax-process",{action:"delete_node",nid:nid},function(data){
			if(data.error == 1){
				jQuery(".card").removeClass("inactive");
				jQuery(".card a").removeClass("inactive");
				jQuery(card).removeClass("loading");
			}
			else{
				jQuery(".card").removeClass("inactive");
				jQuery(card).remove();
			}
		},"json");
	}
	function create_draft_project(me){
		var secs = me.href.split('#');
		var project_template_id = secs[1];
		jQuery(".card").addClass("inactive");
		jQuery(".card a").addClass("inactive");
		var card = jQuery(me).parents(".card");
		jQuery(card).addClass("loading");
		jQuery.post("<?php print $base_url;?>/ajax-process",{action:"add_project",project_template_id:project_template_id},function(data){
			if(data.error == 1){
				jQuery(".card").removeClass("inactive");
				jQuery(".card a").removeClass("inactive");
				jQuery(card).removeClass("loading");
			}
			else{
				jQuery("header").animate({opacity:1},500,"swing");
				window.history.replaceState('Object', 'Title', data.url);
				//jQuery("#workspace > .container").html(data.content);
                                jQuery("#block-system-main").html(data.content);
                                
			}
		},"json");
	}
	jQuery(document).ready(function(){
		jQuery("form#new_template").ajaxForm(options); 
		data_behavior();
		jQuery("body").bind("click",function(){
			jQuery(".card").removeClass("expanded");
		});
		jQuery(".card").bind("click",function(event){
			event.stopPropagation();
		});
	});
</script>
<?php } else { ?>
<div class="container stack_container" data-container-id="2" style="width: 980px;">
	<div class="panel sheet project_templates has_sidebar inactive">
		<header><h1><a data-restore-position="" href="<?php print $base_url.'/templates';?>">Project Templates</a></h1></header>
		<div class="panel sheet about_project_templates has_sidebar" style="margin-left: 20px; margin-bottom: -20px;">
    <title>About project templates</title>

    <header>
      <h1>About project templates</h1>
    </header>

    <div class="sheet_body">
      <h4>Who can create project templates?</h4>
      <p>
        Anyone who can create projects on your account can add, edit, or delete project templates.
          To change this setting, <a href="<?php print $base_url;?>/people/permissions" data-replace-sheet="true" class="decorated">visit the superpowers page</a>.
      </p>

      <h4>If I change a template, will it affect projects I've already created?</h4>
      <p>Nope. Your existing projects will not be affected.</p>

      <h4>Is there any limit to the number of project templates that I can create?</h4>
      <p>Each Freebasecamp account can hold any number of project templates. When you store files within your project templates, these files will count against your overall file storage limit.</p>

      <h4>Will people be notified when I create a project from a template?</h4>
      <p>If you've invited people to your template, Freebasecamp will automatically notify them when you publish a project using that template. They'll be given access to the project and any content you've already added. You can also add or remove people before you publish your project, even if they weren't included in the template originally.</p>

      <h4>Can I import templates from Freebasecamp Classic into our new Freebasecamp?</h4>
      <p>It's not possible to import templates created in a Freebasecamp Classic account into the new Freebasecamp, so they'll need to be re-created.</p>

      <h4>How do events work with a project template?</h4>
      <p>When you add events to a template, you'll choose which week and day they should occur, relative to the project's start date. (For example, a kick-off meeting might happen on <strong>Monday of Week 1</strong>.) Week 1 begins on the Sunday prior to the project's start date.</p>
      <p>You'll have a chance to preview and reschedule everything before the project goes live.</p>
    </div>
  </div>
  </div>
</div>
<?php } ?>

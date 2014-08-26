<?php global $base_url,$user;?>
<?php $with_clients = isset($project->field_with_clients['und'][0]['value'])?$project->field_with_clients['und'][0]['value']:0;?>
<div class="panel sheet project active inactive" data-behavior="<?php if($project->sticky) echo 'read_only';?>" data-creator-id="<?php print $project->nid;?>" data-status="<?php if($project->sticky) echo 'archived'; else echo 'active';?>">
    <header>
        <h1><a data-restore-position="" href="<?php print $base_url.'/'.$project->alias;?>" class="link_page" data-nid="<?=$project->nid?>"><?php print $project->title;?></a></h1>
    </header>
    <div class="panel sheet accesses" data-behavior="prevent_reload_when_stacked" style="margin-left: 20px; margin-bottom: -20px;">
        <header>
            <h1 class="inactive_title">People on this project</h1>
            <div class="active_title">
                <h1>Here’s who’s on this project</h1>
                <p>Invite people from your team or from your client and
                start working together in seconds. Everyone you invite will
                receive a welcome email.</p>
            </div>
        </header>
        <div class="sheet_body">
            <div class="accesses_tabs">
            <?php if(!$is_client){?>
                <ul>
                    <li><a class="selected" data-accesses-list="team" data-behavior="toggle_accesses_list" href="#team">Our Team</a></li>
                    <li>
                        <a data-accesses-list="client" data-behavior="toggle_accesses_list" href="#client">The Client</a>
                        <figure style="<?php if($with_clients) print 'display:none;';?>">
                            Working with a client? Invite them here.
                            Clients can’t see posts unless you say so.
                        </figure>
                    </li>
                </ul>
            <?php } ?>
            </div>
            <?php if(!$is_client){ ?>
            <section class="split_accesses" data-accesses-list="team" data-behavior="accesses_list" style="display: block;">
                <div class="columns">
                    <div class="column">
                        <section class="invite" data-new-team="">
                            <header style="margin: 0; display: none">
                                <h1>Add more people</h1>
                            </header>
                            <form id="invite-members" accept-charset="UTF-8" action="<?php print $base_url;?>/ajax-process?action=invite_members" class="invite" data-remote="true" method="post">
                                <div style="margin:0;padding:0;display:inline">
                                    <input name="utf8" type="hidden" value= "✓">
                                </div>
                                <div class="slider accesses team_accesses" data-behavior="slider">
                                    <ul class="slides" style="width: 200%;">
                                        <li class="slide active" data-slide-index="0" style="width: 360px;">
                                            <h2>Type names or emails to invite people to your team:</h2>
                                            <div class="invitees">
                                                <div class="person invitee field blank">
                                                	<div class="autocomplete_people">
                                                        <input data-role="email_address_input" name="invitees[][email_address]" type="hidden" value="">
                                                        <div class="icon"></div>
                                                        <div class="input">
                                                            <input data-behavior="input_change_emitter" data-role="human_input" spellcheck="false" type="text" value="" onkeydown="press_key(this,event)" onkeyup="search_person(this,'team')" onchange="change_person(this)" onfocus="focus_person(this)" onblur="blur_person(this)">
                                                        </div>
                                                        <div class="suggestions" data-role="suggestions_view">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="person invitee field blank">
                                                	<div class="autocomplete_people">
                                                        <input data-role="email_address_input" name="invitees[][email_address]" type="hidden" value="">
                                                        <div class="icon"></div>
                                                        <div class="input">
                                                            <input data-behavior="input_change_emitter" data-role="human_input" spellcheck="false" type="text" value="" onkeydown="press_key(this,event)" onkeyup="search_person(this,'team')" onchange="change_person(this)" onfocus="focus_person(this)" onblur="blur_person(this)">
                                                        </div>
                                                        <div class="suggestions" data-role="suggestions_view">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="person invitee field blank">
                                                	<div class="autocomplete_people">
                                                        <input data-role="email_address_input" name="invitees[][email_address]" type="hidden" value="">
                                                        <div class="icon"></div>
                                                        <div class="input">
                                                            <input data-behavior="input_change_emitter" data-role="human_input" spellcheck="false" type="text" value="" onkeydown="press_key(this,event)" onkeyup="search_person(this,'team')" onchange="change_person(this)" onfocus="focus_person(this)" onblur="blur_person(this)">
                                                        </div>
                                                        <div class="suggestions" data-role="suggestions_view">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p id="new_superpowers_prompt" style="display: none;">Looks like you're inviting new people to Freebasecamp!<br>
                                            <a class="decorated" data-behavior="slide" data-direction="forward" href="#">Decide who can create projects...</a></p>
                                            <h2>Add a welcome message for your team:</h2>
                                            <textarea name="message">Hi there. We’ll be using Freebasecamp to share ideas, gather feedback, and track progress during this project.
</textarea>
                                        </li>
                                        <li class="slide" data-slide-index="1" style="width: 360px;">
                                            <table border="0" cellpadding="0" cellspacing="0" class="header">
                                                <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td class="option projects">Can create projects?</td>
                                                        <td class="option admin">Admin</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div id="new_superpowers"></div>
                                            <p class="glossary">Admins can create projects, remove anyone from a project, remove users from your Freebasecamp account, and grant admin powers to other people. We recommend only granting admin powers to people you really trust.</p>
                                            <p><a class="decorated done_editing" data-behavior="slide" data-direction="backward" href="#">← Back to the list of people</a></p>
                                        </li>
                                    </ul>
                                </div>
                                <input type="hidden" name="nid" value="<?php print $project->nid;?>"/>
                                <p class="submit"><input class="button action_button green" name="commit" type="submit" value="Send invitation"></p>
                            </form>
                        </section>
                    </div>
                    <div class="column">
                        <section class="accesses infinite_page" data-behavior="infinite_page" data-infinite-page="2" id="team_accesses">
                            <header><p>Anyone on your team will see everything posted to this project. Every message, to-do list, file, event, and text document.</p></header>
                            <?php foreach($users as $member){?>
                            <article class="access" data-person-id="<?php print $member->uid;?>" id="access_<?php print $member->uid;?>">
                                <div class="wrapper">
                                    <a data-replace-stack="true" class="link_page" href="<?php print $base_url.'/user/'.$member->uid;?>" data-uid="<?=$member->uid?>">
                                    <img class="avatar" height="96" src="<?php print $base_url.'/sites/default/files/profile/'.$member->field_profile_picture['und'][0]['value'];?>"
                                    title="<?php print $member->fname;?>" width="96"></a>
                                    <?php if(is_email($member->fname)){?>
                                    <?php 	$secs = explode('@',$member->fname);?>
									<h1>
										<span class="mailbox"><?php print $secs[0];?></span><span class="at_symbol">@</span><span class="domain"><?php print $secs[1];?></span>
									</h1>
                                    <?php } else { ?>
									<h1>
										<a class="person_name link_page" href="<?php print $base_url.'/user/'.$member->uid;?>" data-uid="<?=$member->uid?>"><?php print $member->fname;?></a>
		                                <div class="email">
		                                    <a href="mailto:<?php print $member->mail;?>"><?php print $member->mail;?></a>
		                                </div>
		                            </h1>
                                    <?php } ?>
                                    <div class="last_invitee_event">Active <time data-time-ago="" datetime="2013-12-02T07:33:07Z">2 hours ago</time></div>
                                    <?php if($member->uid == 2){?>
                                    <div class="intro">The account owner has access to all projects.</div>
                                    <?php } else if($member->uid != $project->uid){?>
									<div class="controls">
										<div class="invite">
											<a rel="nofollow" href="" data-remote="true" data-method="post" onclick="invite_resend(this,event,<?php print $project->nid;?>,<?php print $member->uid;?>)" data-activated-text="Sending invitation…" class="decorated">Send another invitation</a>
										</div>
										<form method="post" class="revoke" action="" accept-charset="UTF-8">
											<div style="margin:0;padding:0;display:inline">
												<input type="hidden" value="✓" name="utf8">
												<input type="hidden" value="delete" name="_method">
											</div>
											<div class="intro">
												<a onclick="start_remove(this,event)" class="decorated">Remove</a>
											</div>
											<div style="display:none" class="confirm">
												<span class="question">Remove The from this project?</span>
												<input type="button" value="Remove" name="commit" onclick="remove_team(this,<?php print $project->nid;?>,<?php print $member->uid;?>)"> or <a onclick="stop_remove(this,event)" class="decorated">Keep Them</a>
											</div>
											<div style="display:none" class="request">
												Removing <?php print $member->fname;?>…
											</div>
										</form>   
									</div>   
									<?php } ?>
                                </div>
                            </article>
                            <?php } ?>
                        </section>
                    </div>
                </div>
            </section>
            <?php } ?>
            <section class="split_accesses" data-accesses-list="client" data-behavior="accesses_list" style="<?php if(!$is_client) print 'display: none;'?>">
                <section class="client_projects_prompt" data-behavior="opt_into_client_projects" style="<?php if($with_clients) print 'display:none;';?>">
                    <figure class="initial_load">
                        <figcaption>
                            You’ll see a checkbox like this whenever you
                            post something to a project with clients.
                        </figcaption>
                    </figure>
                    <article>
                        <h3>Working with a client on this project?</h3>
                        <p>Freebasecamp lets you hide certain messages, to-dos,
                        files, events, and text documents from people
                        invited as clients. This is great for sharing
                        unfinished work with your team before getting
                        client feedback.</p>
                        <p>You can turn this on now to start choosing what
                        clients can see, even if you’re not ready to invite
                        any clients yet.</p>
                        <p class="submit"><a class="button action_button big" data-behavior="confirm_opt_into_client_projects" data-method="put" data-remote="true" href="" rel="nofollow">Yes, turn on client access for this project</a></p>
                    </article>
                </section>
                <div class="columns <?php if(!$with_clients) print 'hidden';?>" data-behavior="clients_list">
                    <div class="column">
                        <section class="invite" data-new-client="">
                            <header style="margin: 0; display: none">
                                <h1>Add more people</h1>
                            </header>
                            <form id="invite-clients" accept-charset="UTF-8" action="<?php print $base_url;?>/ajax-process?action=invite_clients" class="invite" data-remote="true" method="post">
                                <div style="margin:0;padding:0;display:inline">
                                    <input name="utf8" type="hidden" value="✓">
                                </div>
                                <div class="slider accesses client_accesses" data-behavior="slider">
                                    <ul class="slides">
                                        <li class="slide<?php if($with_clients) print ' active';?>" data-slide-index="0" style="<?php if($with_clients) print 'width:360px;';?>">
                                            <h2>Type names or emails to invite your clients:</h2>
                                            <div class="invitees">
                                                <div class="person invitee field blank">
                                                	<div class="autocomplete_people">
                                                        <input data-role="email_address_input" name="client_invitees[][email_address]" type="hidden" value="">
                                                        <div class="icon"></div>
                                                        <div class="input">
                                                            <input data-behavior="input_change_emitter" data-role="human_input" spellcheck="false" type="text" value="" onkeydown="press_key(this,event)" onkeyup="search_person(this,'client')" onchange="change_person(this)" onfocus="focus_person(this)" onblur="blur_person(this)">
                                                        </div>
                                                        <div class="suggestions" data-role="suggestions_view"></div>
                                                    </div>
                                                </div>
                                                <div class="person invitee field blank">
                                                	<div class="autocomplete_people">
                                                        <input data-role="email_address_input" name="client_invitees[][email_address]" type="hidden" value="">
                                                        <div class="icon"></div>
                                                        <div class="input">
                                                            <input data-behavior="input_change_emitter" data-role="human_input" spellcheck="false" type="text" value="" onkeydown="press_key(this,event)" onkeyup="search_person(this,'client')" onchange="change_person(this)" onfocus="focus_person(this)" onblur="blur_person(this)">
                                                        </div>
                                                        <div class="suggestions" data-role="suggestions_view"></div>
                                                    </div>
                                                </div>
                                                <div class="person invitee field blank">
                                                	<div class="autocomplete_people">
                                                        <input data-role="email_address_input" name="client_invitees[][email_address]" type="hidden" value="">
                                                        <div class="icon"></div>
                                                        <div class="input">
                                                            <input data-behavior="input_change_emitter" data-role="human_input" spellcheck="false" type="text" value="" onkeydown="press_key(this,event)" onkeyup="search_person(this,'client')" onchange="change_person(this)" onfocus="focus_person(this)" onblur="blur_person(this)">
                                                        </div>
                                                        <div class="suggestions" data-role="suggestions_view"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <h2>Add a welcome message for your clients:</h2>
                                            <textarea name="client_message">Hi there. We'll be using Freebasecamp to share ideas, gather feedback, and track progress during this project. Simply log in or create an account and you’ll be up and running in no time.</textarea>
                                        </li>
                                    </ul>
                                </div>
                                <input type="hidden" name="nid" value="<?php print $project->nid;?>"/>
                                <p class="submit"><input class="button action_button green" name="commit" type="submit" value="Send invitation"></p>
                            </form>
                        </section>
                    </div>
                    <div class="column">
                        <section class="accesses <?php if(empty($clients)) print 'blank_slate';?>" id="client_accesses">
                            <header>
                                <p>You can hide certain messages, to-do
                                lists, files, events, and text documents
                                from people invited as clients.</p>
                            </header>
                            <header class="blank_slate">
                                <figure>
                                    <figcaption>
                                        Now you can choose what clients can
                                        see, even if you haven’t invited
                                        them to this project yet. Just
                                        check this box when you post. Easy!
                                    </figcaption>
                                </figure>
                                <p class="submit">Not working with clients on this project? You can <a class="decorated" data-behavior="confirm_opt_out_of_client_projects" data-method="put" data-remote="true" href="" rel="nofollow">turn this option off</a></p>
                            </header>
                            <?php if($is_client){?>
                            <?php foreach($users as $member){?>
                            <article class="access" data-person-id="<?php print $member->uid;?>" id="access_<?php print $member->uid;?>">
                                <div class="wrapper">
                                    <a data-replace-stack="true" class="link_page" href="<?php print $base_url.'/user/'.$member->uid;?>" data-uid="<?=$member->uid?>">
                                    <img class="avatar" height="96" src="<?php print $base_url.'/sites/default/files/profile/'.$member->field_profile_picture['und'][0]['value'];?>"
                                    title="<?php print $member->fname;?>" width="96"></a>
                                    <?php if(is_email($member->fname)){?>
                                    <?php 	$secs = explode('@',$member->fname);?>
									<h1>
										<span class="mailbox"><?php print $secs[0];?></span><span class="at_symbol">@</span><span class="domain"><?php print $secs[1];?></span>
									</h1>
                                    <?php } else { ?>
									<h1>
										<a class="person_name link_page" href="<?php print $base_url.'/user/'.$member->uid;?>" data-uid="<?=$member->uid?>"><?php print $member->fname;?></a>
		                                <div class="email">
		                                    <a href="mailto:<?php print $member->mail;?>"><?php print $member->mail;?></a>
		                                </div>
		                            </h1>
                                    <?php } ?>
                                    <div class="last_invitee_event">Active <time data-time-ago="" datetime="2013-12-02T07:33:07Z">2 hours ago</time></div>
                                    <?php if($member->uid == 1){?>
                                    <div class="intro">The account owner has access to all projects.</div>
                                    <?php } else if($member->uid != $project->uid){?>
									<div class="controls">
										<div class="invite">
											<a rel="nofollow" href="" data-remote="true" data-method="post" onclick="invite_resend(this,event,<?php print $project->nid;?>,<?php print $member->uid;?>)" data-activated-text="Sending invitation…" class="decorated">Send another invitation</a>
										</div>
									</div>   
									<?php } ?>
                                </div>
                            </article>
                            <?php } ?>
                            <?php } ?>
                            <?php foreach($clients as $member){?>
                            <article class="access" data-person-id="<?php print $member->uid;?>" id="access_<?php print $member->uid;?>">
                                <div class="wrapper">
                                    <a data-replace-stack="true" class="link_page" href="<?php print $base_url.'/user/'.$member->uid;?>" data-uid="<?=$member->uid?>">
                                    <img class="avatar" height="96" src="<?php print $base_url.'/sites/default/files/profile/'.$member->field_profile_picture['und'][0]['value'];?>"
                                    title="<?php print $member->fname;?>" width="96"></a>
                                    <?php if(is_email($member->fname)){?>
                                    <?php 	$secs = explode('@',$member->fname);?>
									<h1>
										<span class="mailbox"><?php print $secs[0];?></span><span class="at_symbol">@</span><span class="domain"><?php print $secs[1];?></span>
									</h1>
                                    <?php } else { ?>
									<h1>
										<a class="person_name link_page" href="<?php print $base_url.'/user/'.$member->uid;?>" data-uid="<?=$member->uid?>"><?php print $member->fname;?></a>
		                                <div class="email">
		                                    <a href="mailto:<?php print $member->mail;?>"><?php print $member->mail;?></a>
		                                </div>
		                            </h1>
                                    <?php } ?>
                                    <div class="last_invitee_event">Active <time data-time-ago="" datetime="2013-12-02T07:33:07Z">2 hours ago</time></div>
                                    <?php if($member->uid == 1){?>
                                    <div class="intro">The account owner has access to all projects.</div>
                                    <?php } else if($member->uid != $project->uid){?>
									<div class="controls">
										<div class="invite">
											<a rel="nofollow" href="" data-remote="true" data-method="post" onclick="invite_resend(this,event)" data-activated-text="Sending invitation…" class="decorated">Send another invitation</a>
										</div>
										<?php if(!$is_client){?>
										<form method="post" class="revoke" action="" accept-charset="UTF-8">
											<div style="margin:0;padding:0;display:inline">
												<input type="hidden" value="✓" name="utf8">
												<input type="hidden" value="delete" name="_method">
											</div>
											<div class="intro">
												<a onclick="start_remove(this,event)" class="decorated">Remove</a>
											</div>
											<div style="display:none" class="confirm">
												<span class="question">Remove The from this project?</span>
												<input type="button" value="Remove" name="commit" onclick="remove_client(this,<?php print $project->nid;?>,<?php print $member->uid;?>)"> or <a onclick="stop_remove(this,event)" class="decorated">Keep Them</a>
											</div>
											<div style="display:none" class="request">
												Removing <?php print $member->fname;?>…
											</div>
										</form>  
										<?php } ?> 
									</div>   
									<?php } ?>
                                </div>
                            </article>
                            <?php } ?>
                        </section>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<script>
    jQuery("document").ready(function(){
        load_page();
    });
	var client_tab = 0;
	var with_clients = <?php print $with_clients;?>;
	var options = { 
		beforeSubmit:  showRequest,
		success:       showResponse
	}; 
	var exists = false;
	function showRequest(formData, jqForm, options) { 
		var secs = options.url.split('action=');
		var action = secs[1];
		if(action == 'invite_members')
			jQuery("form#invite-members p.submit").addClass("busy");
		else if(action == 'invite_clients')
			jQuery("form#invite-clients p.submit").addClass("busy");
	}
	
	function showResponse(responseText, statusText, xhr, form) { 
		var result = jQuery.parseJSON(responseText);
		if(result.error == 1){
			alert(result.message);
		}
		else{
			if(result.people == 'team'){
				jQuery("#team_accesses article").remove();
				jQuery("#team_accesses").append(result.content);
				jQuery("form#invite-members p.submit").removeClass("busy");
				jQuery("#invite-members .person").each(function(){
					if(!jQuery(this).hasClass("blank"))
						jQuery(this).remove();
				});
				jQuery("#new_superpowers_prompt").css("display","none");
				jQuery("#new_superpowers").html("");
				jQuery(".team_accesses").css("overflow","hidden");
				var slider = jQuery(".team_accesses .slides").first();
				var slides = jQuery(".team_accesses .slides li");
				jQuery(slider).css("margin-left","0");
				jQuery(slides[1]).css("margin-left","0");
				jQuery(slides[1]).removeClass("active");
				jQuery(slides[0]).addClass("active");
				jQuery(".team_accesses").attr("style","");
			}
			else if(result.people == 'client'){
				jQuery("#client_accesses article").remove();
				jQuery("#client_accesses").append(result.content);
				if(result.content.length == 0){
					jQuery("#client_accesses").addClass("blank_slate");
				}
				else jQuery("#client_accesses").removeClass("blank_slate");
				jQuery("form#invite-clients p.submit").removeClass("busy");
				jQuery("#invite-clients .person").each(function(){
					if(!jQuery(this).hasClass("blank"))
						jQuery(this).remove();
				});
			}
		}
	}
	jQuery(document).ready(function(){
		data_behavior();
		jQuery("body").click(function(){
			jQuery(".suggestions").html("");
		});
		jQuery("form.invite").ajaxForm(options);
	});
	function toggle_accesses_list(self){
		var data_accesses_list = jQuery(self).attr("data-accesses-list");
		jQuery(self).addClass("selected");
		var parent = jQuery(self).parent();
		if(data_accesses_list == 'team'){
			client_tab = 0;
			var client = jQuery(parent).next().children("a").first();
			jQuery(client).removeClass("selected");
			if(!with_clients)
				jQuery(client).next().css("display","");
			jQuery(".split_accesses").each(function(){
				var list = jQuery(this).attr("data-accesses-list");
				if(list == 'team')
					jQuery(this).fadeIn("fast");
				else jQuery(this).fadeOut("fast");
			});
		}
		else if(data_accesses_list == 'client'){
			client_tab = 1;
			if(!with_clients)
				jQuery(self).next().css("display","none");
			jQuery(parent).prev().children("a").removeClass("selected");
			jQuery(".split_accesses").each(function(){
				var list = jQuery(this).attr("data-accesses-list");
				if(list == 'client'){
					jQuery(this).fadeIn("fast",function(){
						if(!with_clients)
							jQuery(this).children("section:first").children("figure:first").removeClass("initial_load");
					});
				}
				else jQuery(this).fadeOut("fast");
			});
		}
	}
	function confirm_opt_into_client_projects(self){
		jQuery(".client_projects_prompt").css("display","none");
		with_clients = 1;
		jQuery.post("<?php print $base_url;?>/ajax-process?action=update_node",{nid:<?php print $project->nid;?>,attr:"with_clients",value:1},function(){});
		jQuery(".columns").each(function(){
			var behavior = jQuery(this).attr("data-behavior");
			if(behavior == 'clients_list'){
				jQuery(this).removeClass("hidden");
				jQuery(this).find(".slide").addClass("active").css("width","360px");
			}
		});
		
	}
	function confirm_opt_out_of_client_projects(self){
		with_clients = 0;
		jQuery.post("<?php print $base_url;?>/ajax-process?action=update_node",{nid:<?php print $project->nid;?>,attr:"with_clients",value:0},function(){});
		jQuery(".columns").each(function(){
			var behavior = jQuery(this).attr("data-behavior");
			if(behavior == 'clients_list'){
				jQuery(this).addClass("hidden");
			}
		});
		jQuery(".client_projects_prompt").css("display","block");
		jQuery(".client_projects_prompt").children("figure:first").removeClass("initial_load");
	}
	function start_remove(self,event){
		var parent = jQuery(self).parent();
		jQuery(parent).css("display","none");
		jQuery(parent).next().css("display","");
	}
	function invite_resend(self,event,nid,uid){
		event.preventDefault();
		self.innerHTML = jQuery(self).attr("data-activated-text");
		jQuery(self).addClass("activated");
		jQuery.post("<?php print $base_url;?>/ajax-process?action=send_invitation",{nid:nid,uid:uid},function(){
			jQuery(self).css("opacity",0);
			jQuery(self).html("Send another invitation");
			jQuery(self).animate({opacity:1},500);
			jQuery(self).removeClass("activated");
		},'json');
	}
	function remove_team(self,nid,uid){
		var parent = jQuery(self).parent();
		jQuery(parent).css("display","none");
		jQuery(parent).next().css("display","block");
		jQuery.post("<?php print $base_url;?>/ajax-process?action=remove_member",{nid:nid,uid:uid},function(data){
			if(data.error == 1)
				alert(data.message);
			else jQuery(self).parents("article:first").fadeOut("fast",function(){
				jQuery(this).remove();
			});
		},'json');
	}
	function remove_client(self,nid,uid){
		var parent = jQuery(self).parent();
		jQuery(parent).css("display","none");
		jQuery(parent).next().css("display","block");
		jQuery.post("<?php print $base_url;?>/ajax-process?action=remove_member",{nid:nid,uid:uid},function(data){
			if(data.error == 1)
				alert(data.message);
			else jQuery(self).parents("article:first").fadeOut("fast",function(){
				jQuery(this).remove();
				var articles = jQuery("#client_accesses > article");
				if(articles.length == 0){
					jQuery("#client_accesses").addClass("blank_slate");
				}
			});
		},'json');
	}
	function stop_remove(self,event,nid,uid){
		event.preventDefault();
		var parent = jQuery(self).parent();
		jQuery(parent).css("display","none");
		jQuery(parent).prev().css("display","block");
	}
	function remove_invitee(event,me){
		event.preventDefault();
		if(client_tab == 0){
			var email = jQuery(me).parent().children("input[type=hidden]").attr("value");
			var id = "#invitee-" + email.replace("@","-").replace(".","-");
			jQuery(id).remove();
			var tables = jQuery("#new_superpowers").children("table");
			if(tables.length == 0){
				jQuery("#new_superpowers_prompt").css("display","none");
			}
		}
		jQuery(me).parents(".person").remove();
		add_more();
	}
	function slide(me){
		var direction = jQuery(me).attr("data-direction");
		jQuery(".team_accesses").css("overflow","hidden");
		var slider = jQuery(".team_accesses .slides").first();
		var slides = jQuery(".team_accesses .slides li");
		if(direction == 'forward'){
			jQuery(slides[1]).addClass("active");
			jQuery(slider).animate({"margin-left":"-360"},250,"swing",function(){
				jQuery(slides[0]).removeClass("active");
				jQuery(slides[1]).css("margin-left","360px");
				jQuery(".team_accesses").attr("style","");
			});
		}
		else if(direction == 'backward'){
			jQuery(slides[1]).css("margin-left","0");
			jQuery(slides[0]).addClass("active");
			jQuery(slider).animate({"margin-left":"0"},250,"swing",function(){
				jQuery(slides[1]).removeClass("active");
				jQuery(".team_accesses").attr("style","");
			});
		}
	}
	function admin_permissions(self){
		if(self.checked){
			var parent = jQuery(self).parent();
			var pre = jQuery(parent).prev();
			jQuery(pre).children("input[type=checkbox]").each(function(){
				this.checked = true;
				jQuery(this).attr("disabled","");
			});
		}
		else{
			var parent = jQuery(self).parent();
			var pre = jQuery(parent).prev();
			jQuery(pre).children("input[type=checkbox]").removeAttr("disabled");
		}
	}
	function select_suggest_company(self){
		var person = jQuery(self).parents(".person:first");
		var suggest = jQuery(self).children("div:first");
		jQuery(person).html(jQuery(suggest).html());
		var company = jQuery(suggest).attr("class");
		jQuery(person).attr("class",company);
		var invitees = jQuery(person).parents(".invitees:first");
		var companies = jQuery(invitees).children("."+company);
		for(var i = 0; i < companies.length - 1; i++)
			jQuery(companies[i]).fadeOut("slow",function(){ jQuery(this).remove()});
		add_more();
	}
	function select_suggest_group(self){
		var person = jQuery(self).parents(".person:first");
		var suggest = jQuery(self).children("div:first");
		jQuery(person).html(jQuery(suggest).html());
		var group = jQuery(suggest).attr("class");
		jQuery(person).attr("class",group);
		var invitees = jQuery(person).parents(".invitees:first");
		var groups = jQuery(invitees).children("."+group);
		for(var i = 0; i < groups.length - 1; i++)
			jQuery(groups[i]).fadeOut("slow",function(){ jQuery(this).remove()});
		add_more();
	}
	function select_suggest_user(self){
		var person = jQuery(self).parents(".person:first");	
		var li = self;
		jQuery(person).removeClass("focused");
		var email = jQuery(li).children(".email:first").html();
		jQuery(person).find(".input:first").html(jQuery(li).children("div:first").html());
		jQuery(person).children("div").append('<a onclick="remove_invitee(event,this)" class="remove"></a>');
		jQuery(person).find("input[type=hidden]").attr("value",email);
		var invitees = jQuery(person).parents(".invitees:first");
		var need_remove = new Array();
		var count = 0;
		jQuery(invitees).find(".person").each(function(){
			var person_email = jQuery(this).find("input[type=hidden]").first().attr("value");
			if(person_email == email)
				need_remove[count++] = this;
		});
		for(var i = 0; i < need_remove.length - 1; i++)
			jQuery(need_remove[i]).fadeOut("slow",function(){ jQuery(this).remove()});
		jQuery(person).removeClass("unknown");
		jQuery("ol.suggestions").html("");
		var invitees = jQuery(person).parents(".invitees:first");
		var need_remove = new Array();
		var count = 0;
		jQuery(invitees).find(".person").each(function(){
			var person_email = jQuery(this).find("input[type=hidden]").first().attr("value");
			if(person_email == email)
				need_remove[count++] = this;
		});
		for(var i = 0; i < need_remove.length - 1; i++)
			jQuery(need_remove[i]).fadeOut("slow",function(){ jQuery(this).remove()});
		add_more();
	}
	function suggest_person_mouseover(self){
		jQuery(self).addClass("selected");
	}
	function suggest_person_mouseleave(self){
		jQuery(self).removeClass("selected");
	}
	function press_key(self,event){
		var code = event.keyCode || event.which;
		if (code == 9) {
			var person = jQuery(self).parents(".person:first");	
			var li = jQuery(person).find(".suggestions li:first");
			var data_suggestion_id = jQuery(li).attr("data-suggestion-id");
			if(typeof data_suggestion_id != 'undefined' && data_suggestion_id.length > 0){
				if(data_suggestion_id.indexOf('person_') != -1){
					jQuery(person).removeClass("focused");
					var email = jQuery(li).children(".email:first").html();
					jQuery(person).find(".input:first").html(jQuery(li).children("div:first").html());
					jQuery(person).children("div").append('<a onclick="remove_invitee(event,this)" class="remove"></a>');
					jQuery(person).find("input[type=hidden]").attr("value",email);
					var invitees = jQuery(person).parents(".invitees:first");
					var need_remove = new Array();
					var count = 0;
					jQuery(invitees).find(".person").each(function(){
						var person_email = jQuery(this).find("input[type=hidden]").first().attr("value");
						if(person_email == email)
							need_remove[count++] = this;
					});
					for(var i = 0; i < need_remove.length - 1; i++)
						jQuery(need_remove[i]).fadeOut("slow",function(){ jQuery(this).remove()});
				}
				else if(data_suggestion_id.indexOf('group_') != -1){
					var suggest = jQuery(li).children("div:first");
					jQuery(person).html(jQuery(suggest).html());
					var group = jQuery(suggest).attr("class");
					jQuery(person).attr("class",group);
					var invitees = jQuery(person).parents(".invitees:first");
					var groups = jQuery(invitees).children("."+group);
					for(var i = 0; i < groups.length - 1; i++)
						jQuery(groups[i]).fadeOut("slow",function(){ jQuery(this).remove()});
				}
				else if(data_suggestion_id.indexOf('company_') != -1){
					var suggest = jQuery(li).children("div:first");
					jQuery(person).html(jQuery(suggest).html());
					var company = jQuery(suggest).attr("class");
					jQuery(person).attr("class",company);
					var invitees = jQuery(person).parents(".invitees:first");
					var companies = jQuery(invitees).children("."+company);
					for(var i = 0; i < companies.length - 1; i++)
						jQuery(companies[i]).fadeOut("slow",function(){ jQuery(this).remove()});
				}
			}
			jQuery("ol.suggestions").html("");
			add_more();
		}
	}
	function search_person(self,type){
		var person = jQuery(self).parents(".person:first");
		if(self.value.length > 0)
			jQuery(person).removeClass("blank");
		else jQuery(person).addClass("blank");
		if(self.value.length < 2)
			jQuery(person).find("ol.suggestions").html("");
		if(self.value.length > 1){
			jQuery.post("<?php print $base_url;?>/ajax-process",{action:"get_suggested_users",keyword:self.value,"project":<?php print $project->nid;?>,type:type},function(data){
				data = data.trim();
				var parent = jQuery(self).parent();
				var view = jQuery(parent).next();
				jQuery(view).html(data);
			});
		}
	}
	function focus_person(self){
		var person = jQuery(self).parents(".person:first");
		jQuery(person).removeClass("unknown");
		jQuery(person).addClass("focused");
		jQuery("ol.suggestions").html("");
		search_person(self);
	}
	function blur_person(self){
		jQuery(self).parents(".person:first").removeClass("focused");
	}
	function remove_group(self,event){
		event.preventDefault();
		jQuery(self).parents(".group:first").parent().remove();
		add_more();
	}
	function change_person(self){
		var person = jQuery(self).parents(".person:first");
		var containter = jQuery(person).find(".input:first");
		var inputs = jQuery(containter).children("input");
		if(inputs.length == 1 && is_email(self.value)){
			jQuery(containter).html("");
			jQuery(person).find("input[type=hidden]").attr("value",self.value);
			jQuery(containter).append('<input type="text" readonly="" spellcheck="false" value="'+self.value+'">');
			jQuery(containter).append('<input type="text" disabled="" style="background:white; opacity:0;">');
			jQuery(person).children("div").append('<a onclick="remove_invitee(event,this)" class="remove"></a>');
			var invitees = jQuery(person).parents(".invitees:first");
			var need_remove = new Array();
			var count = 0;
			jQuery(invitees).find(".person").each(function(){
				var person_email = jQuery(this).find("input[type=hidden]").first().attr("value");
				if(person_email == self.value)
					need_remove[count++] = this;
			});
			for(var i = 0; i < need_remove.length - 1; i++)
				jQuery(need_remove[i]).fadeOut("slow",function(){ jQuery(this).remove()});
			if(client_tab == 0){
				jQuery("#new_superpowers_prompt").css("display","block");
				id = "invitee-" + self.value.replace("@","-");
				id = id.replace(".","-");
				var table = '';
				table += '<table cellspacing="0" cellpadding="0" border="0" id="'+id+'">';
				table += '	<tbody>';
				table += '		<tr data-behavior="set_permission_checkboxes">';
				table += '			<td>';
				table += '			  <strong>'+self.value+'</strong>';
				table += '			</td>';
				table += '			<td class="option projects">';
				table += '			  <input type="hidden" value="0" name="permissions['+self.value+'][can_create_projects]">';
				table += '			  <input type="checkbox" value="1" name="permissions['+self.value+'][can_create_projects]">';
				table += '			</td>';
				table += '			<td class="option admin">';
				table += '			  <input type="hidden" value="0" name="permissions['+self.value+'][admin]">';
				table += '			  <input type="checkbox" value="1" name="permissions['+self.value+'][admin]" onclick="admin_permissions(this)">';
				table += '			</td>';
				table += '  		</tr>';
				table += '	</tbody>';
				table += '</table>';
				jQuery("#new_superpowers").append(table);
			}
		}
		else{
			jQuery(person).addClass("unknown");
		}
		add_more();
	}
	function add_more(){
		var type = 'team';
		var i_name = 'invitees';
		if(client_tab == 1){
			type = 'client';
			i_name = 'client_invitees';
		}
		var person = '';
		person += '<div class="person invitee field blank">';
		person += '	<div class="autocomplete_people">';
		person += '        <input data-role="email_address_input" name="'+i_name+'[][email_address]" type="hidden" value="">';
		person += '        <div class="icon"></div>';
		person += '        <div class="input">';
		person += '            <input data-behavior="input_change_emitter" data-role="human_input" spellcheck="false" type="text" value="" onkeydown="press_key(this,event)" onkeyup="search_person(this,\''+type+'\')" onchange="change_person(this)" onfocus="focus_person(this)" onblur="blur_person(this)">';
		person += '        </div>';
		person += '        <div class="suggestions" data-role="suggestions_view">';
		person += '        </div>';
		person += '    </div>';
		person += '</div>';	
		if(client_tab == 0){
			jQuery(".team_accesses .invitees").each(function(){
				var blanks = jQuery(this).children(".blank");
				if(blanks.length < 2)
					jQuery(this).append(person);
			});
		}
		else{
			jQuery(".client_accesses .invitees").each(function(){
				var blanks = jQuery(this).children(".blank");
				if(blanks.length < 2)
					jQuery(this).append(person);
			});
		}
	}
</script>

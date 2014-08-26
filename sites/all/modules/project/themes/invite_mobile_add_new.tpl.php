<?php global $base_url, $user; ?>
<div class="panel sheet">
    <header class="sheet_header hide_from_ios hide_from_android">
        <h1>Invite more people</h1>
        <h2>To the project: <?= $project->title ?></h2>
    </header>
    <article class="access access_form <?= ($project->field_with_clients['und'][0]['value'] == 1) ? 'client_access_on' : '' ?>">
            <form accept-charset="UTF-8" action="<?=$base_url?>/ajax-process?action=invite_members_client" data-behavior="invite_form" data-disabled-group-ids="" data-disabled-people-ids="[<?= implode(",", $uids) ?>]" data-remove-sheet="true" method="post" class="">
            <input type="hidden" name="nid" value="<?=$project->nid?>">
            <input type="hidden" name="with_clients" id="project_is_client_project" value="<?=$project->field_with_clients['und'][0]['value']?>">
            <div class="access_form" data-behavior="team_invitees">
                <h2 class="hide_from_ios">Invite people to your team</h2>
                <h3 class="for_ios" style="margin-bottom: 0;">Invite people to your team</h3>
                <p>
                    <label for="invitee">Anyone on your team will see every message, to-do list, file, event, or text document posted to this project.</label>
                    <!--[if IE]>
                      <label for="invitee">Add team members by name or email:</label>
                    <![endif]-->
                    <input id="team_invitee" autocomplete="off" autofocus="autofocus" data-behavior="invitee_input" data-placeholder-text="Add team members by name or email..." id="invitee" name="invitee" type="email" placeholder="Add team members by name or email..." onkeydown="press_key(this,event)" onkeyup="search_person(this,'team')" onchange="change_person(this)" onfocus="focus_person(this)" onblur="blur_person(this)">
                </p><ul class="suggestions" data-behavior="invitee_suggestions"></ul>
                <p></p>
                <ul class="pending_invitees teams" data-behavior="invitees_list"> </ul>
                <p>
                    <!--[if IE]>
                      <label for="message">Message (optional)</label>
                    <![endif]-->
                    <textarea data-behavior="autosave autoresize" data-line-height="18" id="message" name="message" placeholder="Optional message for your team..." rows="5" style="overflow: hidden;"></textarea>
                </p>
            </div>
            
            <div class="access_form" data-role="client_access_off">
                <h2 class="hide_from_ios">Working with clients on this project?</h2>
                <h3 class="for_ios" style="margin-bottom: 0;">Working with clients on this project?</h3>
                <p>
                    <label for="client_access">Freebasecamp lets you hide certain posts from people you invite as clients. You can turn this on now to start choosing what clients can see, even if you're not ready to invite any clients yet.</label>
                    <a data-behavior="toggle_client_access_on" onclick="set_client_project_flag(this)">Yes, turn on client access</a>
                </p>
            </div>
            <div class="access_form" data-behavior="client_invitees" data-role="client_access_on">

                <h2 class="hide_from_ios">Invite clients</h2>
                <h3 class="for_ios" style="margin-bottom: 0;">Invite clients</h3>
                <p>
                    <label for="invitee">Working with a client on this project? Freebasecamp lets you hide certain posts from people you invite here.</label>
                    <!--[if IE]>
                      <label for="invitee">Add clients by name or email:</label>
                    <![endif]-->
                    <input id="client_invitee" autocomplete="off" autofocus="autofocus" data-behavior="invitee_input" data-placeholder-text="Add clients by name or email..." id="invitee" name="invitee" type="email" placeholder="Add clients by name or email..." onkeydown="press_key(this,event)" onkeyup="search_person(this,'client')" onchange="change_person(this)" onfocus="focus_person(this)" onblur="blur_person(this)">

                </p><ul class="suggestions" data-behavior="invitee_suggestions">
                </ul>
                <p></p>
                <ul class="pending_invitees clients" data-behavior="invitees_list"></ul>
                <p>
                    <!--[if IE]>
                      <label for="client_message">Message (optional)</label>
                    <![endif]-->
                    <textarea data-behavior="autosave autoresize" data-line-height="18" id="client_message" name="client_message" placeholder="Optional message for the client..." rows="5" style="overflow: hidden;"></textarea>
                </p>
            </div>
            <footer>
                <div class="submit">
                    <input class="action_button default_action" data-behavior="invitation_submit selectable" name="commit" type="submit" value="Invite these people" disabled="">
                </div>
            </footer>
        </form>  
    </article>
</div>
<script>
 
        function close_flash(me){
            jQuery(me).parent().remove();
        }
        function press_key(self,event){
        var code = event.keyCode || event.which;
        /*mobile*/
        if (code == 9 || code==13) {
            var mail = self.value;
            if(is_email(mail)){
                jQuery(self).removeClass("error");
                var person = jQuery(self).parents(".access_form:first");	
                var li = jQuery(person).find(".suggestions li:first");
                var tag_a = jQuery(li).children("a");
                var data_suggestion_id = jQuery(tag_a).attr("data-suggestion-id");
                if(typeof data_suggestion_id != 'undefined' && data_suggestion_id.length > 0){
                    append_person('exist',tag_a);
                } else {
                    append_person('not_exist',self);
                }
                enable_disibled_submit();
            } else {
                jQuery(self).addClass("error");
            }
            if(code==13){
                event.stopPropagation();
                event.preventDefault();
            }
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
    function search_person(self,type){
        var person = jQuery(self).parents(".person:first");
        if(self.value.length > 0)
            jQuery(person).removeClass("blank");
        else jQuery(person).addClass("blank");
        if(self.value.length < 2)
            jQuery(person).find("ol.suggestions").html("");
        if(self.value.length > 1){
            jQuery.post("<?php print $base_url; ?>/ajax-process",{action:"get_suggested_users",keyword:self.value,type:type},function(data){
                data = data.trim();
                var parent = jQuery(self).parent();
                var view = jQuery(parent).next();
                jQuery(view).html(data);
            });
        }
    }
    function select_suggest_user(self){
    var loop_in = jQuery(self).parent().parent();
        append_person('exist',self);
      jQuery(loop_in).html("");
      enable_disibled_submit()
    }
    function enable_disibled_submit(){
        var teams = jQuery(".pending_invitees.teams").children().length;
      var clients = jQuery(".pending_invitees.clients").children().length;
	var both =teams+clients;
        if(both>0){
            jQuery(".submit .action_button").removeAttr("disabled");
        }else {
            jQuery(".submit .action_button").attr("disabled","disabled");
        }
    }
    function suggest_person_mouseover(self){
        jQuery(self).addClass("selected");
    }
    function suggest_person_mouseleave(self){
        jQuery(self).removeClass("selected");
    }
    function change_person(self){
    }
    function append_person(flag,self){
        var string ='';
        var tag_a = self;
        var li = jQuery(tag_a).parent();
        var parent = jQuery(tag_a).closest(".access_form");
        var i_name ='invitees';
        var invited_client = jQuery(parent).data("behavior");
        if(invited_client=='client_invitees')
            i_name ='client_invitees';
        var invitees = jQuery(parent).children(".pending_invitees");
        if(flag=='exist'){
            var email = jQuery(li).find(".email").val();
            var name = jQuery(tag_a).children("span.name").html();
            var data_person_id = jQuery(tag_a).data("person-id");
            string+='<li class="invitee" data-behavior="invitee_person" data-person-id="'+data_person_id+'">';
            string+='<span class="icon"></span>';
            string+='<span class="name">'+name+'</span>';
            string+='<a class="remove" onclick="remove_invitee(event,this)"><span>Remove</span></a>';
            string+='<input type="hidden" name="'+i_name+'[][email_address]" value="'+email+'">';
            string+='</li>';
            jQuery(invitees).prepend(string);
            jQuery(tag_a).remove();
        } else {
            var email = jQuery(self).val();
            string+='<li class="invitee" data-behavior="invitee_person">';
            string+='<span class="icon"></span>';
            string+='<span class="name">'+email+'</span>';
            string+='<a class="remove" onclick="remove_invitee(event,this)"><span>Remove</span></a>';
            string+='<input type="hidden" name="'+i_name+'[][email_address]" value="'+email+'">';
            string+='</li>';
            jQuery(invitees).prepend(string);
        }
        jQuery("#team_invitee").val(" ");
        jQuery("#client_invitee").val(" ");
        
    }
    function remove_invitee(event,me){
        jQuery(me).closest(".invitee").remove();
         var teams = jQuery(".pending_invitees.teams").children().length;
      var clients = jQuery(".pending_invitees.clients").children().length;
	var both =teams+clients;
        if(both==0){
            jQuery(".submit .action_button").attr("disabled","disabled");
        }
    }
    function set_client_project_flag(me){
        jQuery("article.access_form").addClass("client_access_on");
        jQuery("#project_is_client_project").attr("value",1);
    }
    </script>
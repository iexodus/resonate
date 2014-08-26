<?php global $base_url, $user; ?>
<header class="global_heading expanded_content">
    <h1 style="margin-bottom: 3px;">Start a project on Freebasecamp.</h1>
</header>
<header class="global_heading collapsed_content">
    <h1>What kind of project?</h1>
</header>
<section class="templates collapsed_content">
    <article class="template">
        <label onclick="moblie_project(this,'choose_project','blank')">
            <input type="radio" name="project_type">
            <h1>Blank project</h1>
            <h2>Start from scratch</h2>
        </label>
    </article>
    <article class="template" data-url="/2586036/project_templates/5429199-template-1/materialize">
        <label onclick="moblie_project(this,'choose_project','template')">
            <input type="radio" name="project_type">
            <h1>Template 1</h1>
            <h2></h2>
            <p class="meta">no discussions, 2 to-dos, no files, no documents, no emails, no events, 2 people.</p>
        </label>
    </article>
    <footer>
        <button data-behavior="expand_on_click selectable project_details_continue" class="action_button" onclick="moblie_project(this,'project_continue','expand')">Continue to project details...</button>
        <button data-behavior="create_draft_project" class="action_button default_action" onclick="moblie_project(this,'project_continue','start')">Start the project</button>
    </footer>
</section>
<article class="project form expanded_content" data-behavior="project_form">
    <form accept-charset="UTF-8" action="<?php print $base_url; ?>/ajax-process?action=add_project" class="new_project" data-replace-sheet="true" id="new_project" method="post">
        <input data-behavior="is_client_project" id="project_is_client_project" name="project[is_client_project]"type="hidden" value="false"> 
        <input id="project_template_id" name="project_template_id" type="hidden" value="">
        <h2>Project name and description</h2>
        <p>
            <input id="project_name" name="project[name]" placeholder="Name the project" type="text">
        </p>

        <p>
            <textarea class="body" id="project_description" name="project[description]" placeholder="Add a description or extra details (optional)" rows="3"></textarea>
        </p>

        <input data-behavior="is_client_project" id="project_is_client_project" name="project[is_client_project]" type="hidden" value="false">
        <div class="access_form" data-behavior="team_invitees">
            <h2>Invite people to your team</h2>
            <p>
                <label for="invitee">Anyone on your team will see every message, to-do list, file, event, or text document posted to this project.</label>
                <!--[if IE]>
                  <label for="invitee">Add team members by name or email:</label>
                <![endif]-->
                <input id="team_invitee" data-behavior="invitee_input" data-placeholder-text="Add team members by name or email..." id="team_invitee" name="team_invitee" type="email" placeholder="Add team members by name or email..." onkeydown="press_key(this,event)" onkeyup="search_person(this,'team')" onchange="change_person(this)" onfocus="focus_person(this)" onblur="blur_person(this)">

            </p>
            <ul class="suggestions" data-behavior="invitee_suggestions">
            </ul>
            <p></p>
            <ul class="pending_invitees" data-behavior="invitees_list">
            </ul>
        </div>

        <div class="access_form" data-role="client_access_off">
            <h2>Working with clients on this project?</h2>
            <p>
                <label for="client_access">Freebasecamp lets you hide certain posts from people you invite as clients. You can turn this on now to start choosing what clients can see, even if you're not ready to invite any clients yet.</label>
                <a class="button action_button" onclick="set_client_project_flag(this)">Yes, turn on client access</a>
            </p>
        </div>

        <div class="access_form" data-behavior="client_invitees" data-role="client_access_on">
            <h2>Client access is on for this project</h2>
            <p>
                <label for="invitee">You can now control what clients can and cannot see on this project. Clients will only see the messages, to-do lists, files, events, and text documents you want them to see.</label>
                <!--[if IE]>
                  <label for="invitee">Add clients by name or email:</label>
                <![endif]-->
                <input id="client_invitee" autocomplete="off" data-behavior="invitee_input" data-placeholder-text="Add clients by name or email..." id="client_invitee" name="client_invitee" type="email" placeholder="Add clients by name or email..." onkeydown="press_key(this,event)" onkeyup="search_person(this,'client')" onchange="change_person(this)" onfocus="focus_person(this)" onblur="blur_person(this)">
            </p><ul class="suggestions" data-behavior="invitee_suggestions">
            </ul>
            <p></p>

            <ul class="pending_invitees" data-behavior="invitees_list">
            </ul>
        </div>


        <footer>
            <p class="submit">
                <input class="action_button default_action" data-behavior="invitation_submit" name="commit" type="submit" value="Start the project">
            </p>
        </footer>
    </form>   
</article>
<script>
    var options = { 
        beforeSubmit:  showRequest,
        success:       showResponse
    }; 
    jQuery(document).ready(function(){
        jQuery("form#new_project").ajaxForm(options); 
    });
    function showRequest(formData, jqForm, options) { 
        jQuery("#new_project").addClass("busy");
    }
	
    function showResponse(responseText, statusText, xhr, form) { 
        var result = jQuery.parseJSON(responseText);
        if(result.error == 1){
            jQuery("#new_project").removeClass("busy");
        }
        else{
            jQuery("#new_project_dialog").remove();
            jQuery("header").animate({opacity:1},500,"swing");
            window.history.replaceState('Object', 'Title', result.url);
            jQuery(".panel").replaceWith(result.content);
            jQuery(".panel").animate({opacity:1},500);
        }
    }
    function moblie_project(me,action,flag){
        switch(action){
            case 'choose_project':
                if(jQuery(me).children("input").is(":checked")){
                    if(flag=='blank'){
                        jQuery(me).closest(".templates").removeClass("start_from_template").addClass("start_from_blank");
                    }else {
                        jQuery(me).closest(".templates").removeClass("start_from_blank").addClass("start_from_template");
                    }
                }
                break;
            case 'project_continue':
                if(flag=='expand'){
                    jQuery(me).addClass("selected");
                    jQuery(".templates").removeAttr("data-behavior");
                    jQuery(".panel").addClass("expanded");
                } else {
              
                }
                break;
        }
    }
    function press_key(self,event){
        var code = event.keyCode || event.which;
        /*mobile*/
        if (code == 9 || code==13) {
            var person = jQuery(self).parents(".access_form:first");	
            var li = jQuery(person).find(".suggestions li:first");
            var tag_a = jQuery(li).children("a");
            var data_suggestion_id = jQuery(tag_a).attr("data-suggestion-id");
            if(typeof data_suggestion_id != 'undefined' && data_suggestion_id.length > 0){
                append_person('exist',tag_a);
            } else {
                append_person('not_exist',self);
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
    }
    function set_client_project_flag(me){
        client_tab=1;
        jQuery(".access_form[data-role=client_access_off]").css("display","none");
        jQuery(".access_form[data-role=client_access_on]").css("display","block");
        jQuery(".accesses[data-accesses-list=client]").addClass("client_access_on");
        jQuery("#project_is_client_project").attr("value",true);
        jQuery("#new_project").append('<input type="hidden" name="with_clients" value="1">');
    }
</script>

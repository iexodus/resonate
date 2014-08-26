<?php global $base_url, $user; ?>
<div data-role="account_upgrade_wrapper">
    <form class="project_template" style="visibility: visible;">
        <header>
            <a data-behavior="cancel_new_project" data-role="cancel">cancel</a>
            <h1>What kind of project would you like to start?</h1>
        </header>
        <section class="project_templates" style="opacity: 1;">
            <article class="card blank_project">
                <div>
                    <a data-behavior="start_blank_project" data-replace-sheet="true" href="#" title="Blank Project">
                        <header></header>
                        <h5>Blank Project</h5></a>
                </div>
            </article>
            <?php foreach ($templates as $template) { ?>
                <article class="card">
                    <div>
                        <a data-behavior="create_draft_project" href="#<?php print $template->nid; ?>" title="<?php print $template->title; ?>">
                            <header></header>
                            <h5><?php print $template->title; ?></h5>
                            <p></p>
                            <p class="meta">1 discussion, no to-dos, no files, no
                                documents, no emails, no dates</p>
                            <div class="people"><img class="avatar" height="40"
                                                     src="https://asset1.basecamp.com/2457101/people/6217295-mt-tom/photo/avatar.40.gif"
                                                     title="Mt Tom" width="40"></div></a>
                    </div>
                </article>
            <?php } ?>
        </section>
    </form>
    <form accept-charset="UTF-8" action="<?php print $base_url; ?>/ajax-process?action=add_project" class="new_project" data-behavior="create_project invite" data-remote="true" id="new_project" method="post" name="new_project" style="visibility: visible; display: none;">
        <div style="margin:0;padding:0;display:inline">
            <input name="utf8" type="hidden" value="✓">
        </div>
        <div class="project">
            <section class="details">
                <div class="name">
                    <h1><div style="position: absolute; left: -9999px; top: 0px; word-wrap: break-word; font-size: 24px; font-family: &quot;ProximaNova&quot;,&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif; font-weight: 700; letter-spacing: normal; line-height: 30px; text-decoration: none; text-rendering: optimizelegibility; width: 640px;" class="formatted_content hidden">Name the project</div><textarea rows="1" placeholder="Name the project" name="project[name]" id="project_name" data-behavior="autoresize submit_on_enter" data-autoresize="true" style="resize: none; overflow: hidden; min-height: 31px;">Name the project</textarea></h1>
                </div>
                <div class="description">
                    <div style="position: absolute; left: -9999px; top: 0px; word-wrap: break-word; font-size: 16px; font-family: &quot;ProximaNova&quot;,&quot;Helvetica Neue&quot;,helvetica,arial,sans-serif; font-weight: 400; letter-spacing: normal; line-height: 20px; text-decoration: none; text-rendering: optimizelegibility; width: 640px;" class="formatted_content hidden"></div><textarea rows="1" placeholder="Add a description or extra details (optional)" name="project[description]" id="project_description" data-behavior="autoresize submit_on_enter" data-autoresize="true" style="resize: none; overflow: hidden; min-height: 21px;"></textarea>
                    <p></p>
                </div>

                <input data-behavior="is_client_project" id= "project_is_client_project" name="project[is_client_project]"  type="hidden" value="false"> 
                <input id="project_template_id" name="project_template_id" type="hidden" value="">
                <!--<input type="file" name="image_project" accept="image/x-png, image/gif, image/jpeg" />-->
                Image:  <input type="file" name="image_project" id="image_project" />
                Category :
                <select name="category">
                    <option value="">Select category</option>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?= $category->tid ?>"><?= $category->name ?></option>    
                    <?php } ?>

                </select>
                <br>
                Group: <select name="group">
                    <option value="">Select Group</option>
                    <?php foreach ($groups as $group) { ?>
                        <option value="<?= $group->nid ?>"><?= $group->title ?></option>    
                    <?php } ?>

                </select>
                <div class="tags">
                    <div class="customleft">   Tags :</div>
                    <div class="tags_and_comments customleft">
                        <div class="taggings editing" id="select_category">
                            <ul id="labels-project" class="labels tags">

                                <div class="clear"></div>
                            </ul>
                        </div>
                    </div>
                    <div class="controls customleft" role="application"> 
                        <input type="text" class="form-text form-autocomplete" maxlength="128" size="60" value="" name="tid" id="edit-tid" onkeyup="tags_autocomplete(this)" style="position: relative">
                        <input type="hidden" id="tid_exists" value="">
                        <div id="list_result_completed">
                            <ul>

                            </ul>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </section>

            <div class="accesses_tabs">
                <!-- <h4>Type emails to invite people &ndash; you can do this later, too.</h4> -->

                <ul>
                    <li>
                        <a class="selected" data-accesses-list="team" data-behavior="toggle_accesses_list" href="#team">Our Team</a>
                    </li>
                    <li>
                        <a data-accesses-list="client" data-behavior="toggle_accesses_list" href="#client">Mentor</a>
                        <figure>
                            Working with a client? Invite them here. Clients
                            can’t see posts unless you say so.
                        </figure>
                    </li>
                </ul>
            </div>

            <section class="spliter_accesses accesses" data-accesses-list="team" data-behavior="accesses_list" style="display: block;">
                <article class="team_accesses">
                    <header>
                        <h3>Invite people to your team</h3>
                        <p>Anyone on your team will see everything posted to
                            this project. Every message, to-do list, file, event,
                            and text document.</p>
                    </header>
                    <div class="slider accesses" data-behavior="slider" style="">
                        <ul class="slides" style="width: 200%;">
                            <li class="slide active" data-slide-index="0"
                                style="width: 640px;">
                                <section class="invite">
                                    <div class="invitees" data-behavior="invitees" data-is-client="false">
                                        <div class="person invitee field blank">
                                            <div class="autocomplete_people">
                                                <input data-role="email_address_input" name="invitees[][email_address]" type="hidden" value="">
                                                <div class="icon"></div>
                                                <div class="input">
                                                    <input data-behavior="input_change_emitter" data-role="human_input" spellcheck="false" type="text" value="" onkeydown="press_key(this, event)" onkeyup="search_person(this, 'team')" onchange="change_person(this)" onfocus="focus_person(this)" onblur="blur_person(this)">
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
                                                    <input data-behavior="input_change_emitter" data-role="human_input" spellcheck="false" type="text" value="" onkeydown="press_key(this, event)" onkeyup="search_person(this, 'team')" onchange="change_person(this)" onfocus="focus_person(this)" onblur="blur_person(this)">
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
                                                    <input data-behavior="input_change_emitter" data-role="human_input" spellcheck="false" type="text" value="" onkeydown="press_key(this, event)" onkeyup="search_person(this, 'team')" onchange="change_person(this)" onfocus="focus_person(this)" onblur="blur_person(this)">
                                                </div>
                                                <div class="suggestions" data-role="suggestions_view">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p id="new_superpowers_prompt">Looks like
                                        you're inviting new people to Open Atrium!<br>
                                        <a class="decorated" data-behavior="slide"
                                           data-direction="forward" href="#">Decide
                                            who can create projects...</a></p>
                                </section>
                            </li>
                            <li class="slide" data-slide-index="1" style="width: 640px;">
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
                                <p class="glossary">Admins can create projects,
                                    remove anyone from a project, remove users from
                                    your Open Atrium account, and grant admin powers
                                    to other people. We recommend only granting
                                    admin powers to people you really trust.</p>

                                <p><a class="decorated done_editing"
                                      data-behavior="slide" data-direction="backward"
                                      href="#">← Back to the list of people</a></p>
                            </li>
                        </ul>
                    </div>

                    <div class="welcome_message" data-behavior="expandable">
                        <p><label data-behavior="expand_on_check"><input type=
                                                                         "checkbox" value="1" name="include_team_message"> Include a welcome message for your
                                team</label></p>

                        <div class="expanded_content">
                            <textarea name="message">
Hi there. We’ll be using Open Atrium to share ideas, gather feedback, and track progress during this project.
                            </textarea>
                        </div>
                    </div>
                </article>
            </section>

            <section class="spliter_accesses accesses" data-accesses-list="client"
                     data-behavior="accesses_list" style="display: none;">
                <figure class="initial_load">
                    <figcaption>
                        You’ll see a checkbox like this whenever you post
                        something to a project with clients.
                    </figcaption>
                </figure>

                <article class="client_accesses" data-role="client_access_off">
                    <header>
                        <h3>Working with a client on this project?</h3>

                        <p>Open Atrium lets you hide certain messages, to-dos,
                            files, events, and text documents from clients. This is
                            great for sharing unfinished work with your team before
                            getting client feedback.</p>

                        <p>You can turn this on now to start choosing what
                            clients can see, even if you’re not ready to invite any
                            clients yet.</p>

                        <p class="submit"><a class="button action_button"
                                             data-behavior="toggle_client_access_on" href="#">Yes,
                                turn on client access for this project</a></p>
                    </header>
                </article>

                <article class="client_accesses" data-role="client_access_on">
                    <header>
                        <h3>Client access is on for this project</h3>

                        <p>You can now control what clients can and cannot see
                            on this project. Invite clients by entering their names
                            or email addresses below. Don’t worry, you can always
                            do this later.</p>
                    </header>
                    <div class="accesses">
                        <section class="invite">
                            <div class="invitees" data-behavior="invitees" data-is-client="true">
                                <div class="person invitee field blank">
                                    <div class="autocomplete_people">
                                        <input data-role="email_address_input" name="client_invitees[][email_address]" type="hidden" value="">
                                        <div class="icon"></div>
                                        <div class="input">
                                            <input data-behavior="input_change_emitter" data-role="human_input" spellcheck="false" type="text" value="" onkeydown="press_key(this, event)" onkeyup="search_person(this, 'client')" onchange="change_person(this)" onfocus="focus_person(this)" onblur="blur_person(this)">
                                        </div>
                                        <div class="suggestions" data-role="suggestions_view"></div>
                                    </div>
                                </div>
                                <div class="person invitee field blank">
                                    <div class="autocomplete_people">
                                        <input data-role="email_address_input" name="client_invitees[][email_address]" type="hidden" value="">
                                        <div class="icon"></div>
                                        <div class="input">
                                            <input data-behavior="input_change_emitter" data-role="human_input" spellcheck="false" type="text" value="" onkeydown="press_key(this, event)" onkeyup="search_person(this, 'client')" onchange="change_person(this)" onfocus="focus_person(this)" onblur="blur_person(this)">
                                        </div>
                                        <div class="suggestions" data-role="suggestions_view"></div>
                                    </div>
                                </div>
                                <div class="person invitee field blank">
                                    <div class="autocomplete_people">
                                        <input data-role="email_address_input" name="client_invitees[][email_address]" type="hidden" value="">
                                        <div class="icon"></div>
                                        <div class="input">
                                            <input data-behavior="input_change_emitter" data-role="human_input" spellcheck="false" type="text" value="" onkeydown="press_key(this, event)" onkeyup="search_person(this, 'client')" onchange="change_person(this)" onfocus="focus_person(this)" onblur="blur_person(this)">
                                        </div>
                                        <div class="suggestions" data-role="suggestions_view"></div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="welcome_message" data-behavior="expandable">
                        <p><label data-behavior="expand_on_check"><input type=
                                                                         "checkbox" value="1" name="include_client_message"> Include a welcome message for your
                                client</label></p>

                        <div class="expanded_content">
                            <textarea name="client_message">
Hi there. We’ll be using Open Atrium to share ideas, gather feedback, and track progress during this project. Simply log in or create an account and you’ll be up and running in no time.
                            </textarea>
                        </div>
                    </div>
                </article>
            </section>

            <div class="submit">
                <input class="button action_button green big" name="commit"
                       type="submit" value="Start the project"> or <a data-behavior=
                       "cancel_new_project" data-role="cancel">Cancel</a>
            </div>
        </div>
    </form>
</div>
<script>
    var client_tab = 0;
    var with_clients = 0;
    var options = {
        beforeSubmit: showRequest,
        success: showResponse
    };

    function showRequest(formData, jqForm, options) {
        jQuery("#new_project").addClass("busy");
    }

    function showResponse(responseText, statusText, xhr, form) {
        var result = jQuery.parseJSON(responseText);
        if (result.error == 1) {
            jQuery("#new_project").removeClass("busy");
        }
        else {
            window.location.href=result.url;
            //            jQuery("#new_project_dialog").remove();
            //            jQuery("header").animate({opacity: 1}, 500, "swing");
            //            window.history.replaceState('Object', 'Title', result.url);
            //            jQuery("#block-system-main").html(result.content);
            //            jQuery(".container").animate({opacity: 1}, 500);
        }
    }
    function create_draft_project(me) {
        var secs = me.href.split('#');
        var project_template_id = secs[1];
        jQuery(".project_templates > .card").addClass("inactive");
        jQuery(".project_templates > .card a").addClass("inactive");
        jQuery(me).parents(".card").addClass("loading");
        jQuery.post("<?php print $base_url; ?>/ajax-process", {action: "add_project", project_template_id: project_template_id}, function(data) {
            if (data.error == 1) {
                jQuery(".project_templates > .card").removeClass("inactive");
                jQuery(".project_templates > .card a").removeClass("inactive");
                jQuery(me).parents(".card").removeClass("loading");
            }
            else {
                jQuery("#new_project_dialog").remove();
                jQuery("header").animate({opacity: 1}, 500, "swing");
                window.history.replaceState('Object', 'Title', data.url);
                jQuery("#block-system-main").html(data.content);
                jQuery(".container").animate({opacity: 1}, 500);
            }
        }, "json");
    }
    function toggle_accesses_list(self) {
        var data_accesses_list = jQuery(self).attr("data-accesses-list");
        jQuery(self).addClass("selected");
        var parent = jQuery(self).parent();
        if (data_accesses_list == 'team') {
            client_tab = 0;
            var client = jQuery(parent).next().children("a").first();
            jQuery(client).removeClass("selected");
            if (!with_clients)
                jQuery(client).next().css("display", "");
            jQuery(".spliter_accesses").each(function() {
                var list = jQuery(this).attr("data-accesses-list");
                if (list == 'team')
                    jQuery(this).fadeIn("fast");
                else
                    jQuery(this).fadeOut("fast");
            });
        }
        else if (data_accesses_list == 'client') {
            client_tab = 1;
            if (!with_clients)
                jQuery(self).next().css("display", "none");
            jQuery(parent).prev().children("a").removeClass("selected");
            jQuery(".spliter_accesses").each(function() {
                var list = jQuery(this).attr("data-accesses-list");
                if (list == 'client') {
                    jQuery(this).fadeIn("fast", function() {
                        if (!with_clients)
                            jQuery(this).children("section:first").children("figure:first").removeClass("initial_load");
                    });
                }
                else
                    jQuery(this).fadeOut("fast");
            });
        }
    }
    function toggle_client_access_on(me) {
        jQuery(".client_accesses[data-role=client_access_off]").css("display", "none");
        jQuery(".client_accesses[data-role=client_access_on]").css("display", "block");
        jQuery(".accesses[data-accesses-list=client]").addClass("client_access_on");
        jQuery("#project_is_client_project").attr("value", true);
        jQuery("#new_project").append('<input type="hidden" name="with_clients" value="1">');
    }
    function remove_invitee(event, me) {
        event.preventDefault();
        var article = jQuery(me).parents("article").first();
        var cls = jQuery(article).attr("class");
        if (cls == "team_accesses") {
            var email = jQuery(me).parent().children("input[type=hidden]").attr("value");
            var id = "#invitee-" + email.replace("@", "-").replace(".", "-");

            jQuery(id).remove();
        }
        jQuery(me).parents(".person").remove();
        var as = jQuery(".team_accesses .person a.remove");
        if (as.length == 0) {
            jQuery("#new_superpowers_prompt").css("display", "none");
        }
    }
    function slide(me) {
        var direction = jQuery(me).attr("data-direction");
        var slider = jQuery(".team_accesses .slides").first();
        var slides = jQuery(".team_accesses .slides li");
        if (direction == 'forward') {
            jQuery(slides[1]).addClass("active");
            jQuery(slider).animate({"margin-left": "-640"}, 500, "swing", function() {
                jQuery(slides[0]).removeClass("active");
                jQuery(slides[1]).css("margin-left", "640px");
            });
        }
        else if (direction == 'backward') {
            jQuery(slides[1]).css("margin-left", "0");
            jQuery(slides[0]).addClass("active");
            jQuery(slider).animate({"margin-left": "0"}, 500, "swing", function() {
                jQuery(slides[1]).removeClass("active");
            });
        }
    }
    function admin_permissions(self) {
        if (self.checked) {
            var parent = jQuery(self).parent();
            var pre = jQuery(parent).prev();
            jQuery(pre).children("input[type=checkbox]").each(function() {
                this.checked = true;
                jQuery(this).attr("disabled", "");
            });
        }
        else {
            var parent = jQuery(self).parent();
            var pre = jQuery(parent).prev();
            jQuery(pre).children("input[type=checkbox]").removeAttr("disabled");
        }
    }
    jQuery(document).ready(function() {
        jQuery("form#new_project").ajaxForm(options);
        data_behavior();
        jQuery(".welcome_message input").bind("click", function(data) {
            if (this.checked) {
                var parent = jQuery(this).parent().parent();
                var next = jQuery(parent).next();
                jQuery(next).slideDown("slow");
            }
            else {
                var parent = jQuery(this).parent().parent();
                var next = jQuery(parent).next();
                jQuery(next).slideUp("slow");
            }
        });
        jQuery("body").click(function() {
            jQuery(".suggestions").html("");
        });
    });
    function select_suggest_company(self) {
        var person = jQuery(self).parents(".person:first");
        var suggest = jQuery(self).children("div:first");
        jQuery(person).html(jQuery(suggest).html());
        var company = jQuery(suggest).attr("class");
        jQuery(person).attr("class", company);
        var invitees = jQuery(person).parents(".invitees:first");
        var companies = jQuery(invitees).children("." + company);
        for (var i = 0; i < companies.length - 1; i++)
            jQuery(companies[i]).fadeOut("slow", function() {
                jQuery(this).remove()
            });
        add_more();
    }
    function select_suggest_group(self) {
        var person = jQuery(self).parents(".person:first");
        var suggest = jQuery(self).children("div:first");
        jQuery(person).html(jQuery(suggest).html());
        var group = jQuery(suggest).attr("class");
        jQuery(person).attr("class", group);
        var invitees = jQuery(person).parents(".invitees:first");
        var groups = jQuery(invitees).children("." + group);
        for (var i = 0; i < groups.length - 1; i++)
            jQuery(groups[i]).fadeOut("slow", function() {
                jQuery(this).remove()
            });
        add_more();
    }
    function select_suggest_user(self) {
        var person = jQuery(self).parents(".person:first");
        var li = self;
        jQuery(person).removeClass("focused");
        var email = jQuery(li).children(".email:first").html();
        jQuery(person).find(".input:first").html(jQuery(li).children("div:first").html());
        jQuery(person).children("div").append('<a onclick="remove_invitee(event,this)" class="remove"></a>');
        jQuery(person).find("input[type=hidden]").attr("value", email);
        var invitees = jQuery(person).parents(".invitees:first");
        var need_remove = new Array();
        var count = 0;
        jQuery(invitees).children(".person").each(function() {
            var person_email = jQuery(this).find("input[type=hidden]").first().attr("value");
            if (person_email == email)
                need_remove[count++] = this;
        });
        for (var i = 0; i < need_remove.length - 1; i++)
            jQuery(need_remove[i]).fadeOut("slow", function() {
                jQuery(this).remove()
            });
        jQuery(person).removeClass("unknown");
        jQuery("ol.suggestions").html("");
        var invitees = jQuery(person).parents(".invitees:first");
        var need_remove = new Array();
        var count = 0;
        jQuery(invitees).find(".person").each(function() {
            var person_email = jQuery(this).find("input[type=hidden]").first().attr("value");
            if (person_email == email)
                need_remove[count++] = this;
        });
        for (var i = 0; i < need_remove.length - 1; i++)
            jQuery(need_remove[i]).fadeOut("slow", function() {
                jQuery(this).remove()
            });
        add_more();
    }
    function suggest_person_mouseover(self) {
        jQuery(self).addClass("selected");
    }
    function suggest_person_mouseleave(self) {
        jQuery(self).removeClass("selected");
    }
    function press_key(self, event) {
        var code = event.keyCode || event.which;
        if (code == 9) {
            var person = jQuery(self).parents(".person:first");
            var li = jQuery(person).find(".suggestions li:first");
            var data_suggestion_id = jQuery(li).attr("data-suggestion-id");
            if (typeof data_suggestion_id != 'undefined' && data_suggestion_id.length > 0) {
                if (data_suggestion_id.indexOf('person_') != -1) {
                    jQuery(person).removeClass("focused");
                    var email = jQuery(li).children(".email:first").html();
                    jQuery(person).find(".input:first").html(jQuery(li).children("div:first").html());
                    jQuery(person).children("div").append('<a onclick="remove_invitee(event,this)" class="remove"></a>');
                    jQuery(person).find("input[type=hidden]").attr("value", email);
                    var invitees = jQuery(person).parents(".invitees:first");
                    var need_remove = new Array();
                    var count = 0;
                    jQuery(invitees).find(".person").each(function() {
                        var person_email = jQuery(this).find("input[type=hidden]").first().attr("value");
                        if (person_email == email)
                            need_remove[count++] = this;
                    });
                    for (var i = 0; i < need_remove.length - 1; i++)
                        jQuery(need_remove[i]).fadeOut("slow", function() {
                            jQuery(this).remove()
                        });
                }
                else if (data_suggestion_id.indexOf('group_') != -1) {
                    var suggest = jQuery(li).children("div:first");
                    jQuery(person).html(jQuery(suggest).html());
                    var group = jQuery(suggest).attr("class");
                    jQuery(person).attr("class", group);
                    var invitees = jQuery(person).parents(".invitees:first");
                    var groups = jQuery(invitees).children("." + group);
                    for (var i = 0; i < groups.length - 1; i++)
                        jQuery(groups[i]).fadeOut("slow", function() {
                            jQuery(this).remove()
                        });
                }
                else if (data_suggestion_id.indexOf('company_') != -1) {
                    var suggest = jQuery(li).children("div:first");
                    jQuery(person).html(jQuery(suggest).html());
                    var company = jQuery(suggest).attr("class");
                    jQuery(person).attr("class", company);
                    var invitees = jQuery(person).parents(".invitees:first");
                    var companies = jQuery(invitees).children("." + company);
                    for (var i = 0; i < companies.length - 1; i++)
                        jQuery(companies[i]).fadeOut("slow", function() {
                            jQuery(this).remove()
                        });
                }
            }
            jQuery("ol.suggestions").html("");
            add_more();
        }
    }
    function search_person(self, type) {
        var person = jQuery(self).parents(".person:first");
        if (self.value.length > 0)
            jQuery(person).removeClass("blank");
        else
            jQuery(person).addClass("blank");
        if (self.value.length < 2)
            jQuery(person).find("ol.suggestions").html("");
        if (self.value.length > 1) {
            jQuery.post("<?php print $base_url; ?>/ajax-process", {action: "get_suggested_users", keyword: self.value, type: type}, function(data) {
                data = data.trim();
                var parent = jQuery(self).parent();
                var view = jQuery(parent).next();
                jQuery(view).html(data);
            });
        }
    }
    function focus_person(self) {
        var person = jQuery(self).parents(".person:first");
        jQuery(person).removeClass("unknown");
        jQuery(person).addClass("focused");
        jQuery("ol.suggestions").html("");
        search_person(self);
    }
    function blur_person(self) {
        jQuery(self).parents(".person:first").removeClass("focused");
    }
    function remove_group(self, event) {
        event.preventDefault();
        jQuery(self).parents(".group:first").parent().remove();
        add_more();
    }
    function change_person(self) {
        var person = jQuery(self).parents(".person:first");
        var containter = jQuery(person).find(".input:first");
        var inputs = jQuery(containter).children("input");
        if (inputs.length == 1 && is_email(self.value)) {
            jQuery(containter).html("");
            jQuery(person).find("input[type=hidden]").attr("value", self.value);
            jQuery(containter).append('<input type="text" readonly="" spellcheck="false" value="' + self.value + '">');
            jQuery(containter).append('<input type="text" disabled="" style="background:white; opacity:0;">');
            jQuery(person).children("div").append('<a onclick="remove_invitee(event,this)" class="remove"></a>');
            var invitees = jQuery(person).parents(".invitees:first");
            var need_remove = new Array();
            var count = 0;
            jQuery(invitees).find(".person").each(function() {
                var person_email = jQuery(this).find("input[type=hidden]").first().attr("value");
                if (person_email == self.value)
                    need_remove[count++] = this;
            });
            for (var i = 0; i < need_remove.length - 1; i++)
                jQuery(need_remove[i]).fadeOut("slow", function() {
                    jQuery(this).remove()
                });
            if (client_tab == 0) {
                jQuery("#new_superpowers_prompt").css("display", "block");
                id = "invitee-" + self.value.replace("@", "-");
                id = id.replace(".", "-");
                var table = '';
                table += '<table cellspacing="0" cellpadding="0" border="0" id="' + id + '">';
                table += '	<tbody>';
                table += '		<tr data-behavior="set_permission_checkboxes">';
                table += '			<td>';
                table += '			  <strong>' + self.value + '</strong>';
                table += '			</td>';
                table += '			<td class="option projects">';
                table += '			  <input type="hidden" value="0" name="permissions[' + self.value + '][can_create_projects]">';
                table += '			  <input type="checkbox" value="1" name="permissions[' + self.value + '][can_create_projects]">';
                table += '			</td>';
                table += '			<td class="option admin">';
                table += '			  <input type="hidden" value="0" name="permissions[' + self.value + '][admin]">';
                table += '			  <input type="checkbox" value="1" name="permissions[' + self.value + '][admin]" onclick="admin_permissions(this)">';
                table += '			</td>';
                table += '  		</tr>';
                table += '	</tbody>';
                table += '</table>';
                jQuery("#new_superpowers").append(table);
            }
        }
        else {
            jQuery(person).addClass("unknown");
        }
        add_more();
    }
    function add_more() {
        var type = 'team';
        var i_name = 'invitees';
        if (client_tab == 1) {
            type = 'client';
            i_name = 'client_invitees';
        }
        var person = '';
        person += '<div class="person invitee field blank">';
        person += '	<div class="autocomplete_people">';
        person += '        <input data-role="email_address_input" name="' + i_name + '[][email_address]" type="hidden" value="">';
        person += '        <div class="icon"></div>';
        person += '        <div class="input">';
        person += '            <input data-behavior="input_change_emitter" data-role="human_input" spellcheck="false" type="text" value="" onkeydown="press_key(this,event)" onkeyup="search_person(this,\'' + type + '\')" onchange="change_person(this)" onfocus="focus_person(this)" onblur="blur_person(this)">';
        person += '        </div>';
        person += '        <div class="suggestions" data-role="suggestions_view">';
        person += '        </div>';
        person += '    </div>';
        person += '</div>';
        if (client_tab == 0) {
            jQuery(".team_accesses .invitees").each(function() {
                var blanks = jQuery(this).children(".blank");
                if (blanks.length < 2)
                    jQuery(this).append(person);
            });
        }
        else {
            jQuery(".client_accesses .invitees").each(function() {
                var blanks = jQuery(this).children(".blank");
                if (blanks.length < 2)
                    jQuery(this).append(person);
            });
        }
    }
    
</script>

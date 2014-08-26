<?php global $base_url, $user; ?>
<div class="panel sheet">
    <header class="hide_from_android">
        <h1>Project settings</h1>
    </header>
    <article class="project settings form" data-behavior="project_form">
        <form accept-charset="UTF-8" action="" class="settings" data-replace-sheet="true" id="edit_project_<?= $project->nid ?>" method="post">
            <h2>Project name and description</h2>
            <p class="name_and_star">
                <a class="action_button" data-behavior="toggle_star" onclick="toggle_star(this)">
                    <span data-project-id="<?=$project->nid?>" class="star <?php if ($project->promote == 1) print ' on'; ?>"></span>
                </a>  
                <input type="hidden" name="project[promote]" value="<?=$project->promote?>" id="star_promote">
                <input autofocus="autofocus" id="project_name" name="project[name]" placeholder="Name the project" type="text" value="<?= $project->title ?>">
            </p>
            <p>
                <textarea class="body" data-behavior="autoresize" data-line-height="18" id="project_description" name="project[description]" placeholder="Add a description or extra details (optional)" rows="3" style="overflow: hidden;"><?= isset($project->body['und'][0]['value']) ? $project->body['und'][0]['value'] : '' ?></textarea>
            </p>

            <section>
                <h2>Project status</h2>
                <ul>
                    <li>
                        <label>
                            <input id="project_archived_false" name="project[archived]" type="radio" value="0" <?php if (!$project->sticky) print 'checked="checked"'; ?>>
                            <strong>Active</strong> —
                            Fully functional project. Add, edit, invite, share, etc.
                        </label>
                    </li>

                    <li>
                        <label>
                            <input id="project_archived_true" name="project[archived]" type="radio" value="1" <?php if ($project->sticky) print 'checked="checked"'; ?>>
                            <strong>Archived</strong> —
                            This project is locked, can't be changed, and won't count against your plan's project limit.
                        </label>
                    </li>
                </ul>
            </section>
            <footer>
                <div class="submit">
                    <input class="action_button default_action" name="commit" type="submit" value="Save changes">
                    <p><a class="action_button hide_from_ios" data-behavior="selectable" href="<?= $base_url ?>/node/<?= $project->nid ?>">Cancel changes</a></p>
                    <p><a class="action_button trash_action hide_from_ios" onclick="delete_project(<?=$project->nid?>)" data-method="post">Delete this project</a></p>
                </div>
            </footer>
        </form> 
    </article>
</div>
<script>
     function toggle_star(me){
        var parent = jQuery(me).parent();
        jQuery(parent).addClass("selected");
        var children = jQuery(me).children();
        if(jQuery(children).hasClass("on")){
            jQuery(children).removeClass("on");
            jQuery("#star_promote").attr("value", 0);
        }
        else {
            jQuery(children).addClass("on");
             jQuery("#star_promote").attr("value", 1);
        }
        jQuery(parent).removeClass("selected");
    }
    function delete_project(nid){
        jQuery.post("<?=$base_url?>/ajax-process",{'action':'delete_node','nid':nid},function(data){
            if(data.error==0){
                window.location.href=data.url;
            }
        },'json');
    }
    </script>
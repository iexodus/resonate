<?php
global $base_url, $user;
$path_theme = str_replace("project" . DS . "themes", "administration" . DS . "themes", dirname(__FILE__)) . DS;
$with_clients = isset($project->field_with_clients['und'][0]['value']) ? $project->field_with_clients['und'][0]['value'] : 0;
?>
<div class="panel sheet in_bucket ">
    <?php frender($path_theme . 'menu-layout-mobile.tpl.php', array('project' => $project)) ?>
    <header class="subheading hide_from_android">
        <h1 class="for_ios hide_from_android">People with access to the project</h1>
        <h1 class="hide_from_ios subheading hide_from_android">People with access to the project</h1>
        <a class="action_button hide_when_read_only hide_from_android" data-behavior="selectable" href="<?= $base_url ?>/accesses/new?nid=<?= $project->nid ?>">Invite more people</a>
    </header>
     <nav class="accesses_tabs" data-behavior="access_tabs">
        <a class="reveal selected" onclick="reveal(this,'team')">Our Team</a>
        <a class="reveal" onclick="reveal(this,'client')">The Client</a>
    </nav>
    <section class="accesses people_team">
<!--        <h2>Our Team</h2>-->
        <?php
        foreach ($users as $member) {
            ?>
            <article class="access_person">
                <div class="hide_from_single_project_accounts">
                    <a data-behavior="selectable" href="<?php print $base_url . '/user/' . $member->uid; ?>" class="link_page" data-uid="<?= $member->uid ?>">
                        <img alt="IE" class="avatar" height="96" src="<?php print $base_url . '/sites/default/files/profile/' . $member->field_profile_picture['und'][0]['value']; ?>" title="<?php print $member->fname; ?>" width="96">
                        <?php if (is_email($member->fname)) { ?>
                            <?php $secs = explode('@', $member->fname); ?>
                            <h3>
                                <span class="mailbox"><?php print $secs[0]; ?></span><span class="at_symbol">@</span><span class="domain"><?php print $secs[1]; ?></span>

                            </h3>

                        <?php } else { ?>
                            <h3><?= $member->fname; ?></h3>
                        <?php } ?>

                        <?php if (!empty($member->access)) { ?>
                            <p>Active <time data-local="time-ago" datetime="<?= get_datetime($member->login) ?>" title=""><?= time_date($member->login, 'message') ?></time></p>
                        <?php } else { ?>
                            <p class="<?php if (date('Y-m-d H:i', $member->assign_project) == $newest) print 'fresh'; ?>"> Invited to the project <time data-local="time-ago" datetime="<?= get_datetime($member->assign_project) ?>" title=""><?= time_date($member->assign_project, 'message') ?></time>.</p>
                            <?php
                        }
                        if ($member->uid == 2) {
                            ?>
                            <p><small>The account owner has access to all projects.</small></p>
                        <?php } ?>
                    </a>  
                </div>
                <div class="show_to_single_project_accounts">
                    <img alt="<?= $member->fname; ?>" class="avatar" height="96" src="<?php print $base_url . '/sites/default/files/profile/' . $member->field_profile_picture['und'][0]['value']; ?>" title="IE" width="96">

                    <h3><?php print $member->fname; ?> </h3>
                    <?php if (!empty($member->access)) { ?>
                        <p>Active <time data-local="time-ago" datetime="<?= get_datetime($member->login) ?>" title=""><?= time_date($member->login, 'message') ?></time></p>
                    <?php } else { ?>
                        <p class="<?php if (date('Y-m-d H:i', $member->assign_project) == $newest) print 'fresh'; ?>"> Invited to the project <time data-local="time-ago" datetime="<?= get_datetime($member->assign_project) ?>" title=""><?= time_date($member->assign_project, 'message') ?></time>.</p>
                    <?php } if ($member->uid == 2) { ?>
                        <p><small>The account owner has access to all projects.</small></p>
                    <?php } ?>
                </div>
            </article>
        <?php } ?>
    </section>
    <section class="accesses people_client">
<!--        <h2>The Client</h2>-->
        <?php if (count($clients) <= 0) { ?>
            <article class="blank_client_access">
                <div class="blank_slate">
                    <h2><br>You haven’t invited any clients to this project.<br><br></h2>
                </div>
            </article>
            <?php
        } else {
            foreach ($clients as $member) {
                ?>
                <article class="access_person">
                    <div class="hide_from_single_project_accounts">
                        <a data-behavior="selectable" href="<?php print $base_url . '/user/' . $member->uid; ?>">
                            <img alt="<?= $member->fname ?>" class="avatar" height="96" src="<?php print $base_url . '/sites/default/files/profile/' . $member->field_profile_picture['und'][0]['value']; ?>" title="<?= $member->fname; ?>" width="96">
                            <?php
                            if (is_email($member->fname)) {
                                $secs = explode('@', $member->fname);
                                ?>
                                <h3>
                                    <span class="mailbox"><?php print $secs[0]; ?></span><span class="at_symbol">@</span><span class="domain"><?php print $secs[1]; ?></span>

                                </h3>

                            <?php } else { ?>
                                <h3><?= $member->fname; ?></h3>
                            <?php } ?>

                            <?php if (!empty($member->access)) { ?>
                                <p>Active <time data-local="time-ago" datetime="<?= get_datetime($member->login) ?>" title=""><?= time_date($member->login, 'message') ?></time></p>
                            <?php } else { ?>
                                <p class="fresh"> Invited to the project <time data-local="time-ago" datetime="<?= get_datetime($member->created) ?>" title=""><?= time_date($member->assign_project, 'message') ?></time>.</p>
                            <?php } ?>

                        </a>  
                    </div>

                    <div class="show_to_single_project_accounts">
                        <img alt="<?= $member->fname ?>" class="avatar" height="96" src="<?php print $base_url . '/sites/default/files/profile/' . $member->field_profile_picture['und'][0]['value']; ?>" title="<?= $member->fname; ?>" width="96">
                        <?php
                        if (is_email($member->fname)) {
                            $secs = explode('@', $member->fname);
                            ?>
                            <h3>
                                <span class="mailbox"><?php print $secs[0]; ?></span><span class="at_symbol">@</span><span class="domain"><?php print $secs[1]; ?></span>

                            </h3>

                        <?php } else { ?>
                            <h3><?= $member->fname; ?></h3>
                        <?php } ?>

                        <?php if (!empty($member->access)) { ?>
                            <p>Active <time data-local="time-ago" datetime="<?= get_datetime($member->login) ?>" title=""><?= time_date($member->login, 'message') ?></time></p>
                        <?php } else { ?>
                            <p class="fresh"> Invited to the project <time data-local="time-ago" datetime="<?= get_datetime($member->created) ?>" title=""><?= time_date($member->assign_project, 'message') ?></time>.</p>
                        <?php } ?>
                    </div>
                </article>
            <?php } ?>
        <?php } ?>
    </section>
</div>
<script>
<?php if (isset($_SESSION['invited'])) { ?>
           var name = '<?= $_SESSION['invited'] ?>';
           var string ='<div class="flash notice hide_from_android" data-android-toast="Success! '+name+' invited to the project." data-behavior="flash"><a class="close_button" onclick="close_flash(this)">close</a><span class="flash_message">Success! '+name+' were invited to the project.</span></div>';
           jQuery("body").prepend(string);
    <?php
    unset($_SESSION['invited']);
}
?>
    function reveal(me,flag){
        jQuery(".reveal").removeClass("selected");
        jQuery("section.accesses").hide();
            jQuery(me).addClass("selected");
jQuery("section.people_"+flag).show();
    }
    
    </script>
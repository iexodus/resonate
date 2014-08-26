

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
<script type="text/javascript" src="<?= $base_url ?>/sites/all/themes/adaptivetheme/basecamp/scripts/storyjs-embed.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>/sites/all/modules/project/js/jcarousellite.js"></script>
<!--<link href="<?= $base_url ?>/sites/all/modules/project/css/custom_css.css" rel="stylesheet">-->
<?php $nproject = 0; ?>
<?php $result = db_query("SELECT COUNT(nid) as total FROM node WHERE type = 'project'"); ?>
<?php foreach ($result as $object)
    $nproject = $object->total; ?>
<?php $author = user_load($user->uid); ?>
<?php $create_projects = can_create_projects($author); ?>
<div data-body-class="home flat_background has_user_menu topnav_root" class="page_discover panel home_tab project_index<?php if (empty($cards) && empty($list) && empty($updates)) echo ' blank_slate'; ?><?php if (!$create_projects && empty($cards) || empty($list) || empty($updates)) print ' no_nav'; ?>">
    <h3 class="title"><?=isset($page_title)?$page_title:'Staff Picks'?> <a href="#" data-behavior="new_project bounce" style="float: right;display:none"><span>Create Project</span></a>  </h3>
        <?php if ($create_projects || !empty($cards) || !empty($list) || !empty($updates) || !empty($archived)) { ?>
        <div id="all_projects" style="display:block;">
        <?php } ?>
<?php if (!empty($cards)) { ?>
            <section class="projects cards ">
                <div class="item_project_block">
                    <?php
                    $arr_ul = array();
                    $index = $i = 0;
                    foreach ($cards as $info) {
                        if ($index % 8 == 0 && $index != 0) {
                            $i++;
                        }
                        $arr_ul[$i][] = $info;
                        $index++;
                    }
                    ?>
                    <?php for ($i = 0; $i < count($arr_ul); $i++): ?>
                        <?php
                        $style_ul = '';
                        $curent = $i;
                        if ($curent != 0) {
                            $style_ul = ' style="display:none;" ';
                        }
                        $next = $i + 1;
                        $pre = $i - 1;
                        if ($curent == (count($arr_ul) - 1)) {
                            $next = 0;
                        }
                        if ($curent == 0) {
                            $pre = count($arr_ul) - 1;
                        }
                        ?>
                        <ul class="slide_project_click slide_<?= $curent ?>" curent="<?= $curent ?>" next="<?= $next ?>"  pre="<?= $pre ?>" <?= $style_ul ?>>
                            <?php
                            for ($j = 0; $j < count($arr_ul[$i]); $j++):
                                $member = get_name_member($arr_ul[$i][$j]->uid);
                                $full_name = $member['full_name'];
                                $tid = isset($member['info']->field_profile_state['und'][0]['tid']) ? $member['info']->field_profile_state['und'][0]['tid'] : 0;

                                if ($tid > 0) {
                                    $states = taxonomy_term_load($tid);
                                }
                                $city = isset($member['info']->field_profile_city['und'][0]['value']) ? $member['info']->field_profile_city['und'][0]['value'] : '';
                                $project_nid = $arr_ul[$i][$j]->nid;
                                $status_like = get_status_like('project', $project_nid);
                                ?>
                                <li>
                                    <article class="card<?php if ($arr_ul[$i][$j]->status == 0) print ' draft'; ?>">
                                        <a title="<?php print $arr_ul[$i][$j]->title; ?>" href="<?php print $base_url . '/' . $arr_ul[$i][$j]->alias; ?>" class="project_card link_page" data-nid="<?= $arr_ul[$i][$j]->nid ?>" type_node="prj">
                                            <div class="image_project">
                                                <?php if (isset($arr_ul[$i][$j]->field_project_image_text["und"][0]["value"])): ?>
                                                    <?php
                                                    $src = $base_url . '/sites/default/files/img_project/' . $arr_ul[$i][$j]->field_project_image_text["und"][0]["value"];
                                                    ?>

                                                    <div class="container_img_prj"><div class="img_prj" style="background: url('<?= $src ?>');background-size: cover;background-position: center center;"></div></div>
            <?php endif; ?>
                                            </div>
                                            <h5 class="title_project"><?php print $arr_ul[$i][$j]->title; ?></h5>
                                            <div class="author"> by <?= $full_name ?></div>
                                            <div class="description_prj"><?= isset($arr_ul[$i][$j]->body["und"][0]["value"]) ? trim_text($arr_ul[$i][$j]->body["und"][0]["value"], 70) : '' ?></div>
                                        </a>	
                                        <div class="line"></div>
                                        <div class="location-name">
                                            <a href="<?= $base_url ?>/projects?places=true&city=<?= $city ?>&state=<?= isset($states->tid) ? $states->tid : 0 ?>">
            <?= $city ?>
            <?= isset($states->name) ? ', ' . $states->name : '' ?>
                                            </a>
                                        </div>
                                        <div class="star <?= ($status_like == 1) ? 'on' : ($status_like == 0 ? 'dislike' : '') ?>"><a onclick="set_favorites(this,<?= $project_nid ?>)">Like</a></div>
                                    </article>

                                </li>
                        <?php endfor; ?>
                            <div class="next_buton_click next "></div>
                        </ul>
                    <?php endfor; ?>

    <?php $count = 0; ?>

                </div>
        </div>


    </section>
    <?php } ?>
    <?php if (empty($cards) && empty($list) && empty($updates) && empty($archived)) { ?>
    <section class="projects cards">
    <?php if ($create_projects) { ?>
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
<?php if ($create_projects || !empty($cards) || !empty($list) || !empty($updates) || !empty($archived)) { ?>
    </div>
<?php } ?>
<div style="display: none" class="starred_project_notification">This project will now appear as a card at the top of the page.</div>
</div>

<!--<script type="text/javascript" src="http://cdn.knightlab.com/libs/timeline/latest/js/storyjs-embed.js"></script>-->








<script >
    jQuery(document).ready(function(){
        jQuery("body").addClass("discover_page");    
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
                
        //            jQuery.get("<?= $base_url ?>/iframe_mile",{nid:60},function(data){alert(data);
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
        jQuery.post("<?php print $base_url; ?>/ajax-process",{action:"project_star",star:star,nid:nid},function(data){
            jQuery("#block-system-main").html(data);
        });
    }
    function change_project_view(me){
        jQuery(".project_view").addClass("busy");
        var view = jQuery(me).attr("class");
        jQuery.post("<?php print $base_url; ?>/ajax-process",{action:"load_content",content:"projects",project_view:view,page:false},function(data){
            jQuery("#block-system-main").html(data);
        });
    }
    //	function new_project(me){
    //		jQuery(".projects").animate({"padding-top":"100px"},500);
    //		jQuery("header").animate({opacity:0},500,"swing");
    //		jQuery(".container").animate({opacity:0},500,"swing",function(){
    //			jQuery("body").append('<div id="new_project_dialog" style="display: block; padding: 0px;" data-behavior="new_project_dialog"><article class="new" style="opacity: 1; width: 0; height: 0; overflow: hidden;"></div></div>');
    //			jQuery("#new_project_dialog > article").animate({width:640, height:283}, 500, "swing", function(){
    //				jQuery.post("<?php print $base_url; ?>/ajax-process",{action:"load_content",content:"add_project"},function(data){
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
    //		jQuery.post("<?php print $base_url; ?>/ajax-process",{action:"load_content",content:"templates"},function(data){
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
    function set_favorites(me,nid){
        var status  = 1;
        jQuery.post("<?= $base_url ?>/ajax-process", {'action':'set_favorites','nid':nid,'status':status},function(data){
            if(data>0){
                jQuery(me).parent().addClass("on");
            }
        });
    }
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




<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $base_url,$user;
?>
<script type="text/javascript" src="<?=$base_url?>/sites/all/themes/adaptivetheme/basecamp/scripts/storyjs-embed.js"></script>
<script type="text/javascript" src="<?=$base_url?>/sites/all/libraries/jquery.min.js"></script>

<?php if(isset($_GET["nid"])){
    if($_GET["nid"]>0){
        $id=$_GET["nid"];
         $list_milestone=  db_query("SELECT * FROM tbl_project_attribute WHERE type=6 AND nid='$id' ORDER BY aid DESC LIMIT 0,10");
         
         $count_milestone=  db_query("SELECT COUNT(aid) FROM tbl_project_attribute WHERE type=6 AND  nid='$id' ORDER BY aid DESC LIMIT 0,10")->fetchField();
         $arr_mile=array();
         if($count_milestone>0){
             $arr_date=array();
             $arr_year=array();
             $arr_month=array();
              $arr_start=array();
             $arr_start_title=array();
             $arr_mile_have_date=array();
             foreach ($list_milestone as $milestone){
                    
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
            

                if($date_mile>0){
                    $ls_date=  explode("-", $milestone->assign_date);
                    $dt=$ls_date[1]."/".$ls_date[2];
                    $arr_date[]=$ls_date[2];
                    $arr_month[]=$ls_date[1];
                    $arr_year[]=$ls_date[0];
                    $arr_mile_have_date[]=$milestone;
                    $arr_start[]=$ls_date[0].",".$ls_date[1].",".$ls_date[2];
                    $arr_start_title[]=date("M d", strtotime($ls_date[0]."-".$ls_date[1]."-".$ls_date[2]));
                }
             }
        
         ?>


        <div id="timeline-embed_<?= $_GET["nid"]?>"></div>
        <?php if(count($arr_start)>0):?>
        <script>
            jQuery(document).ready(function(){

                      var dataObject = {
                         "timeline":
                             {

                                     "type":"default",

                                     "date": [
                                         <?php for($i=0;$i<count($arr_start);$i++) {
                                             echo '{"startDate":"'.$arr_start[$i].'","endDate":"'.$arr_start[$i].'","headline":"'.$arr_mile_have_date[$i]->subject.' - '.$arr_start_title[$i].'"},';
                                         }?>


                                     ]

                             }
                     }
                         createStoryJS({
                         type:		'timeline',
                         width:		'440',
                         height:	'250',
                         source:		dataObject,
                         embed_id:	'timeline-embed_<?= $_GET["nid"]?>'
                 });
           
           });
                                       
           </script>
           <?php endif;?>
    <?php } }}

 ?>
<style>


.vco-slider .slider-container-mask .slider-container,.nav-next,.nav-container{
    display:none !important;

}
.storyjs-embed,.vco-storyjs,.vco-timeline .vco-navigation .timenav-background .timenav-interval-background{
    background: transparent !important;
    
}
.storyjs-embed.sized-embed{
    border:none !important;
}

</style>

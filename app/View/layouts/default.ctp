<?php
/* SVN FILE: $Id$ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml" >
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php __('uLink | '); ?>
            <?php echo $title_for_layout; ?>
        </title>
        <script src="http://maps.google.com/maps?file=api&amp;v=2.122&amp;key=<?php echo GOOGLE_MAP_API_KEY; ?>"
        type="text/javascript"></script>

        <script src="http://www.google.com/uds/api?file=uds.js&amp;v=2.122&amp;key=<?php echo GOOGLE_MAP_API_KEY; ?>" type="text/javascript"></script>

        <?php
        echo $this->Html->meta('icon');
        echo $this->Html->css(array('style.css', 'autofill.css', 'pop_thickbox.css', 'thickbox.css'));
        echo $this->Html->script(array('var.js', 'jquery.js', 'infobox.js', 'autofill.js', 'thickbox.js', 'form-submit.js', 'ajax.js', 'validate.js', 'jquery.form.js', 'tiny_mce/tiny_mce.js'));
        echo $scripts_for_layout;
        ?>

        <style type="text/css">
            bodY{ font-family:verdana; font-size:12px; }
            .mapContent{ width:100%; }
            .mapContent img{ float:left; border:solid 1px #ccc; padding:3px; }
            .mapContent .content{ margin-left:90px; }
            .mapContent .content{ margin-left:90px; }
            .mapContent a{ text-decoration:underline; color:#126ab6; }
            .mapContent a:hover{ text-decoration:none; color:#000; }
            .mapContent .content h1, .mapContent .content .inner{ 
                color:#1563af; font-size:12px; border-bottom:dashed 1px #ccc; margin:0 0 5px 0; padding:0 0 4px 0;
            }
            .mapContent .content h1, .mapContent .content .inner.last{ border:none; }
            .mapContent .content .inner{ color:#4e4e4e; line-height:20px; }
            .mapContent .content .inner strong{ width:67px; float:left; }
            .mapContent .content .inner img{ border:none; padding:0; float:none; }
        </style>

        <script type="text/javascript" language="javascript">
            //<![CDATA[
            var map;
 
            var baseIcon;
            var icons = [];
            var gmarkers = [];
            var gmarkershtml = []; 
	 
	
            function myclick(i) {
                if(gmarkers[i] != null) {
                    gmarkers[i].openInfoWindowHtml(gmarkershtml[i]);
                }
                map.removeOverlay(map.infoBox);
            }

            function load() 
            {
                if (GBrowserIsCompatiblecho()) 
                {
                   
                    map = new GMap2(document.getElementById("map"));
                    //map.addControl(new GLargeMapControl());
                    //map.addControl(new GMapTypeControl());     to remove the previous scoller
                    //map.setCenter(new GLatLng('<?php //echo $UserLatitiude   ?>','<?php //echo $UserLongitude;   ?>'), 13);
				
                    var customUI = map.getDefaultUI();
                    customUI.maptypes.hybrid = false;
                    map.setUI(customUI);
				
                    // Create a base icon for all of our markers that specifies the
                    // shadow, icon dimensions, etc.
                    baseIcon = new GIcon(G_DEFAULT_ICON);
                    baseIcon.shadow = "http://www.google.com/mapfiles/shadow50.png";
                    baseIcon.iconSize = new GSizecho(25, 25);
                    baseIcon.shadowSize = new GSizecho(40, 25);
                    baseIcon.iconAnchor = new GPoint(0, 0);
                    baseIcon.infoWindowAnchor = new GPoint(0, 0);
				
                    // Add 10 markers to the map at random locations
                    //var bounds = map.getBounds();
                    // Reset bounds  
                    var bounds = new GLatLngBounds(); 
                    // var southWest = bounds.getSouthWest();
                    // var northEast = bounds.getNorthEast();
                    // var lngSpan = northEast.lng() - southWest.lng();
                    // var latSpan = northEast.lat() - southWest.lat();

                    var i=0;	

<?php
$j = 1;
if ($schools != null) {
    foreach ($schools as $school) {
        $distance = AppController::get_distancecho($school['School']['latitude'], $UserLatitiude, $school['School']['longitude'], $UserLongitude);

        if ($school['School']['image_url'] == "") {
            $school['School']['image_url'] = "noImage.jpg";
        }
        ?>
                            var point = new GLatLng(<?php echo $school['School']['latitude']; ?>,<?php echo $school['School']['longitude']; ?>);
                            map.addOverlay(createMarker(point,i++,<?php echo $school['School']['id']; ?>,'<?php echo $school['School']['name']; ?>','<?php echo $school['School']['address']; ?>','<?php echo $school['School']['image_url']; ?>','<?php echo $hiv = count($school['Review']); ?>','<?php echo $school['School']['rating']; ?>','<?php echo $distance ?>','<?php echo $j; ?>','<?php echo $j - 1; ?>','<?php echo $j + 1; ?>'));
                            bounds.extend(point);
        <?php $j++;
    } ?> 
                    map.setCenter(bounds.getCenter(), map.getBoundsZoomLevel(bounds)); 
         
<?php } else { ?>
                map.setCenter(new GLatLng('<?php echo $UserLatitiude ?>','<?php echo $UserLongitude; ?>'), 5);
<?php } ?>
            // myclick(1);
        }
    } //end load()

    function createMarker(point,index,id,name,address,image_url,totReview,ratingCount,distance_cal,current,prev,next) 
    {
        var letter = String.fromCharCodecho("A".charCodeAt(0) + index);
        var letteredIcon = new GIcon(baseIcon);
        letteredIcon.image = hostname+"/app/webroot/img/degree-icon.png";

        // Set up our GMarkerOptions object
        markerOptions = { icon:letteredIcon };
        var marker = new GMarker(point, markerOptions);
        gmarkers[current] = marker;
			
        if (document.images)
        {
            preload_image = new Imagecho(90,90); 
            preload_image.src=hostname+"app/img/files/schools/"+image_url+""; 
        }

        if (index >= 0) 
        {
            var schoolid='<?php echo $userSchoolId; ?>';
	  
            if(id==schoolid)
            { 
                var review='<?php echo $MyReview; ?>';
                var str = '';
                
                switch (review) 
                { 
                    case '0': str="<div class='inner'>Review approval pending</div>"; 
                        break;
                    case '1': str="<a class='thickbox right' href= '<?php echo $this->Html->url('/reviews/alltextreview/'); ?>"+id+"'><img alt='' src='"+hostname+"app/img/modify.gif'></a>";
                        break;
                    case '2': str="<a class='thickbox right' href= '<?php echo $this->Html->url('/reviews/alltextreview/'); ?>"+id+"'><img alt='' src='"+hostname+"app/img/write-a-review.gif'></a>";
                        break;
                    }
	  
                }
                else
                {
                    var str="";
                }
	  
	  
                if(ratingCount!=0)
                {
                    var ratingValue="<div class='inner last'><strong>Rating:</strong><a href='<?php echo $this->Html->url('/reviews/alltextreview/'); ?>"+id+"'><img src='"+hostname+"app/img/star-"+ratingCount+".gif' title='"+ratingCount+"' /></a></div>";
		  
                }
                else
                {
                    var ratingValue="";
		  
                }
                var nextValue=<?php echo count($schools); ?>;
	  
                var html = "<div class='mapContent' ><a href='<?php echo $this->Html->url('/schools/detail/'); ?>"+id+"'><img src='"+hostname+"app/img/files/schools/"+image_url+"' width='90' height='90'/></a><div class='content'><h1><a href='<?php echo $this->Html->url('/schools/detail/'); ?>"+id+"'>"+name+"</a></h1>" +
                    "<div class='inner'>"+address+"</div>" +
                    "<div class='inner'><strong>Distance:</strong>"+distance_cal+" miles from your location</div>" +
                    "<div class='inner'><strong>Reviews:</strong><span>"+totReview+"</span>"+ str+"</div>" +   ratingValue;
                 
				 

               
	
                /*  if the schools are greater than one then only previous next comes */
                /*	<?php //if (count($schools) > 1) {   ?>
                                if(prev != 0) {
                                        html += "<div class='inner last'><a href='javascript:myclick("+prev+")'>Prev</a> |";
                                }
                                else
                                {
                                        html += "<div class='inner last'>Prev |";
                                }
                                if(next<=nextValue)
                                {
                         html += " <a href='javascript:myclick("+next+")'>Next</a> </div>" ;
                         }
                         else
                         {
                                  html += " Next </div>" ;
                         } */
            	 
<?php //}   ?>
                html  +=  "</div><div class='clear'></div></div>";
			  
                gmarkershtml[current]=html;
			  
                var infoBoxOptions = {
                    "content": html,
                    "offsetHorizontal": 15,
                    "offsetVertical": -180,
                    "height": -250,
                    "width": 250,
                    "className": "infoBox"
                };

                var infoBox = new InfoBox(point, infoBoxOptions);
                marker.infoBox = infoBox;
     
                GEvent.addListener(marker, "click", function () {
                    if (map.infoBox) {
                        map.closeInfoWindow(); 
                        map.removeOverlay(map.infoBox);
                    }
                    map.infoBox = marker.infoBox;
                    //map.openInfoWindowHtml(map.infoBox);
                    map.addOverlay(map.infoBox);
        
                });
            } else {
        
                GEvent.addListener(marker, "dblclick", function() {
                    map.setZoom(4);
                }); 
            }
            return marker;
	  
	  
        }



        function hidesuggestionstext()
        {
	
            $("#suggestionstext").hidecho();
        }

        //]]>
	
	
        function blank(a) {

		 
            hideViewOption();
        }
        function blankDefault() {  var myTextField = document.getElementById('inputString');
            hideViewOption();
            if(myTextField.value=="Quick search"){document.getElementById('inputString').value="";}
        }

	

        var configArray = [{
                theme : "advanced",
                mode : "textareas",
                language : "en",
                height:"200",
                width:"450",

                theme_advanced_layout_manager : "SimpleLayout",
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull",
                theme_advanced_buttons2 : "",
                theme_advanced_buttons3 : ""
            },{
                theme : "advanced",
                mode : "none",
                language : "en",
                width:"100px",
                theme_advanced_layout_manager : "SimpleLayout",
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left"
            }]
        tinyMCE.settings = configArray[0];
        tinyMCE.execCommand('mceAddControl', true, "textarea1");

        $(document).ready(function() {
		
		
          /*  $('.search_text').focus(function(){
                var entredtext = $(this).val();
                var defaultText = $(this).attr('title');
                if( entredtext == defaultText){
                    $(this).val('');
                }
            });

            $('.search_text').blur(function(){
                var defaultText = $(this).attr('title');
                var entredtext = $(this).val();

                if( entredtext == ''){
                    $(this).val(defaultText);
                }

            }); */
	
	  
            $(".topMenuAction").click( function() {
                if ($("#openCloseIdentifier").is(":hidden")) {
                    $("#sliderLeft").animatecho({ 
                        marginLeft: "-76px"
                    }, 500 );
                    $("#topMenuImage").html('<img src="../img/openLeftPanel.png" class="start" />');
                    $("#openCloseIdentifier").show();
                } else {
                    $("#sliderLeft").animatecho({ 
                        marginLeft: "0px"
                    }, 500 );
                    $("#topMenuImage").html('<img src="../img/closeLeftPanel.png" />');
                    $("#openCloseIdentifier").hidecho();
                }
            }); 
		
		
            $('#suggestS').click(function(){
				
                tb_show('Suggest a School','#TB_inline?height=150&width=450&inlineId=popup_name', null);
		

            });

            $('#writeAreview2').click(function(){

                if (tinyMCE.getInstanceById('textarea2'))
                {
                    tinyMCE.execCommand('mceFocus', false, 'textarea2');
                    tinyMCE.execCommand('mceRemoveControl', false, 'textarea2');
                }


                var schoolName= '<?php echo $Shoolreview[0]['School']['name'] ?>';

                tb_show(schoolName,'#TB_inline?height=530&width=500&inlineId=writeReview', null);
                if (tinyMCE.getInstanceById('textarea2'))
                {


                    tinyMCE.execCommand('mceFocus', false, 'textarea2');
                    tinyMCE.execCommand('mceRemoveControl', false, 'textarea2');
                }else
                {

                    tinyMCE.settings = configArray[0];
                    tinyMCE.execCommand('mceAddControl', true, "textarea2");
                }


                //createEditor();
            });

            $('#writeAreview').click(function(){ 

                if (tinyMCE.getInstanceById('textarea2'))
                {
                    tinyMCE.execCommand('mceFocus', false, 'textarea2');                    
                    tinyMCE.execCommand('mceRemoveControl', false, 'textarea2');
                }

	
                var schoolName= '<?php echo $Shoolreview[0]['School']['name'] ?>';
	
                tb_show(schoolName,'#TB_inline?height=530&width=500&inlineId=writeReview', null);
                if (tinyMCE.getInstanceById('textarea2'))
                {
	
	
                    tinyMCE.execCommand('mceFocus', false, 'textarea2');                    
                    tinyMCE.execCommand('mceRemoveControl', false, 'textarea2');
                }else
                {
	
                    tinyMCE.settings = configArray[0];
                    tinyMCE.execCommand('mceAddControl', true, "textarea2");
                }
	
		
                //createEditor();
            });





            $('#writeAreviewNew').click(function(){ 
                if (tinyMCE.getInstanceById('textarea2'))
                {

	
	
                    tinyMCE.execCommand('mceFocus', false, 'textarea2');                    
                    tinyMCE.execCommand('mceRemoveControl', false, 'textarea2');
                }

	
                var schoolName= '<?php echo $Shoolreview[0]['School']['name'] ?>';
	
                tb_show(schoolName,'#TB_inline?height=530&width=500&inlineId=writeReview', null);
                if (tinyMCE.getInstanceById('textarea2'))
                {	
                    tinyMCE.execCommand('mceFocus', false, 'textarea2');                    
                    tinyMCE.execCommand('mceRemoveControl', false, 'textarea2');
                }else
                {
	
                    tinyMCE.settings = configArray[0];
                    tinyMCE.execCommand('mceAddControl', true, "textarea2");
                }
	
		
                //createEditor();
            });

        });
	
	
	
	
	
        </script>	

        <script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php"></script>
        <script type="text/javascript">   FB.init("<?php echo FACEBOOK_APP_ID; ?>","<?php echo FACEBOOK_APP_URL; ?>");</script>	

    </head>
    <body onload="load()" onunload="GUnload()">


        <?php echo $this->Form->input('abc', array('type' => 'hidden', 'value' => $usertextreview[0]['Review']['description'])); ?>
        <div id="mainContainer">
            <div class="main_inner_container">
                <div id="leftSliderWrap">
                    <div style="display: block;" id="openCloseIdentifier"></div>
                    <div style="margin-left:-76px;" id="sliderLeft">
                        <div id="sliderContent">
                                <!-- <a href="<?php echo($this->Html->url('/maps/map_index')); ?>"><?php echo $this->Html->image(('reviews-icon.gif', array('alt' => '')); ?></a>
					<a href="<?php echo($this->Html->url('/')); ?>"><?php echo $this->Html->image(('submit_review_icon.gif', array('alt' => 'submit a review')); ?></a>
					<a href="<?php echo($this->Html->url('/')); ?>"><?php echo $this->Html->image(('rated-icon.gif', array('alt' => 'suggest a school')); ?></a> -->

                            <?php if ($loggedInId) { ?>
                                <a href="javascript:void(0);" id="writeAreview" class="poplight suggestSchool">

                                    <?php echo $this->Html->image(('submit_review_icon.gif', array('alt' => 'submit a review', 'title' => 'submit a review')); ?></a>

                            <?php } else { ?>
                                <a href="javascript:void(0);" class="login loginBoxPopup"><?php echo $this->Html->image(('submit_review_icon.gif', array('alt' => 'submit a review', 'title' => 'submit a review')); ?></a>

                            <?php } ?>


                            <a href="javascript:void(0);" id="suggestS" class="poplight suggestSchool"><?php echo $this->Html->image(('suggest-icon.gif', array('alt' => 'suggest a school', 'class' => 'no-border', 'title' => 'suggest a school')); ?></a>
                            <a href="javascript:void(0);"><?php echo $this->Html->image(('left-panel-bottom.png', array('alt' => '', 'class' => 'no-border')); ?></a>
                        </div>

                    </div>
                    <div id="openCloseWrap"><a href="javascript:void(0);" class="topMenuAction" id="topMenuImage"><?php echo $this->Html->image(('openLeftPanel.png', array('alt' => '')); ?></a></div>		
                </div>
                <div id="header">
                    <div class="left"><?php echo $this->Html->link($this->Html->image(("logouLinkv2.png"), array('controller' => 'pages', 'action' => 'home'), array('escape' => false)); ?></div>


                    <?php echo $this->element('login_tab'); ?>

                    <div class="searchPanel">
                        <div class="left">
                            <a href="<?php echo($this->Html->url('/')); ?>"><?php echo $this->Html->image(('home-icon.gif', array('alt' => '')); ?></a>
                            <a href="<?php echo($this->Html->url('/maps/map_index')); ?>"><?php echo $this->Html->image(('map-icon.gif', array('alt' => '')); ?></a>
                            <?php if ($loggedInId) { ?>
                            <a href="javascript:void(0);" id="writeAreview2" class="poplight suggestSchool">

                                <?php echo $this->Html->image(('submit_review_icon.gif', array('alt' => 'submit a review', 'title' => 'submit a review')); ?></a>

                            <?php } else { ?>

                            <a href="javascript:void(0);" class="login loginBoxPopup"><?php echo $this->Html->image(('submit_review_icon.gif', array('alt' => 'submit a review', 'title' => 'submit a review')); ?></a>
                            <?php } ?>
                        </div>
                        <div class="right"><?php echo $this->Html->image(('search-bar-right.gif', array('alt' => '')); ?></div>

                        <div class="new_search">
                            <!-- <div class="search" id="tobeSearch">- -->
                            <?php echo $this->Form->create(('Map', array('action' => 'map_index')); ?>

                            <span class="left"><?php echo $this->Html->image(('left_search_icon.png', array('alt' => '')); ?></span>
                            <?php
                            if (isset($search_srting)) {

                                $searchValueUser = $search_srting;
                            }



                            echo $this->Form->text('search', array('type' => 'text', 'id' => 'inputString', 'value' => $searchValueUser, 'title' => 'Search', 'placeHolder' => 'Search',
                                'class' => 'search_text',
                                'autocomplete' => 'off',
                                'onKeyUp' => 'lookup(this.value)'
                            ));
                            ?>

                            <div id="suggestionstext" class="suggestionshelp" value="Map" style="margin:0 20" onmouseout="hidesuggestionstext();"  >
                            </div>

                            <?php
                            if (isset($type)) {

                                $searchType = $type;
                            } else {

                                $searchType = "Map";
                            }
                            ?>

                            <input class="drop_help" type="text" id="droptext" value="<?php echo $searchType; ?>" onmouseover="showOptionDefault();" />

                            <?php echo $this->Form->button('Search', array('type' => 'Submit', 'class' => 'btn', 'onmouseover' => 'blankDefault()')); ?>

                            <div id="suggestions" class="suggestionsBox" value="uMap" style="display:none;">
                                <div id="autoSuggestionsList" class="suggestionList">
                                    <div>
                                        <ul style="list-style: none outside none;" id="opList">
                                            <li onclick="hideOption('maps')"   class="activeOption" id="optionmaps">Map</li>
                                            <li onclick="hideOption('users');" id="optionusers">Profile</li>
                                            <li class="no-border" onclick="hideOption('reviews')" id="optionreviews">Review</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <?php echo $this->Form->end(); ?>
                        </div>



                        <div class="clear"></div>
                    </div>



                </div>
                <div class="pageHeading"><?php if (isset($currentPageHeading))
                                echo $currentPageHeading; ?>
                </div>
                <div id="whiteContentBox" class="clear">
                    <div class="top">
                        <span class="left"><?php echo $this->Html->image(('white-box-top-left-inner.gif', array('alt' => '')); ?></span>
                        <span class="right"><?php echo $this->Html->image(('white-box-top-right-inner.gif', array('alt' => '')); ?></span>
                        <div class="clear"></div>
                    </div>
                    <?php $session->flash(); ?>
                    <?php echo $content_for_layout; ?>


                    <div class="bottom">
                        <span class="left"><?php echo $this->Html->image(('white-box-bottom-left-inner.gif', array('alt' => '')); ?></span>
                        <span class="right"><?php echo $this->Html->image(('white-box-bottom-right-inner.gif', array('alt' => '')); ?></span>				
                    </div>
                </div>
                <div class="clear"></div>
            </div>


        </div>
        <div class="footer_bottom">
            <div class="share">
                <div class="left"><?php echo $this->Html->image(('find-us.gif', array('alt' => '')); ?></div>
                <a href="javascript:void(0)" class="facebook"></a>
                <a href="http://www.twitter.com/ulinkinc" class="twitter"></a>
                <a href="javascript:void(0)" class="stumble"></a>
                <a href="javascript:void(0)" class="delicious"></a>
                <a href="javascript:void(0)" class="yahoo"></a>
                <div class="clear"></div>
            </div>

            <div id="footer">
                <div class="left"><a href="<?php echo($this->Html->url('/')); ?>">Home</a> | 

                    <?php if (!isset($loggedInId)) { ?>
                        <a href="<?php echo($this->Html->url('/users/register')); ?>">Join uLink</a> |
                        <?php
                    } else {
                        if ($loggedInFacebookId > 0) {
                            echo $this->Html->link('Log Out |', '#', array('class' => 'login', 'onclick' => 'FB.Connect.logout(function() { document.location = "' . $this->Html->url('/users/logout/') . '"; }); return false;'));
                        } else {
                            ?>
                            <a href="<?php echo $this->Html->url('/users/logout'); ?>" class="login">Log Out</a> |
                            <?php
                        }
                    }
                    ?> 

                    <a href="<?php echo($this->Html->url('/pages/faq')); ?>">FAQ</a> | <a href="<?php echo($this->Html->url('/pages/legal')); ?>">Legal</a> | <a href="<?php echo($this->Html->url('/pages/advertise')); ?>">Advertise</a></div>
                <div class="right">&copy; 2012 uLink, Inc. All rights reserved.</div>
                <div class="clear"></div>
            </div>
        </div>
        <?php echo $this->element('add_school'); ?>
        <?php echo $this->element('writereview'); ?>

    </body>
</html>
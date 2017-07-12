<?php
/**
 * Plugin Name: Gallerio
 * Plugin URI: http://wordpress.org/plugins/gallerio/
 * Description: A simple yet powerful gallery plugin for Wordpress
 * Version: 1.0.1
 * Author: Subhasis Laha
 * Author URI: http://profiles.wordpress.org/subhasis005/
 * License: GPL3
 */

ob_start();

// Include phpThumb()
require_once('class/phpthumb.class.php');

// Add Menu Action
add_action('admin_menu', 'getgalleryaction');

function getgalleryaction(){
	add_menu_page( 'Gallerio', 'Gallerio', '0', 'welcome', 'welcome','dashicons-images-alt2');
	add_submenu_page( 'welcome', 'Gallery', 'Gallery', '0', 'gallerio', 'getgallerio' );
	add_submenu_page( 'hiddenpage', 'Upload Pictures', 'Upload Pictures', '0', 'upload-pictures', 'upload' );
	add_submenu_page( 'hiddenpage', 'Bulk Upload Pictures', 'Bulk Upload Pictures', '0', 'bulk-upload-pictures', 'bulkupload' );
	add_submenu_page( 'welcome', 'Settings', 'Settings', '0', 'settings', 'settings' );
	add_submenu_page( 'welcome', 'Usage Guide', 'Usage Guide', '0', 'guide', 'guide' );
	add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );
}

function registercolorbox(){
	
	wp_register_script( 'colorboxjs', plugins_url() . '/gallerio/js/jquery.colorbox.js','','',true );
	wp_register_style( 'colorboxcss', plugins_url() . '/gallerio/css/colorbox.css','','', 'screen' );
}

add_action( 'wp_enqueue_scripts', 'registercolorbox' );


function welcome(){
	
	ob_start();
	?>
	<style>
	#wpcontent{
		
		background:url(<?=plugins_url().'/gallerio/background.jpg'?>) no-repeat !important; 
		background-size:100% 100% !important; 
		background-position:center !important; 
		background-attachment:fixed !important;
		margin-left:160px;
	}
	.folded #wpcontent, .folded #wpfooter{
		margin-left:36px !important;
	}
	</style>
	
	<div style="width:100%; text-align:center;">
	
		<div style="font-size:60px; color:#FFFFFF; margin-top:120px; font-weight:bold; text-shadow:3px 3px 3px #333">Gallerio<span style="font-size:20px; vertical-align:super">&trade;</span></div>
		<div style="font-size:20px; color:#FFFFFF; margin-top:30px;; font-weight:bold; text-shadow:3px 3px 3px #333; font-style:italic; line-height:30px;">v 1.0.1</div>
		<div style="font-size:30px; color:#FFFFFF; margin-top:40px;; font-weight:bold; text-shadow:3px 3px 3px #333; font-style:italic; line-height:30px;">"A simple gallery plugin based on wordpress."</div>
		<div style="font-size:18px; color:#FFFFFF; margin-top:40px;; font-weight:bold; text-shadow:3px 3px 3px #333; font-style:italic; line-height:30px;">By Subhasis Laha</div>
		<div style="font-size:30px; color:#FFFFFF; margin-top:40px;; font-weight:bold; text-shadow:3px 3px 3px #333; font-style:italic; line-height:30px;">
			<input type="button" value="Get Started!" name="btnadd" class="button button-primary" style="padding:5px 20px; font-size:20px; height:50px;" onclick="location.href='?page=gallerio'" >
		</div>
	
	</div>
	
	<script>
	jQuery(document).ready(function(){
		
		var height = jQuery('#wpwrap').css('height');
		jQuery('#wpbody').css('height',height);
	});
	</script>
	
	<?php
}

function guide(){
	
	?>
	<style>
	.widefat td, .widefat th {
		vertical-align: top;
		padding:10px 10px !important;
	}
	.dashicons{
		cursor:pointer;
	}
	.widefat th input[type="checkbox"] {
		margin-left: 0 !important;
	}
	</style>
	
	
	<div style="height:150px; background:url(<?=plugins_url().'/gallerio/background.jpg'?>) no-repeat; margin-left:-20px;" >
		
		<div style="font-size:40px; color:#FFFFFF; font-weight:bold; text-shadow:3px 3px 3px #333; float:left; line-height:100px; margin-left:20px;">Gallerio<span style="font-size:20px; vertical-align:super">&trade;</span></div>
		
		<div style="font-size:20px; color:#FFFFFF; font-weight:bold; text-shadow:3px 3px 3px #333; font-style:italic; line-height:130px; float:right; margin-right:20px;">v 1.0.1</div>
	
	</div>
	
	<div style="padding:20px 10px; background:#e0e0e0; color:#0074a2; margin-top:20px; font-size:13px; font-weight:bold">
		
		<?php
		echo $file = nl2br(file_get_contents(plugins_url().'/gallerio/readme.txt', true));
		?>
		
	</div>
	
	<?php
}

/********************************************* Gallery Listing Admin ***********************************************/

function getgallerio(){
	
	?>
	<style>
	.widefat td, .widefat th {
		vertical-align: top;
		padding:10px 10px !important;
	}
	.dashicons{
		cursor:pointer;
	}
	.widefat th input[type="checkbox"] {
		margin-left: 0 !important;
	}
	.shortcode{
		background:#DFDFDF;
		color:#333333;
		font-family:"Courier New", Courier, monospace;
		font-size:14px;
		padding:3px;
	}
	</style>
	
	<div style="height:150px; background:url(<?=plugins_url().'/gallerio/background.jpg'?>) no-repeat; margin-left:-20px;" >
		
		<div style="font-size:40px; color:#FFFFFF; font-weight:bold; text-shadow:3px 3px 3px #333; float:left; line-height:100px; margin-left:20px;">Gallerio<span style="font-size:20px; vertical-align:super">&trade;</span></div>
		
		<div style="font-size:20px; color:#FFFFFF; font-weight:bold; text-shadow:3px 3px 3px #333; font-style:italic; line-height:130px; float:right; margin-right:20px;">v 1.0.1</div>
	
	</div>
	
	<?php
	
	global $wpdb;
	
	$uploaddir = wp_upload_dir();
	
	
	$getdata = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."gallerio_config" );
	
	$config = array();
	
	foreach($getdata as $valconfig){
		
		$config[$valconfig->config_type] = $valconfig->config_val;
	}
	
			
	if(isset($_POST['doadd']) && $_POST['doadd'] == 'yes'){
	  
		 $insert = "INSERT INTO ".$wpdb->prefix."gallerio SET
					gallery = '".addslashes($_POST['gallery'])."',
					gallery_desc = '".addslashes($_POST['gallery_desc'])."',
					date_created = '".date('d-m-Y')."'";
				   
		$wpdb->query($insert);
		
		$action = 'added';
		
	  }
	  
	  if(isset($_POST['doedit']) && $_POST['doedit'] == 'yes'){
	  
		 $update = "UPDATE ".$wpdb->prefix."gallerio SET
					gallery = '".addslashes($_POST['gallery'])."',
					gallery_desc = '".addslashes($_POST['gallery_desc'])."'
					WHERE id = '".$_POST['id']."'";
					
		 $wpdb->query($update);
		 
		 $action = 'updated';
		 
	  }
	  
	  if(isset($_POST['dodel']) && $_POST['dodel'] == 'yes'){
	  
		 $getgal = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."gallerio_images WHERE gallery_id = '".$_POST['id']."'");
		 
		 foreach($getgal as $valgal){
		 	
			 $getimage = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."gallerio_images WHERE id = '".$valgal->id."'");
		 		 
			 @unlink($uploaddir['basedir'].'/gallerio/'.$valgal->gallery_pic_small);
			 @unlink($uploaddir['basedir'].'/gallerio/'.$valgal->gallery_pic_big);
			 @unlink($uploaddir['basedir'].'/gallerio/'.$valgal->gallery_pic_large);
			 
			 $del = "DELETE FROM ".$wpdb->prefix."gallerio_images WHERE id = '".$valgal->id."'";
						
			 $wpdb->query($del);
		 }
		 
		 $del = "DELETE FROM ".$wpdb->prefix."gallerio WHERE id = '".$_POST['id']."'";
					
		 $wpdb->query($del);
		 
		 $action = 'deleted';
		 
	  }
	  
	  if(isset($_POST['delsel']) && $_POST['delsel'] == 'yes'){
	  
		 $getids = $_POST['chk_id'];
	
		 foreach($getids as $valids)
		 {
			 $getgal = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."gallerio_images WHERE gallery_id = '".$valids."'");
		 
			 foreach($getgal as $valgal){
				
				 $getimage = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."gallerio_images WHERE id = '".$valgal->id."'");
					 
				 @unlink($uploaddir['basedir'].'/gallerio/'.$valgal->gallery_pic_small);
				 @unlink($uploaddir['basedir'].'/gallerio/'.$valgal->gallery_pic_big);
				 @unlink($uploaddir['basedir'].'/gallerio/'.$valgal->gallery_pic_large);
				 
				 $del = "DELETE FROM ".$wpdb->prefix."gallerio_images WHERE id = '".$valgal->id."'";
							
				 $wpdb->query($del);
			 }
			
			$del = "DELETE FROM ".$wpdb->prefix."gallerio WHERE id = '".$valids."'";
					
		 	$wpdb->query($del);
		 }
		 
		 $action = 'deleted';
		 
	  }
	  

	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	$limit = 5; // number of rows in page
	$offset = ( $pagenum - 1 ) * $limit;
	$total = $wpdb->get_var( "SELECT COUNT(`id`) FROM ".$wpdb->prefix."gallerio" );
	$num_of_pages = ceil( $total / $limit );
	
	$gallerio = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."gallerio ORDER BY id DESC LIMIT $offset, $limit" );
	
	/*print "<pre>";
	print_r($gallerio_subscribers);
	die();*/
	
	?>
	
	<form name="frmhidden" method="post" action="">
		<input type="hidden" name="mode" value="" />
		<input type="hidden" name="id" value="" />
	</form>
	
	<div class="wrap">
	
	<?php
	if($_POST['mode'] == 'add' || $_POST['mode'] == 'edit'){
		
		$getdata = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."gallerio WHERE id = '".$_POST['id']."'" );
		
		/*print "<pre>";
		print_r($getdata);*/
		?>
	
		<h2><?=ucfirst($_POST['mode'])?> Gallery</h2>
		
		<form name="frmhidden" method="post" action="">
			<input type="hidden" name="do<?=$_POST['mode']?>" value="yes" />
			<input type="hidden" name="id" value="<?=$_POST['id']?>" />
			
			<table class="form-table">
			
			  <tbody>
				<tr>
					<th scope="row"><label for="gallery">Name</label></th>
					<td><input name="gallery" type="text" id="gallery" value="<?=$getdata->gallery?>" class="regular-text" required></td>
				</tr>
				<tr>
					<th scope="row"><label for="gallery_desc">Description</label></th>
					<td><?=wp_editor( stripslashes($getdata->gallery_desc), 'gallery_desc' );?></td>
				</tr>
				<tr>
					<th scope="row">&nbsp;</th>
					<td>
						<input type="submit" value="<?=$_POST['mode'] == 'add' ? 'Add' : 'Update'?>" name="btnadd" class="button button-primary" >
						<input type="button" value="Cancel" name="btnadd" class="button button-primary" onclick="location.href='<?=site_url().'/'?>wp-admin/admin.php?page=gallerio'" >
					</td>
				</tr>
			  </tbody>
			  
			</table>
		</form>
		<?php
	}
	else{
	?>
	
	<h2>List of Gallery</h2>
	
	<br />
	
	<div><a class="add-new-h2" style="cursor:pointer; margin-left:0" onclick="add()">Add New</a>&nbsp;<a class="add-new-h2" style="cursor:pointer" onclick="delsel()">Delete Selected</a></div>
	
	<br />
	
	<?php
	if($action == 'added' || $action == 'updated' || $action == 'deleted' || $action == 'sent'){
		
		echo '<div id="message" class="updated below-h2"><p>Gallery '.$action.'</p></div>';
	}
	?>
	
	<form action="" method="post" id="formlist" name="formlist">
	<input type="hidden" name="dodel" value="" />
	<input type="hidden" name="dosend" value="" />
	<input type="hidden" name="delsel" value="" />
	<input type="hidden" name="id" value="" />
		
	<div style="clear:both"></div>
	
	<!--<ul class="subsubsub">
		<li class="all"><a class="current" href="edit.php?post_type=page">All <span class="count">(2)</span></a> |</li>
		<li class="publish"><a href="edit.php?post_status=publish&amp;post_type=page">Published <span class="count">(2)</span></a></li>
	</ul>-->
	
	<table width="60%" border="0" class="wp-list-table widefat">
	  <thead>
	  <tr>
	  	<th style="" scope="col"><input type="checkbox" value="" id="chkall" onclick="checkitem(this.checked)"></th>
		<th style="" scope="col">#</th>
		<th style="" scope="col">ID</th>
		<th style="" scope="col">Cover</th>
		<th style="" scope="col">Gallery Title</th>
		<th style="" scope="col">Date Created</th>
		<th style="" scope="col">No of Pictures</th>
		<th style="" scope="col">Shortcode</th>
		<th style="" scope="col">Actions</th>
	  </tr>
	  </thead>
	  
	  <tfoot>
	  <tr>
		<th style="" scope="col"><input type="checkbox" value="" id="chkall" onclick="checkitem(this.checked)"></th>
		<th style="" scope="col">#</th>
		<th style="" scope="col">ID</th>
		<th style="" scope="col">Cover</th>
		<th style="" scope="col">Gallery Title</th>
		<th style="" scope="col">Date Created</th>
		<th style="" scope="col">No of Pictures</th>
		<th style="" scope="col">Shortcode</th>
		<th style="" scope="col">Actions</th>
	  </tr>
	  </tfoot>
	  
	  <tbody id="the-list">
	  
	  <?php
	  $sl = 1;
	  
	  if(count($gallerio)){
		  foreach($gallerio as $val){
		  $alt = $sl%2;
		  
		  //echo plugins_url();
		  
		  $getpicture = "SELECT * FROM ".$wpdb->prefix."gallerio_images WHERE gallery_id = '".$val->id."' ORDER BY id LIMIT 1";
		  $getpicture = $wpdb->get_row($getpicture);
		  $pic = $getpicture->gallery_pic_small;
		  
		  if($pic == ''){
		  	
			  $pic = plugins_url().'/gallerio/no_img.png';
		  }
		  else if(!is_file($uploaddir['basedir'].'/gallerio/'.$pic)){
		  	  
			  $pic = plugins_url().'/gallerio/no_img.png';
		  }
		  else{
		  	
			  $pic = $uploaddir['baseurl'].'/gallerio/'.$pic;
		  }
		  
		  $noofpics = "SELECT * FROM ".$wpdb->prefix."gallerio_images WHERE gallery_id = '".$val->id."'";
		  $noofpics = $wpdb->get_results($noofpics);
		  $noofpics = $wpdb->num_rows;
		  
		  
		  ?>
		  <tr class="post-4 type-page status-publish hentry <?=$alt == 0 ? '' : 'alternate'?> iedit author-self level-0">
			<td><input type="checkbox" value="<?=$val->id?>" name="chk_id[]"></td>
			<td><?=$sl?></td>
			<td><?=$val->id?></td>
			<td>
				<div style="width:<?=$config['gallerio_canvas_width']?>px; height:<?=$config['gallerio_canvas_height']?>px; background:url(<?=$pic?>) center center no-repeat; background-size:100% auto; border:1px solid #CCCCCC; box-shadow:5px 5px 5px #CCC; border-radius:10px"></div>
			</td>
			<td><?=$val->gallery?></td>
			<td><?=date('dS M Y',strtotime($val->date_created))?></td>
			<td><?=$noofpics?></td>
			<td><span class="shortcode"><?='[gallery id="'.$val->id.'"]'?></span></td>
			<td>
			
				<div class="dashicons dashicons-camera" data-code="f466" onclick="upload('<?=$val->id?>')" title="Upload Pictures"></div>
				<div class="dashicons dashicons-edit" data-code="f464" onclick="edit('<?=$val->id?>')" title="Edit"></div>
				<div class="dashicons dashicons-no" data-code="f158" onclick="del('<?=$val->id?>')" title="Delete"></div>
				
			</td>
		  </tr>	
		  <?php
		  $sl++;
		  }
	  }
	  else{
	  	?>
		<tr>
			<td colspan="8" align="center">No Record(s)</td>
		</tr>
		<?php
	  }
	  ?>
	  </tbody>
	
	</table>
	
	</form>
	
	<?php
	
	$page_links = paginate_links( array(
		'base' => add_query_arg( 'pagenum', '%#%' ),
		'format' => '',
		'prev_text' => __( '&laquo;', 'text-domain' ),
		'next_text' => __( '&raquo;', 'text-domain' ),
		'total' => $num_of_pages,
		'current' => $pagenum
	) );
	
	if ( $page_links ) {
		echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
	}
	
	}
	?>
	
	<script>
	function add(){
		
		document.frmhidden.mode.value = 'add';
		document.frmhidden.submit();
	}
	function edit(id){
		
		document.frmhidden.mode.value = 'edit';
		document.frmhidden.id.value = id;
		document.frmhidden.submit();
	}
	function del(id){
		
		var cnf = confirm('Are you sure?');
		if(cnf){
			document.formlist.dodel.value = 'yes';
			document.formlist.id.value = id;
			document.formlist.submit();
		}
	}
	function upload(id){
		
		location.href = '<?=site_url().'/'?>wp-admin/admin.php?page=upload-pictures&gallery_id='+id;
	}
	function checkitem(checked){
	
		var element = document.getElementsByName('chk_id[]');
		ln = element.length;
		
		var stat;
		
		if(checked)		stat = 'checked';
		else			stat = '';
		
		for(i=0;i<ln;i++){
			
			element[i].checked = checked;
		}
	}
	
	function delsel(){
		
		var element = document.getElementsByName('chk_id[]');
		ln = element.length;
		
		var flag = 0;
			
		for(i=0;i<ln;i++){
			
			//alert(element[i].checked);
			
			if(element[i].checked){
				
				flag = 1;
				break;
			}
		}
		
		if(flag == 0){
			
			alert('You must select atleast one item');
		}
		else{
			
			var cnf = confirm('Are you sure?');
			if(cnf){
				
				document.formlist.delsel.value = 'yes';
				document.formlist.submit();
			}
			
		}
	}
	</script>
	
	</div>
	<?php
}

/********************************************* Gallery Listing Admin ***********************************************/



/****************************************** Upload Pictures to Gallery *********************************************/

function upload(){
	
	?>
	<style>
	.widefat td, .widefat th {
		vertical-align: top;
		padding:10px 10px !important;
	}
	.dashicons{
		cursor:pointer;
	}
	.widefat th input[type="checkbox"] {
		margin-left: 0 !important;
	}
	.progress { position:relative; width:auto; border: 0px solid #ddd; padding: 1px; border-radius: 3px; display:none; }
	.bar { width:100%; height:30px; border-radius: 3px; margin-top:8px; background-color:#7AD03A; }
	.percent { position:absolute; display:inline-block; top:14px; left:1%; color:#fff; }
	</style>
	
		
	<?php
	wp_enqueue_script( 'jquery-form' );
	?>
	
	<div style="height:150px; background:url(<?=plugins_url().'/gallerio/background.jpg'?>) no-repeat; margin-left:-20px;" >
		
		<div style="font-size:40px; color:#FFFFFF; font-weight:bold; text-shadow:3px 3px 3px #333; float:left; line-height:100px; margin-left:20px;">Gallerio<span style="font-size:20px; vertical-align:super">&trade;</span></div>
		
		<div style="font-size:20px; color:#FFFFFF; font-weight:bold; text-shadow:3px 3px 3px #333; font-style:italic; line-height:130px; float:right; margin-right:20px;">v 1.0.1</div>
	
	</div>
	
		
	<?php
	
	global $wpdb;
	
	$uploaddir = wp_upload_dir();
	
	
	$getdata = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."gallerio_config" );
	
	$config = array();
	
	foreach($getdata as $valconfig){
		
		$config[$valconfig->config_type] = $valconfig->config_val;
	}
	
	
	$getgal = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."gallerio WHERE id = '".$_REQUEST['gallery_id']."'" );
			
	if(isset($_POST['doadd']) && $_POST['doadd'] == 'yes'){
	  
		 if($_FILES['pic']['name'] != ""){
			
			$fileext = end(explode(".",$_FILES['pic']['name']));
			
			$filename = rand(0,9999).time();
			
			$directory = 'gallerio';
			
			@mkdir($uploaddir['basedir'].'/'.$directory,0777);
			@chmod($uploaddir['basedir'].'/'.$directory,0777);
			
			$filepath = $uploaddir['basedir'].'/'.$directory.'/'.$filename.'.'.$fileext;
			
			move_uploaded_file($_FILES['pic']['tmp_name'],$filepath);
			
			$filepath_small = $uploaddir['basedir'].'/'.$directory.'/'.$filename.'_small.'.$fileext;
			$filepath_medium = $uploaddir['basedir'].'/'.$directory.'/'.$filename.'_medium.'.$fileext;
			
			createthmb($filepath,$filepath_small,$config['gallerio_thumb_small_width'],$config['gallerio_thumb_small_height'],$config['thumbnail_aspect_ratio']);
			createthmb($filepath,$filepath_medium,$config['gallerio_thumb_big_width'],$config['gallerio_thumb_big_height'],$config['picture_aspect_ratio']);
		}
		 
		 $cntgal = $wpdb->get_row( "SELECT COUNT(*) AS num FROM ".$wpdb->prefix."gallerio_images WHERE gallery_id = '".$_REQUEST['gallery_id']."'" );
		 
		 $imagetitle = initials(stripslashes($getgal->gallery)).($cntgal->num+1);
		 
		 $insert = "INSERT INTO ".$wpdb->prefix."gallerio_images SET
					gallery_id = '".$_REQUEST['gallery_id']."',
					title = '".$imagetitle."',
					alt = '".$imagetitle."',
					gallery_pic_small = '".$filename.'_small.'.$fileext."',
					gallery_pic_big = '".$filename.'_medium.'.$fileext."',
					gallery_pic_large = '".$filename.'.'.$fileext."'";
				   
		$wpdb->query($insert);
		
		$action = 'added';
		
	  }
	  
	  if(isset($_POST['doedit']) && $_POST['doedit'] == 'yes'){
	  	 
		 $getimage = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."gallerio_images WHERE id = '".$_POST['id']."'");
		  
		 if($_FILES['pic']['name'] != ""){
			
			$fileext = end(explode(".",$_FILES['pic']['name']));
			
			$filename = rand(0,9999).time();
			
			$directory = 'gallerio';
			
			@mkdir($uploaddir['basedir'].'/'.$directory,0777);
			@chmod($uploaddir['basedir'].'/'.$directory,0777);
			
			$filepath = $uploaddir['basedir'].'/'.$directory.'/'.$filename.'.'.$fileext;
			
			move_uploaded_file($_FILES['pic']['tmp_name'],$filepath);
			
			$filepath_small = $uploaddir['basedir'].'/'.$directory.'/'.$filename.'_small.'.$fileext;
			$filepath_medium = $uploaddir['basedir'].'/'.$directory.'/'.$filename.'_medium.'.$fileext;
			
			createthmb($filepath,$filepath_small,$config['gallerio_thumb_small_width'],$config['gallerio_thumb_small_height'],$config['thumbnail_aspect_ratio']);
			createthmb($filepath,$filepath_medium,$config['gallerio_thumb_big_width'],$config['gallerio_thumb_big_height'],$config['picture_aspect_ratio']);
			
			
			$filepath_small = $filename.'_small.'.$fileext;
			$filepath_medium = $filename.'_medium.'.$fileext;
			$filepath = $filename.'.'.$fileext;
			
			
			@unlink($uploaddir['basedir'].'/gallerio/'.$getimage->gallery_pic_small);
			@unlink($uploaddir['basedir'].'/gallerio/'.$getimage->gallery_pic_big);
			@unlink($uploaddir['basedir'].'/gallerio/'.$getimage->gallery_pic_large);
		}
		else{
			
			$filepath_small = $getimage->gallery_pic_small;
			$filepath_medium = $getimage->gallery_pic_big;
			$filepath = $getimage->gallery_pic_large;
		}
		 
		 $update = "UPDATE ".$wpdb->prefix."gallerio_images SET
					gallery_pic_small = '".$filepath_small."',
					gallery_pic_big = '".$filepath_medium."',
					gallery_pic_large = '".$filepath."'
					WHERE id = '".$_POST['id']."'";
					
		 $wpdb->query($update);
		 
		 $action = 'updated';
		 
	  }
	  
	  if(isset($_POST['dodel']) && $_POST['dodel'] == 'yes'){
	  
		 $getimage = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."gallerio_images WHERE id = '".$_POST['id']."'");
		 		 
		 @unlink($uploaddir['basedir'].'/gallerio/'.$getimage->gallery_pic_small);
		 @unlink($uploaddir['basedir'].'/gallerio/'.$getimage->gallery_pic_big);
		 @unlink($uploaddir['basedir'].'/gallerio/'.$getimage->gallery_pic_large);
		 
		 $del = "DELETE FROM ".$wpdb->prefix."gallerio_images WHERE id = '".$_POST['id']."'";
					
		 $wpdb->query($del);
		 
		 $action = 'deleted';
		 
	  }
	  
	  if(isset($_POST['delsel']) && $_POST['delsel'] == 'yes'){
	  
		 $getids = $_POST['chk_id'];
	
		 foreach($getids as $valids)
		 {
			 $getimage = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."gallerio_images WHERE id = '".$valids."'");
		 
			 @unlink($uploaddir['basedir'].'/gallerio/'.$getimage->gallery_pic_small);
			 @unlink($uploaddir['basedir'].'/gallerio/'.$getimage->gallery_pic_big);
			 @unlink($uploaddir['basedir'].'/gallerio/'.$getimage->gallery_pic_large);
			
			$del = "DELETE FROM ".$wpdb->prefix."gallerio_images WHERE id = '".$valids."'";
					
		 	$wpdb->query($del);
		 }
		 
		 $action = 'deleted';
		 
	  }
	  
	  
	  if(isset($_POST['dosave']) && $_POST['dosave'] == 'yes'){
	  
		 $getgalimages = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."gallerio_images WHERE gallery_id = '".$_POST['gallery_id']."'");
		 
		 /*print "<pre>";
		 print_r($getgalimages);
		 
		 die();*/
		 
		 if(count($getgalimages)){
		 	
			 foreach($getgalimages as $valimages){
			 	
				 $update = "UPDATE ".$wpdb->prefix."gallerio_images SET
				 			title = '".$_POST['title_'.$valimages->id]."',
							alt = '".$_POST['alt_'.$valimages->id]."',
							link = '".$_POST['link_'.$valimages->id]."'
							WHERE id = '".$valimages->id."'";
							
				$wpdb->query($update);
			 }
		 }
		 
		 $action = 'saved';
		 
	  }
	  
	
	  $gallerio = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."gallerio_images WHERE gallery_id = '".$_REQUEST['gallery_id']."'" );
	
	  /*print "<pre>";
	  print_r($gallerio);
	  die();*/
	
	?>
	
	
	<form name="frmhidden" method="post" action="">
		<input type="hidden" name="mode" value="" />
		<input type="hidden" name="id" value="" />
		<input type="hidden" name="gallery_id" value="<?=$_REQUEST['gallery_id']?>" />
	</form>
	
	<div class="wrap">
	
	<?php
	if($_POST['mode'] == 'add' || $_POST['mode'] == 'edit'){
		
		$getdata = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."gallerio_images WHERE id = '".$_POST['id']."'" );
		
		/*print "<pre>";
		print_r($getdata);*/
		
		$pic = $getdata->gallery_pic_small;
		  
		if($pic == ''){
		
		  $pic = plugins_url().'/gallerio/no_img.png';
		}
		else if(!is_file($uploaddir['basedir'].'/gallerio/'.$pic)){
		  
		  $pic = plugins_url().'/gallerio/no_img.png';
		}
		else{
		
		  $pic = $uploaddir['baseurl'].'/gallerio/'.$pic;
		}		
		?>
	
		<h2><?=ucfirst($_POST['mode'])?> Image</h2>
		
		<form name="frmhidden" method="post" action="" enctype="multipart/form-data">
			<input type="hidden" name="do<?=$_POST['mode']?>" value="yes" />
			<input type="hidden" name="id" value="<?=$_POST['id']?>" />
			<input type="hidden" name="gallery_id" value="<?=$_POST['gallery_id']?>" />
			
			<table class="form-table">
			
			  <tbody>
				
				<?php
				if($_POST['mode'] == 'edit'){
				?>
				<tr>
					<th scope="row"><label for="gallery">Existing Image</label></th>
					<td><img src="<?=$pic?>" /></td>
				</tr>
				<?php
				}
				?>
				<tr>
					<th scope="row"><label for="gallery">Choose Image: </label></th>
					<td><input name="pic" type="file" required></td>
				</tr>
				<tr>
					<th scope="row">&nbsp;</th>
					<td>
						<input type="submit" value="<?=$_POST['mode'] == 'add' ? 'Add' : 'Update'?>" name="btnadd" class="button button-primary" >
						<input type="button" value="Cancel" name="btnadd" class="button button-primary" onclick="location.href='<?=site_url().'/'?>wp-admin/admin.php?page=upload-pictures&gallery_id=<?=$_POST['gallery_id']?>'" >
					</td>
				</tr>
			  </tbody>
			  
			</table>
		</form>
		<?php
	}
	else{
	?>
	
	<h2>Upload Pictures in "<strong style="font-size:30px;"><?=$getgal->gallery?></strong>"</h2>
	
	<div style="margin:20px 0">
		<a class="add-new-h2" style="cursor:pointer" onclick="add()">Add New</a>&nbsp;
		<a class="add-new-h2" style="cursor:pointer" onclick="delsel()">Delete Selected</a>&nbsp;
		<a class="add-new-h2" href="<?=site_url().'/'?>wp-admin/admin.php?page=gallerio" >&laquo;&nbsp;Back</a>
	</div>
	
	<div style="margin:20px 0">
		<a class="add-new-h2" style="cursor:pointer" onclick="jQuery('#bulkfileselector').toggle('fast')">Bulk Upload</a>&nbsp;
		
		<div id="bulkfileselector" style="display:none; margin:10px 0">
		
			<form action="<?=site_url().'/'?>wp-admin/admin.php?page=bulk-upload-pictures" method="post" enctype="multipart/form-data" id="formbulk">
				<input type="hidden" name="bulkadd" value="yes" />
				<input type="file" name="bulkfile[]" multiple />
				<input type="hidden" name="gallery_id" value="<?=$_REQUEST['gallery_id']?>" />
				<input type="submit" name="btnsubmit" value="Upload"  class="button button-primary" />
			</form>
			
			<div style="clear:both"></div>
		
			<div class="progress">
				<div class="bar"></div >
				<div class="percent">0%</div >
			</div>
			
			<div id="status"></div>
			
			<div style="clear:both"></div>
			
			<div style="background:#e0e0e0; padding:10px; border-radius:5px; margin-top:10px;"><strong>Note:</strong>
				
				<ol>
					<li>Maximum no of pictures you can upload once are: <strong><?=ini_get('max_file_uploads')?></strong></li>
					<li>Server maximum upload limit is: <strong><?=ini_get('post_max_size')?></strong></li>
				</ol>
			
			</div>
			
		</div>
		
	</div>
	
	<?php
	if($action == 'added' || $action == 'updated' || $action == 'deleted' || $action == 'saved'){
		
		echo '<div id="message" class="updated below-h2"><p>Picture(s) '.$action.'</p></div>';
	}
	?>
	
	<form action="" method="post" id="formlist" name="formlist" onsubmit="document.formlist.dosave.value = 'yes'">
	<input type="hidden" name="dodel" value="" />
	<input type="hidden" name="dosave" value="" />
	<input type="hidden" name="delsel" value="" />
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="gallery_id" value="<?=$_REQUEST['gallery_id']?>" />
		
	<div style="clear:both"></div>
	
	<!--<ul class="subsubsub">
		<li class="all"><a class="current" href="edit.php?post_type=page">All <span class="count">(2)</span></a> |</li>
		<li class="publish"><a href="edit.php?post_status=publish&amp;post_type=page">Published <span class="count">(2)</span></a></li>
	</ul>-->
	
	<table width="60%" border="0" class="wp-list-table widefat">
	  <thead>
	  <tr>
	  	<th style="" scope="col"><input type="checkbox" value="" id="chkall" onclick="checkitem(this.checked)"></th>
		<th style="" scope="col">Sl No.</th>
		<th style="" scope="col">Image</th>
		<th style="" scope="col">Title</th>
		<th style="" scope="col">Alt</th>
		<th style="" scope="col">Link</th>
		<th style="" scope="col">Actions</th>
	  </tr>
	  </thead>
	  
	  <tfoot>
	  <tr>
		<th style="" scope="col"><input type="checkbox" value="" id="chkall" onclick="checkitem(this.checked)"></th>
		<th style="" scope="col">Sl No.</th>
		<th style="" scope="col">Image</th>
		<th style="" scope="col">Title</th>
		<th style="" scope="col">Alt</th>
		<th style="" scope="col">Link</th>
		<th style="" scope="col">Actions</th>
	  </tr>
	  </tfoot>
	  
	  <tbody id="the-list">
	  
	  <?php
	  $sl = 1;
	  
	  if(count($gallerio)){
		  foreach($gallerio as $val){
		  $alt = $sl%2;
		  
		  $pic = $val->gallery_pic_small;
		  
		  if($pic == ''){
		  	
			  $pic = plugins_url().'/gallerio/no_img.png';
		  }
		  else if(!is_file($uploaddir['basedir'].'/gallerio/'.$pic)){
		  	  
			  $pic = plugins_url().'/gallerio/no_img.png';
		  }
		  else{
		  	
			  $pic = $uploaddir['baseurl'].'/gallerio/'.$pic;
		  }
		  
		  ?>
		  <tr class="post-4 type-page status-publish hentry <?=$alt == 0 ? '' : 'alternate'?> iedit author-self level-0">
			<td><input type="checkbox" value="<?=$val->id?>" name="chk_id[]"></td>
			<td><?=$sl?></td>
			<td><div style="width:<?=$config['gallerio_canvas_width']?>px; height:<?=$config['gallerio_canvas_height']?>px; background:url(<?=$pic?>) center center no-repeat; background-size:100% auto; border:1px solid #CCCCCC; box-shadow:5px 5px 5px #CCC; border-radius:10px"></div></td>
			<td><input type="text" name="title_<?=$val->id?>" value="<?=$val->title?>" /></td>
			<td><input type="text" name="alt_<?=$val->id?>" value="<?=$val->alt?>" /></td>
			<td><input type="text" name="link_<?=$val->id?>" value="<?=$val->link?>" /></td>
			<td>
			
				<div class="dashicons dashicons-edit" data-code="f464" onclick="edit('<?=$val->id?>')" title="Edit"></div>
				<div class="dashicons dashicons-no" data-code="f158" onclick="del('<?=$val->id?>')" title="Delete"></div>
				
			</td>
		  </tr>	
		  <?php
		  $sl++;
		  }
	  }
	  else{
	  	?>
		<tr>
			<td colspan="7" align="center">No Record(s)</td>
		</tr>
		<?php
	  }
	  ?>
	  </tbody>
	
	</table>
	
	<div style="margin-top:20px; float:right">
	<input type="submit" value="Save" name="btnadd" class="button button-primary" >
	<input type="button" value="Cancel" name="btnadd" class="button button-primary" onclick="location.href='<?=site_url().'/'?>wp-admin/admin.php?page=gallerio'" />
	</div>
	
	</form>
	
	<?php
	}
	?>
	
	<script>
	
	function add(){
		
		document.frmhidden.mode.value = 'add';
		document.frmhidden.submit();
	}
	function edit(id){
		
		document.frmhidden.mode.value = 'edit';
		document.frmhidden.id.value = id;
		document.frmhidden.submit();
	}
	function del(id){
		
		var cnf = confirm('Are you sure?');
		if(cnf){
			document.formlist.dodel.value = 'yes';
			document.formlist.id.value = id;
			document.formlist.submit();
		}
	}
	function send(id){
		
		var cnf = confirm('Are you sure?');
		if(cnf){
			document.formlist.dosend.value = 'yes';
			document.formlist.id.value = id;
			document.formlist.submit();
		}
	}
	function checkitem(checked){
	
		var element = document.getElementsByName('chk_id[]');
		ln = element.length;
		
		var stat;
		
		if(checked)		stat = 'checked';
		else			stat = '';
		
		for(i=0;i<ln;i++){
			
			element[i].checked = checked;
		}
	}
	
	function delsel(){
		
		var element = document.getElementsByName('chk_id[]');
		ln = element.length;
		
		var flag = 0;
			
		for(i=0;i<ln;i++){
			
			//alert(element[i].checked);
			
			if(element[i].checked){
				
				flag = 1;
				break;
			}
		}
		
		if(flag == 0){
			
			alert('You must select atleast one item');
		}
		else{
			
			var cnf = confirm('Are you sure?');
			if(cnf){
				
				document.formlist.delsel.value = 'yes';
				document.formlist.submit();
			}
			
		}
	}
	
	jQuery(document).ready(function($){
		
		var bar = $('.bar');
		var percent = $('.percent');
		var status = $('#status');
		   
		$('#formbulk').ajaxForm({
			beforeSend: function() {
				status.empty();
				var percentVal = '0%';
				bar.width(percentVal)
				percent.html(percentVal);
				$('.progress').show();
			},
			uploadProgress: function(event, position, total, percentComplete) {
				var percentVal = percentComplete + '%';
				bar.width(percentVal)
				percent.html(percentVal);
				if(percentComplete == 100){
					setTimeout(function(){
						percent.html('Please wait...');
					},2000);
				}
				//console.log(percentVal, position, total);
			},
			success: function() {
				var percentVal = 'Done';
				bar.width(percentVal)
				percent.html(percentVal);
			},
			complete: function(xhr) {
				location.href='<?=site_url().'/'?>wp-admin/admin.php?page=upload-pictures&gallery_id=<?=$_REQUEST['gallery_id']?>';
			}
		}); 
	
	});       
	</script>
	
	</div>
	<?php
}

function bulkupload(){
	
	set_time_limit(0);

	global $wpdb;
		
	$uploaddir = wp_upload_dir();
	
	
	$getdata = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."gallerio_config" );
		
	$config = array();
	
	foreach($getdata as $valconfig){
		
		$config[$valconfig->config_type] = $valconfig->config_val;
	}
	
	
	$getgal = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."gallerio WHERE id = '".$_REQUEST['gallery_id']."'" );
	
	
	if(isset($_POST['bulkadd']) && $_POST['bulkadd'] != "")
	{
		/*print "<pre>";
		print_r($_FILES['photogallery']['name']);
		die();*/
		
		$cnt = count($_FILES['bulkfile']['name']);
			
		if($cnt){
		
			for($i=0;$i<$cnt;$i++){
			
				if($_FILES['bulkfile']['name'][$i] != "")
				{
					$fileext = end(explode(".",$_FILES['bulkfile']['name'][$i]));
				
					$filename = rand(0,9999).time();
					
					$directory = 'gallerio';
					
					@mkdir($uploaddir['basedir'].'/'.$directory,0777);
					@chmod($uploaddir['basedir'].'/'.$directory,0777);
					
					$filepath = $uploaddir['basedir'].'/'.$directory.'/'.$filename.'.'.$fileext;
					
					move_uploaded_file($_FILES['bulkfile']['tmp_name'][$i],$filepath);
					
					$filepath_small = $uploaddir['basedir'].'/'.$directory.'/'.$filename.'_small.'.$fileext;
					$filepath_medium = $uploaddir['basedir'].'/'.$directory.'/'.$filename.'_medium.'.$fileext;
					
					createthmb($filepath,$filepath_small,$config['gallerio_thumb_small_width'],$config['gallerio_thumb_small_height'],$config['thumbnail_aspect_ratio']);
					createthmb($filepath,$filepath_medium,$config['gallerio_thumb_big_width'],$config['gallerio_thumb_big_height'],$config['picture_aspect_ratio']);
					
					
					$cntgal = $wpdb->get_row( "SELECT COUNT(*) AS num FROM ".$wpdb->prefix."gallerio_images WHERE gallery_id = '".$_REQUEST['gallery_id']."'" );
			 
					$imagetitle = initials(stripslashes($getgal->gallery)).($cntgal->num+1);
			 
					
					$insert = "INSERT INTO ".$wpdb->prefix."gallerio_images SET
								gallery_id = '".$_REQUEST['gallery_id']."',
								title = '".$imagetitle."',
								alt = '".$imagetitle."',
								gallery_pic_small = '".$filename.'_small.'.$fileext."',
								gallery_pic_big = '".$filename.'_medium.'.$fileext."',
								gallery_pic_large = '".$filename.'.'.$fileext."'";
							   
					$wpdb->query($insert);
				}
			}
			
			echo 'success';
		}
	}
}

/****************************************** Upload Pictures to Gallery *********************************************/



/********************************************* Settings ***********************************************/

function settings(){
	
	?>
	<style>
	.widefat td, .widefat th {
		vertical-align: top;
		padding:10px 10px !important;
	}
	.dashicons{
		cursor:pointer;
	}
	.widefat th input[type="checkbox"] {
		margin-left: 0 !important;
	}
	</style>
	
	
	<div style="height:150px; background:url(<?=plugins_url().'/gallerio/background.jpg'?>) no-repeat; margin-left:-20px;" >
		
		<div style="font-size:40px; color:#FFFFFF; font-weight:bold; text-shadow:3px 3px 3px #333; float:left; line-height:100px; margin-left:20px;">Gallerio<span style="font-size:20px; vertical-align:super">&trade;</span></div>
		
		<div style="font-size:20px; color:#FFFFFF; font-weight:bold; text-shadow:3px 3px 3px #333; font-style:italic; line-height:130px; float:right; margin-right:20px;">v 1.0.1</div>
	
	</div>
	
	
	<?php
	
	global $wpdb;
	
	$uploaddir = wp_upload_dir();
	
	$getdata = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."gallerio_config" );
		
	$config = array();
	
	foreach($getdata as $valconfig){
		
		$config[$valconfig->config_type] = $valconfig->config_val;
	}
			
	if(isset($_POST['dosave']) && $_POST['dosave'] == 'yes'){
		
		 foreach($_POST as $keycon=>$valcon){
		 	
			$update = "UPDATE ".$wpdb->prefix."gallerio_config SET
					   config_val = '".addslashes($valcon)."'
					   WHERE config_type = '".$keycon."'";
						
			 $wpdb->query($update);
		 }
		 
		 $action = 'updated';
	 
	}
	
	if(isset($_POST['mode']) && $_POST['mode'] == 'regthumb'){
		
		 $getpics = "SELECT * FROM ".$wpdb->prefix."gallerio_images";
		 $getpics = $wpdb->get_results($getpics);
		 
		 foreach($getpics as $valimage){
		 	
			 @unlink($uploaddir['basedir'].'/gallerio/'.$valimage->gallery_pic_small);
			 @unlink($uploaddir['basedir'].'/gallerio/'.$valimage->gallery_pic_big);
			 //@unlink($uploaddir['basedir'].'/gallerio/'.$valgal->gallery_pic_large);
			 
			 $filepath = $uploaddir['basedir'].'/gallerio/'.$valimage->gallery_pic_large;
			 $filepath_small = $uploaddir['basedir'].'/gallerio/'.$valimage->gallery_pic_small;
			 $filepath_medium = $uploaddir['basedir'].'/gallerio/'.$valimage->gallery_pic_big;
			 
			 createthmb($filepath,$filepath_small,$config['gallerio_thumb_small_width'],$config['gallerio_thumb_small_height'],$config['thumbnail_aspect_ratio']);
			 createthmb($filepath,$filepath_medium,$config['gallerio_thumb_big_width'],$config['gallerio_thumb_big_height'],$config['picture_aspect_ratio']);
		 }
		 
		 $action = 'updated';
	}
	
	
	$getdata = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."gallerio_config" );
	
	$config = array();
	
	foreach($getdata as $valconfig){
		
		$config[$valconfig->config_type] = $valconfig->config_val;
	}
	
	//print "<pre>";
	//print_r($config);
	?>
	
	<form name="frmhiddenoptions" method="post" action="">
		<input type="hidden" name="mode" value="" />
		<input type="hidden" name="id" value="" />
	</form>
	
	<div class="wrap">
	
	<h2>Settings</h2>
	
	<?php
	if($action == 'updated'){
		
		echo '<div id="message" class="updated below-h2"><p>Settings '.$action.'</p></div>';
	}
	?>
	
	<div style="margin-top:20px;"><a href="javascript:regeneratethumb()">Regenerate Thumbnails</a><img src="<?=plugins_url().'/gallerio/css/images/loading.gif'?>" style="vertical-align:middle; margin-left:5px; margin-bottom:3px; display:none" id="loaderimagethmb" /></div>
	
	<div style="background:#e0e0e0; padding:10px; border-radius:5px; margin-top:10px;"><strong>Note:</strong> Regenerate thumbnails incase you change the dimension after uploading pictures. Thumbnails do not generate automatically once you change the dimension. That's why make sure to regenerate them for the changes to take effect.</div>
	
	<form name="frmhidden" method="post" action="">
	
		<input type="hidden" name="dosave" value="yes" />
		
		<table class="form-table">
		
		  <tbody>
			<tr>
				<th scope="row"><label for="gallerio_thumb_small_width">Thumbnail Dimension</label></th>
				<td>
					<input name="gallerio_thumb_small_width" type="text" id="gallerio_thumb_small_width" value="<?=$config['gallerio_thumb_small_width']?>" class="regular-text" required style="width:100px;">&nbsp;&nbsp;X&nbsp;&nbsp;
					<input name="gallerio_thumb_small_height" type="text" id="gallerio_thumb_small_height" value="<?=$config['gallerio_thumb_small_height']?>" class="regular-text" required style="width:100px;">&nbsp;px
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="show_title">Thumnbail Aspect Ratio</label></th>
				<td>
					<input name="thumbnail_aspect_ratio" type="radio" value="exact" <?=$config['thumbnail_aspect_ratio'] == 'exact' ? 'checked' : ''?> class="regular-text" required style="width:auto;">&nbsp;Exact&nbsp;&nbsp;
					<input name="thumbnail_aspect_ratio" type="radio" value="auto" <?=$config['thumbnail_aspect_ratio'] == 'auto' ? 'checked' : ''?> class="regular-text" required style="width:auto;">&nbsp;Maintain&nbsp;&nbsp;
					<input name="thumbnail_aspect_ratio" type="radio" value="crop" <?=$config['thumbnail_aspect_ratio'] == 'crop' ? 'checked' : ''?> class="regular-text" required style="width:auto;">&nbsp;Crop
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="gallerio_thumb_big_width">Picture Dimension</label></th>
				<td>
					<input name="gallerio_thumb_big_width" type="text" id="gallerio_thumb_big_width" value="<?=$config['gallerio_thumb_big_width']?>" class="regular-text" required style="width:100px;">&nbsp;&nbsp;X&nbsp;&nbsp;
					<input name="gallerio_thumb_big_height" type="text" id="gallerio_thumb_big_height" value="<?=$config['gallerio_thumb_big_height']?>" class="regular-text" required style="width:100px;">&nbsp;px
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="show_title">Picture Aspect Ratio</label></th>
				<td>
					<input name="picture_aspect_ratio" type="radio" value="exact" <?=$config['picture_aspect_ratio'] == 'exact' ? 'checked' : ''?> class="regular-text" required style="width:auto;">&nbsp;Exact&nbsp;&nbsp;
					<input name="picture_aspect_ratio" type="radio" value="auto" <?=$config['picture_aspect_ratio'] == 'auto' ? 'checked' : ''?> class="regular-text" required style="width:auto;">&nbsp;Maintain&nbsp;&nbsp;
					<input name="picture_aspect_ratio" type="radio" value="crop" <?=$config['picture_aspect_ratio'] == 'crop' ? 'checked' : ''?> class="regular-text" required style="width:auto;">&nbsp;Crop
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="gallerio_canvas_width">Picture Box Dimension</label></th>
				<td>
					<input name="gallerio_canvas_width" type="text" id="gallerio_canvas_width" value="<?=$config['gallerio_canvas_width']?>" class="regular-text" required style="width:100px;">&nbsp;&nbsp;X&nbsp;&nbsp;
					<input name="gallerio_canvas_height" type="text" id="gallerio_canvas_height" value="<?=$config['gallerio_canvas_height']?>" class="regular-text" required style="width:100px;">&nbsp;px
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="show_title">Show Image Title?</label></th>
				<td>
					<input name="show_title" type="radio" value="Yes" <?=$config['show_title'] == 'Yes' ? 'checked' : ''?> checked="checked" class="regular-text" required style="width:auto;">&nbsp;Yes&nbsp;&nbsp;
					<input name="show_title" type="radio" value="No" <?=$config['show_title'] == 'No' ? 'checked' : ''?> class="regular-text" required style="width:auto;">&nbsp;No
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="show_gallery_title">Show Gallery Title?</label></th>
				<td>
					<input name="show_gallery_title" type="radio" value="Yes" <?=$config['show_gallery_title'] == 'Yes' ? 'checked' : ''?> checked="checked" class="regular-text" required style="width:auto;">&nbsp;Yes&nbsp;&nbsp;
					<input name="show_gallery_title" type="radio" value="No" <?=$config['show_gallery_title'] == 'No' ? 'checked' : ''?> class="regular-text" required style="width:auto;">&nbsp;No
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="show_gallery_description">Show Gallery Description?</label></th>
				<td>
					<input name="show_gallery_description" type="radio" value="Yes" <?=$config['show_gallery_description'] == 'Yes' ? 'checked' : ''?> checked="checked" class="regular-text" required style="width:auto;">&nbsp;Yes&nbsp;&nbsp;
					<input name="show_gallery_description" type="radio" value="No" <?=$config['show_gallery_description'] == 'No' ? 'checked' : ''?> class="regular-text" required style="width:auto;">&nbsp;No
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="include_pagination">Show Pagination?</label></th>
				<td>
					<input name="include_pagination" type="radio" value="Yes" <?=$config['include_pagination'] == 'Yes' ? 'checked' : ''?> checked="checked"  class="regular-text" required style="width:auto;">&nbsp;Yes&nbsp;&nbsp;
					<input name="include_pagination" type="radio" value="No" <?=$config['include_pagination'] == 'No' ? 'checked' : ''?> class="regular-text" required style="width:auto;">&nbsp;No
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="per_page_item">Per Page Item</label></th>
				<td>
					<input name="per_page_item" type="text" id="per_page_item" value="<?=$config['per_page_item']?>" class="regular-text" required>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="include_pagination">Include Colorbox?</label></th>
				<td>
					<input name="include_colorbox" type="radio" value="Yes" <?=$config['include_colorbox'] == 'Yes' ? 'checked' : ''?> checked="checked"  class="regular-text" required style="width:auto;">&nbsp;Yes&nbsp;&nbsp;
					<input name="include_colorbox" type="radio" value="No" <?=$config['include_colorbox'] == 'No' ? 'checked' : ''?> class="regular-text" required style="width:auto;">&nbsp;No
				</td>
			</tr>
			<tr>
				<th scope="row">&nbsp;</th>
				<td>
					<input type="submit" value="<?=$_POST['mode'] == 'add' ? 'Add' : 'Update'?>" name="btnadd" class="button button-primary" >
					<input type="button" value="Cancel" name="btnadd" class="button button-primary" onclick="location.href='<?=site_url().'/'?>wp-admin/admin.php?page=gallerio'" >
				</td>
			</tr>
		  </tbody>
		  
		</table>
	</form>
	
	</div>
	
	<script>
	function regeneratethumb(){
		
		var cnf = confirm('Are you sure?');
		if(cnf){
			
			jQuery('#loaderimagethmb').show();
			document.frmhiddenoptions.mode.value = 'regthumb';
			document.frmhiddenoptions.submit();
		}
	}
	</script>
	
	<?php
}

/********************************************* Settings ***********************************************/



/****************************************** Other related functions ************************************************/

function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function createthmb($filepath,$thumbpath,$thmbwidth,$thmbheight,$resizetype)
{
	$newheight = $thmbheight;
	$newwidth = $thmbwidth;
	
	$phpThumb = new phpThumb();
	
	$phpThumb->setSourceData(file_get_contents($filepath));
	$output_filename = $thumbpath;
	
	$phpThumb->setParameter('w', $newwidth);
	$phpThumb->setParameter('h', $newheight);
	$phpThumb->setParameter('q', 100);
	
	if($resizetype == 'exact'){
		
		$phpThumb->setParameter('iar', 1);
	}
	if($resizetype == 'crop'){
		
		$phpThumb->setParameter('zc', 1);
	}
	
	$phpThumb->GenerateThumbnail();
	$phpThumb->RenderToFile($output_filename);
	
	$phpThumb->purgeTempFiles();
}

function initials($str) {
    $ret = '';
    foreach (explode(' ', $str) as $word)
        $ret .= strtoupper($word[0]);
    return $ret;
}

/****************************************** Other related functions ************************************************/


/***************************************** Prepare Gallery Shortcode ***********************************************/

function gallery($atts){
	
	global $wpdb;
	
	$uploaddir = wp_upload_dir();
	
	
	$getdata = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."gallerio_config" );
	
	$config = array();
	
	foreach($getdata as $valconfig){
		
		$config[$valconfig->config_type] = $valconfig->config_val;
	}
	
	
	$getgal = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."gallerio WHERE id = '".$atts['id']."'" );
	
	//print "<pre>";
	//print_r($atts);
	
	//die();

	ob_start();
	
	$pagenum = isset( $_GET['pagenum'.$atts['id']] ) ? absint( $_GET['pagenum'.$atts['id']] ) : 1;
	$limit = $config['per_page_item']; // number of rows in page
	$offset = ( $pagenum - 1 ) * $limit;
	$total = $wpdb->get_var( "SELECT COUNT(`id`) FROM ".$wpdb->prefix."gallerio_images WHERE gallery_id = '".$atts['id']."'" );
	$num_of_pages = ceil( $total / $limit );
	
	if($config['include_pagination'] == 'Yes'){
	
		$getgalimages = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."gallerio_images WHERE gallery_id = '".$atts['id']."' LIMIT $offset,$limit");
	}
	else{
		
		$getgalimages = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."gallerio_images WHERE gallery_id = '".$atts['id']."'");
	}
	
	//print "<pre>";
	//print_r($getgalimages);
	
	//die();
	
	?>
	
	<?php
	if($config['include_colorbox'] == 'Yes'){
	
	wp_enqueue_script( 'colorboxjs' );
    wp_enqueue_style( 'colorboxcss' );
	?>

	
	<script>
	jQuery(document).ready(function(){
		jQuery(".group1").colorbox({rel:'group1', maxWidth:'95%', maxHeight:'95%'});
	});
	</script>
	
	<?php
	}
	?>
	
	<style>
	.galleriocontainer{
		
		width:100%;
	}
	.gallerioimage{
		
		width:<?=$config['gallerio_canvas_width']?>px;
		height:<?=$config['gallerio_canvas_height']?>px;
	}
	.gallerioimagecontainer{
		
		height:auto;
		width:<?=$config['gallerio_canvas_width']?>px;
		text-align:center;
		float:left;
		margin-right:35px;
		margin-bottom:25px;
		border:1px solid #CCCCCC;
		border-radius:10px;
		box-shadow:5px 5px 5px #CCC;
		overflow:hidden;
	}
	.tablenav-pages .page-numbers{
		width:auto;
		height:20px !important;
		border:1px solid #CCCCCC;
		text-decoration:none;
		padding:3px;
		text-align:center;
		background:#CCCCCC;
		color:#333333 !important;
		display:block;
		float:left;
		margin-right:5px;
		line-height:20px;
		min-width:20px !important;
	}
	.tablenav-pages .page-numbers:hover{
		background:#333333 !important;
		color:#CCC !important;
	}
	.tablenav-pages .current{
		background:#333333 !important;
		color:#CCC !important;
	}
	.tablenav{
		float:right;
	}
	</style>
	
	<div class="galleriocontainer">
	
		<?php
		if($config['show_gallery_title'] == 'Yes'){
		?>
		
		<h2><?=$getgal->gallery?></h2>
		
		<?php
		}
		?>
		
		<?php
		if($config['show_gallery_description'] == 'Yes'){
		?>
		
		<?=apply_filters('the_content', stripslashes($getgal->gallery_desc));?>
		
		<?php
		}
		?>
		
		<p>&nbsp;</p>
		
		<?php
		if(count($getgalimages)){
			
			foreach($getgalimages as $valimages){
			
			$pic = $valimages->gallery_pic_small;
			
			if($pic == ''){
			
			  $pic = '';
			  $alt = $valimages->alt;
			}
			else if(!is_file($uploaddir['basedir'].'/gallerio/'.$pic)){
			  
			  $pic = '';
			  $alt = $valimages->alt;
			}
			else{
			
			  $pic = $uploaddir['baseurl'].'/gallerio/'.$pic;
			  $alt = '';
			}
			?>
			
			<?php
			if($config['include_colorbox'] == 'Yes'){
			?>
			
			<div class="gallerioimagecontainer">
			
				<a class="group1" href="<?=$uploaddir['baseurl'].'/gallerio/'.$valimages->gallery_pic_big?>" title="<?=$config['show_title'] == 'Yes' ? $valimages->title : ''?>">
				
					<div class="gallerioimage" style="background:url(<?=$pic?>) center center no-repeat; background-size:100% auto; line-height:100px;">
						
						<?=substr($alt,0,13).(strlen($alt) > 13 ? '...' : '')?>
						
					</div>
					
				</a>
				
				<?php
				if($config['show_title'] == 'Yes'){
				?>
				
				<div style="clear:both"></div>
				
				<div><?=$valimages->title ? : '&nbsp;'?></div>
				
				<?php 
				} 
				?>
				
			</div>
			
			<?php
			}
			else{
			?>
			
			<div class="gallerioimagecontainer">
				
				<?php
				if($valimages->link != ''){
					
					?>
					<a href="<?=$valimages->link?>" target="_blank">
					<?php
				}
				?>
			
				<div class="gallerioimage" style="background:url(<?=$pic?>) center center no-repeat; background-size:100% auto; line-height:100px;">
					
					<?=substr($alt,0,13).(strlen($alt) > 13 ? '...' : '')?>
					
				</div>
				
				<?php
				if($valimages->link != ''){
					
					?>
					</a>
					<?php
				}
				?>
					
				<?php
				if($config['show_title'] == 'Yes'){
				?>
				
				<div style="clear:both"></div>
				
				<div><?=$valimages->title?></div>
				
				<?php 
				} 
				?>
				
			</div>
			
			<?php
			}
			?>
			
			<?php
			}
		}
		else{
			
			echo 'No Picture(s)';
		}
		?>
		
	</div>
	
	<div style="clear:both"></div>

	<?php
	if($config['include_pagination'] == 'Yes'){
	
		$page_links = paginate_links( array(
			'base' => add_query_arg( 'pagenum'.$atts['id'], '%#%' ),
			'format' => '',
			'prev_text' => __( '&laquo;', 'text-domain' ),
			'next_text' => __( '&raquo;', 'text-domain' ),
			'total' => $num_of_pages,
			'current' => $pagenum
		) );
		
		if ( $page_links ) {
			echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
		}
	}
	?>
	
	
	<?php
	
	return ob_get_clean();
}

add_shortcode('gallery','gallery');


function galleries($atts){
	
	global $wpdb;
	
	$uploaddir = wp_upload_dir();
	
	
	$getdata = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."gallerio_config" );
	
	$config = array();
	
	foreach($getdata as $valconfig){
		
		$config[$valconfig->config_type] = $valconfig->config_val;
	}
	
	//print "<pre>";
	//print_r($atts);
	?>
	
	<style>
	.galleriocontainer{
		
		width:100%;
	}
	.gallerioimage{
		
		width:<?=$config['gallerio_canvas_width']?>px;
		height:<?=$config['gallerio_canvas_height']?>px;
	}
	.gallerioimagecontainer{
		
		height:auto;
		width:<?=$config['gallerio_canvas_width']?>px;
		text-align:center;
		float:left;
		margin-right:35px;
		margin-bottom:25px;
		border:1px solid #CCCCCC;
		border-radius:10px;
		box-shadow:5px 5px 5px #CCC;
		overflow:hidden;
		font-size:11px;
	}
	.tablenav-pages .page-numbers{
		width:auto;
		height:20px !important;
		border:1px solid #CCCCCC;
		text-decoration:none;
		padding:3px;
		text-align:center;
		background:#CCCCCC;
		color:#333333 !important;
		display:block;
		float:left;
		margin-right:5px;
		line-height:20px;
		min-width:20px !important;
	}
	.tablenav-pages .page-numbers:hover{
		background:#333333 !important;
		color:#CCC !important;
	}
	.tablenav-pages .current{
		background:#333333 !important;
		color:#CCC !important;
	}
	.tablenav{
		float:right;
	}
	</style>
	
	<div class="galleriocontainer">
	
	<?php
	
	if(isset($_GET['id']) && $_GET['id'] != ""){
		
		$getgal = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."gallerio WHERE id = '".$_GET['id']."'" );
	
		$pagenum = isset( $_GET['pagenum'.$_GET['id']] ) ? absint( $_GET['pagenum'.$_GET['id']] ) : 1;
		$limit = $config['per_page_item']; // number of rows in page
		$offset = ( $pagenum - 1 ) * $limit;
		$total = $wpdb->get_var( "SELECT COUNT(`id`) FROM ".$wpdb->prefix."gallerio_images WHERE gallery_id = '".$_GET['id']."'" );
		$num_of_pages = ceil( $total / $limit );
		
		if($config['include_pagination'] == 'Yes'){
		
			$getgalimages = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."gallerio_images WHERE gallery_id = '".$_GET['id']."' LIMIT $offset,$limit");
		}
		else{
			
			$getgalimages = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."gallerio_images WHERE gallery_id = '".$_GET['id']."'");
		}
		
		if($config['include_colorbox'] == 'Yes'){
		
		wp_enqueue_script( 'colorboxjs' );
	    wp_enqueue_style( 'colorboxcss' );
		?>
		
		<script>
		jQuery(document).ready(function(){
			jQuery(".group1").colorbox({rel:'group1', maxWidth:'95%', maxHeight:'95%'});
		});
		</script>
		
		<?php
		}
		?>
		
		<?php
		if($config['show_gallery_title'] == 'Yes'){
		?>
		
		<h2><?=$getgal->gallery?></h2>
		
		<?php
		}
		?>
		
		<?php
		if($config['show_gallery_description'] == 'Yes'){
		?>
		
		<?=apply_filters('the_content', stripslashes($getgal->gallery_desc));?>
		
		<?php
		}
		?>
		
		<p>&nbsp;</p>
		
		<?php
		if(count($getgalimages)){
			
			foreach($getgalimages as $valimages){
			
			$pic = $valimages->gallery_pic_small;
			
			if($pic == ''){
			
			  $pic = '';
			  $alt = $valimages->alt;
			}
			else if(!is_file($uploaddir['basedir'].'/gallerio/'.$pic)){
			  
			  $pic = '';
			  $alt = $valimages->alt;
			}
			else{
			
			  $pic = $uploaddir['baseurl'].'/gallerio/'.$pic;
			  $alt = '';
			}
			?>
			
			<?php
			if($config['include_colorbox'] == 'Yes'){
			?>
			
			<div class="gallerioimagecontainer">
			
				<a class="group1" href="<?=$uploaddir['baseurl'].'/gallerio/'.$valimages->gallery_pic_big?>" data-fancybox-group="gallery" title="<?=$config['show_title'] == 'Yes' ? $valimages->title : ''?>">
				
					<div class="gallerioimage" style="background:url(<?=$pic?>) center center no-repeat; background-size:100% auto; line-height:100px;">
						
						<?=substr($alt,0,13).(strlen($alt) > 13 ? '...' : '')?>
						
					</div>
					
				</a>
				
				<?php
				if($config['show_title'] == 'Yes'){
				?>
				
				<div style="clear:both"></div>
				
				<div><?=$valimages->title ? : '&nbsp;'?></div>
				
				<?php 
				} 
				?>
				
			</div>
			
			<?php
			}
			else{
			?>
			
			<div class="gallerioimagecontainer">
				
				<?php
				if($valimages->link != ''){
					
					?>
					<a href="<?=$valimages->link?>" target="_blank">
					<?php
				}
				?>
				
				<div class="gallerioimage" style="background:url(<?=$pic?>) center center no-repeat; background-size:100% auto; line-height:100px;">
					
					<?=substr($alt,0,13).(strlen($alt) > 13 ? '...' : '')?>
					
				</div>
				
				<?php
				if($valimages->link != ''){
					
					?>
					</a>
					<?php
				}
				?>
					
				<?php
				if($config['show_title'] == 'Yes'){
				?>
				
				<div style="clear:both"></div>
				
				<div><?=$valimages->title?></div>
				
				<?php 
				} 
				?>
				
			</div>
			
			<?php
			}
			?>
			
			<?php
			}
		}
		else{
			
			echo 'No Picture(s)';
		}
		?>
			
	
		<?php
		if($config['include_pagination'] == 'Yes'){
		
			$page_links = paginate_links( array(
				'base' => add_query_arg( 'pagenum'.$_GET['id'], '%#%' ),
				'format' => '',
				'prev_text' => __( '&laquo;', 'text-domain' ),
				'next_text' => __( '&raquo;', 'text-domain' ),
				'total' => $num_of_pages,
				'current' => $pagenum
			) );
			
			if ( $page_links ) {
				echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
			}
		}
	}
	else{
	
		if($atts['ids'] == 'all'){
			
			$gallerio = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."gallerio" );
		}
		else{
			
			$ids = $atts['ids'];
			$ids = explode(',',$ids);
			
			$idsin = '';
			
			if(count($ids)){
				
				foreach($ids as $id){
					
					$idsin .= "'".$id."',";
				}
			}
			
			$idsin = trim($idsin,',');
			
			$gallerio = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."gallerio WHERE id IN (".$idsin.")" );
		}	
		
		if(count($gallerio)){
				
			foreach($gallerio as $val){
				
				  $getpicture = "SELECT * FROM ".$wpdb->prefix."gallerio_images WHERE gallery_id = '".$val->id."' ORDER BY id LIMIT 1";
				  $getpicture = $wpdb->get_row($getpicture);
				  $pic = $getpicture->gallery_pic_small;
				  
				  if($pic == ''){
					
					  $pic = plugins_url().'/gallerio/no_img.png';
				  }
				  else if(!is_file($uploaddir['basedir'].'/gallerio/'.$pic)){
					  
					  $pic = plugins_url().'/gallerio/no_img.png';
				  }
				  else{
					
					  $pic = $uploaddir['baseurl'].'/gallerio/'.$pic;
				  }
				  
				  $noofpics = "SELECT * FROM ".$wpdb->prefix."gallerio_images WHERE gallery_id = '".$val->id."'";
				  $noofpics = $wpdb->get_results($noofpics);
				  $noofpics = $wpdb->num_rows;
				  
				  ?>
				  
				  <div class="gallerioimagecontainer">
			
					<a href="?id=<?=$val->id?>"><div class="gallerioimage" style="background:url(<?=$pic?>) center center no-repeat; background-size:100% auto; line-height:100px;"></div></a>
						
					
					<div style="clear:both"></div>
					
					<div style="font-size:14px; font-weight:bold"><?=$val->gallery?></div>
					
					<div><?=$noofpics.' Pictures'?></div>
					
					<div style="font-style:italic"><?=date('d.m.Y',strtotime($val->date_created))?></div>
					
				  </div>
				  
				  <?php
			}
		}
	}
	?>
	</div>
	
	<?php
}

add_shortcode('galleries','galleries');

/****************************************** Prepare Gallery Shortcode **********************************************/


/****************************************** Plugin Activation Actions **********************************************/

function install_table_gallerio(){

	global $wpdb;
	
	$tablename = $wpdb->prefix.'gallerio';
	
	$qry = "CREATE TABLE IF NOT EXISTS `".$tablename."` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `gallery` varchar(256) NOT NULL,
			  `gallery_desc` text NOT NULL,
			  `date_created` varchar(256) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
			
	$wpdb->query($qry);
	
	$tablename = $wpdb->prefix.'gallerio_images';
	
	$qry = "CREATE TABLE IF NOT EXISTS `".$tablename."` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `gallery_id` int(11) NOT NULL,
			  `gallery_pic_small` text NOT NULL,
			  `gallery_pic_big` text NOT NULL,
			  `gallery_pic_large` text NOT NULL,
			  `title` varchar(256) NOT NULL,
			  `alt` varchar(256) NOT NULL,
			  `link` text NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
			
	$wpdb->query($qry);
	
	$tablename = $wpdb->prefix.'gallerio_config';
	
	$qry = "CREATE TABLE IF NOT EXISTS `".$tablename."` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `config_type` varchar(256) NOT NULL,
			  `config_val` varchar(256) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15";
			
	$wpdb->query($qry);
	
	$qry = "INSERT INTO `".$tablename."` (`id`, `config_type`, `config_val`) VALUES
			(1, 'gallerio_thumb_small_width', '150'),
			(2, 'gallerio_thumb_small_height', '100'),
			(3, 'gallerio_thumb_big_width', '800'),
			(4, 'gallerio_thumb_big_height', '800'),
			(5, 'include_colorbox', 'Yes'),
			(6, 'include_pagination', 'No'),
			(7, 'per_page_item', '20'),
			(8, 'show_title', 'Yes'),
			(9, 'thumbnail_aspect_ratio', 'crop'),
			(10, 'picture_aspect_ratio', 'auto'),
			(11, 'gallerio_canvas_width', '150'),
			(12, 'gallerio_canvas_height', '100'),
			(13, 'show_gallery_title', 'Yes'),
			(14, 'show_gallery_description', 'Yes')";
			
	$wpdb->query($qry);
}

register_activation_hook(__FILE__,'install_table_gallerio');


function droptables(){
	
	global $wpdb;
	
	$uploaddir = wp_upload_dir();
	
	$getimages = "SELECT * FROM ".$wpdb->prefix."gallerio_images";
	$getimages = $wpdb->get_results($getimages);
	
	if(count($getimages)){
		
		foreach($getimages as $valimage){
			
			@unlink($uploaddir['basedir'].'/gallerio/'.$valimage->gallery_pic_small);
			@unlink($uploaddir['basedir'].'/gallerio/'.$valimage->gallery_pic_big);
			@unlink($uploaddir['basedir'].'/gallerio/'.$valimage->gallery_pic_large);
		}
	}
	
	$tablename = $wpdb->prefix.'gallerio';
	
	$qry = "DROP TABLE ".$tablename;
			
	$wpdb->query($qry);
	
	$tablename = $wpdb->prefix.'gallerio_config';
	
	$qry = "DROP TABLE ".$tablename;
			
	$wpdb->query($qry);
	
	$tablename = $wpdb->prefix.'gallerio_images';
	
	$qry = "DROP TABLE ".$tablename;
			
	$wpdb->query($qry);
}

register_uninstall_hook(__FILE__,'droptables');

/****************************************** Plugin Activation Actions **********************************************/
?>
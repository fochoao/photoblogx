<?php
	require_once('login.php');
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="author" content="PhotoblogX" /><?php
			require_once('printhead.php');
		?><link href="../css/main.css" type="text/css" rel="stylesheet" />
		<link href="../css/jquery-ui.css" type="text/css" rel="stylesheet" />
		
		<script src="../js/jquery-1.9.1.js" type="text/javascript"></script>
		<script src="../js/jquery-ui-1.10.3.js" type="text/javascript"></script>
		<script src="../js/jquery.form.js"></script>
		<script src="../js/tooltip.js" type="text/javascript"></script>
		<script type="text/javascript">
			function start() {
				$('body').fadeIn(1000);
			};
			$(document).ready(function (){
				$('body').hide();
				$("#admintab").tabs();
				var i = 0;
				$(".addphoto").click(function() {
					i++;
					if (i < 15) {
						$('<div style="padding-right:39px;"><input type="file" id="filestyle" class="uploadphoto'+i+'" title="Upload Photo" name="uploadphoto[]" alt="Upload Photo" /></div>').appendTo('#addfields');
						$('input[title]').Tooltip();
						$('input,button').focusin(function() {
							$(this).fadeTo(500, 0.90).css('background-color','#0AC');
						});
						$('input,button').focusout(function() {
							$(this).fadeTo(500, 0.40).css('background-color','#0AF');
						});
					}
				});
				$(document).on("click", '.managephotos_tab a', function(){
					$.ajax({
						type: 'GET',
						url: 'showcategory.php',
						cache: false,
						success: function(show_category) {
							$('#manage_photos').html(show_category);
							$('a[title],img[title]').Tooltip();
						}
					});
					return false;
				});
				$(document).on("click", '#photoedit a', function(){
					$.ajax({
						type: 'GET',
					    url: this.href,
					    cache: false,
					    success: function(photo_data) {
							$('.photodialog').dialog({
								autoOpen: false,
								modal: true,
								resizable: false,
								width: 730,
								height: 500,
								position: 'center',
								buttons: { 
									"Change Photo Info": function() {
										$("form#photosmodify").ajaxSubmit({
											url: $("form#photosmodify").attr('action'),
											type: $("form#photosmodify").attr('method'),
											data: $("form#photosmodify").formSerialize(),
											dataType: 'json',
											success: function(modify_photo) {
												if (modify_photo.photo.modified == "yes") {
													var photo_id = modify_photo.photo.id;
													var photo_name = modify_photo.photo.name;
													var photo_file = modify_photo.photo.file;
													var photo_category = modify_photo.photo.category;
													var photo_upload = modify_photo.photo.uploadphoto;
													if (photo_category == "yes") {
														$('#photo-'+photo_id).effect('bounce');
													} else {
														$('#photo-'+photo_id).effect('bounce');
													}
													$('a[title],img[title]').Tooltip();
													$('.photodialog').dialog('close');
												}
											}
										});
									},
									"Cancel": function() {
										$('.photodialog').dialog('close');
									}
								}
							});
							$('.photodialog').html(photo_data);
							$('.photodialog').dialog('open');
						}
					});
					return false;
				});
				$(document).on("click", '#photoerase a', function(){
					$.ajax({
						type: 'GET',
					    url: this.href,
					    dataType: 'json',
					    cache: false,
					    success: function(erase_photo) {
					    	if (erase_photo.photo.modified == "yes") {
					    		var photo_id_delete = erase_photo.photo.id;
					    		$('#photo-'+photo_id_delete).fadeOut(900);
					    		$('a[title],img[title]').Tooltip();
					    	}
					    }
					});
					return false;
				});
				$(document).on("click", '#photocategory a', function(){
					$.ajax({
					    url: this.href,
					    cache: false,
					    success: function(list_photos) {
							$('#fillphotos').html(list_photos);
							$('a[title],img[title]').Tooltip();
						}
					});
					return false;
				});
				$(document).on("click", '.comments_tab a', function(){
					$.ajax({
						type: 'GET',
						url: 'managecomments.php',
						data: { load_comments: true },
						cache: false,
						success: function (load_comments) {
						  $("#manage_comments").html(load_comments);
						}
					});
					return false;
				});
				$(document).on("click", '#admin_comments a', function(){
					$.ajax({
						type: 'GET',
						url: this.href,
						dataType: 'json',
						cache: false,
						success: function (comment_erase) {
							if (comment_erase.comment.erased == "yes") {
								$.ajax({
									type: 'GET',
									url: 'managecomments.php',
									data: { load_comments: true },
									cache: false,
									success: function (load_comments) {
									  $("#manage_comments").html(load_comments);
									}
								});
							}
						}
					});
					return false;
				});
				$(document).on("click", '.categories_tab a', function(){
					$("div#categorytext").html('');
					$("#title-category").prop('value','');
					$("#description-category").prop('value','');
					$("#id-category").prop('value','');
					return false;
				});
				$(document).on("click", '#addcategory', function(){
					$("#categories").ajaxSubmit({
						url: $("#categories").attr('action'),
						type: 'GET',
						data: $("#categories").formSerialize(),
						dataType: 'json',
						cache: false,
						success: function(category_add) {
							if (category_add.category.add == "yes") {
								var categorytitle = category_add.category.title;
								$("div#categorytext").html('<p>Category with title '+categorytitle+' added.</p>');
								$("#title-category").prop('value', '');
								$("#description-category").prop('value', '');
							}
						}
					});
				});
				$(document).on("click", '#listcategory', function(){
					$.ajax({
						url: 'categories.php',
						type: 'GET',
						data: { listcategories: 'yes' },
						cache: false,
						success: function(list_categories) {
							$("div#categorytext").html(list_categories);
						}
					});
				});
				$(document).on("click", '#link-category a', function(){
					$.ajax({
						type: 'GET',
					    url: this.href,
					    dataType: 'json',
					    cache: false,
					    success: function(edit_category) {
					    	if (edit_category.category.edit == "yes") {
					    		$("#id-category").prop('value', edit_category.category.id);
					    		$("#title-category").prop('value', edit_category.category.title);
					    		$("#description-category").prop('value', edit_category.category.description);
					    	}
					    }
					});
					return false;
				});
				$(document).on("click", '#applycategory', function(){
					var edit_id = $("#id-category").prop('value');
					var edit_title = $("#title-category").prop('value');
					var edit_description = $("#description-category").prop('value');
					$.ajax({
						type: 'GET',
						url: 'categories.php',
						data: { id_edit : edit_id, category_name : edit_title, category_description : edit_description },
						dataType: 'json',
						cache: false,
						success: function(apply_changes) {
							if (apply_changes.category.change == "yes") {
								var result_title = apply_changes.category.title;
								$("div#categorytext").html('<p>Category with title '+result_title+' edited.</p>');
								$("#title-category").prop('value', '');
								$("#description-category").prop('value', '');
								$("#id-category").prop('value', '');
							}
						}
					});
				});
				$(document).on("click", '#deletecategory', function(){
					var edit_id_del = $("#id-category").prop('value');
					var edit_title_del = $("#title-category").prop('value');
					$.ajax({
						type: 'GET',
						url: 'categories.php',
						data: { id_delete : edit_id_del, category_name : edit_title_del },
						dataType: 'json',
						cache: false,
						success: function(delete_category) {
							if (delete_category.category.confirm == "yes") {
								var del_title = delete_category.category.title;
								$("div#categorytext").html('<p>Category with title '+del_title+' deleted.</p>');
								$("#title-category").prop('value', '');
								$("#description-category").prop('value', '');
								$("#id-category").prop('value', '');
							}
						}
					});
				});
				$(document).on("click", '.settings_tab a', function(){
					$.ajax({
						type: 'POST',
						url: 'settings.php',
						data: { get_values: "yes"},
						dataType: 'json',
						cache: false,
						success: function(settings_tab) {
							if (settings_tab.settings.modified == "yes") {
								$("#admin_mail").html("<span>"+settings_tab.settings.mail+"</span>");
								if (settings_tab.settings.biography != null) {
									$("textarea#admin_biography").text(settings_tab.settings.biography);
								}
								if (settings_tab.settings.title != null) {
									$("input#photoblog_title").prop('value',settings_tab.settings.title);
								}
								if (settings_tab.settings.description != null) {
									$("input#photoblog_description").prop('value',settings_tab.settings.description);
								}
								if (settings_tab.settings.keywords != null) {
									$("input#photoblog_keywords").prop('value',settings_tab.settings.keywords);
								}
								if (settings_tab.settings.timezone != null) {
									$("#settings_timezone").html("<p>Timezone: "+settings_tab.settings.timezone+"</p>");
								}
								$("#settings_biography").html('');
								$("#settings_title").html('');
								$("#settings_description").html('');
								$("#settings_keywords").html('');
							}
						}
					});
					return false;
				});
				$(document).on("click", '#change_password', function(){
					var password = $("#admin_password").prop('value');
					$.ajax({
						url: 'settings.php',
						type: 'POST',
						data: { change_password: password },
						dataType: 'json',
						cache: false,
						success: function(pass_change) {
							if (pass_change.password.modified == "yes") {
								$("#admin_mail").html("<span>Password changed for mail "+pass_change.password.mail+".</span>");
							}
						}
					});
				});
				$(document).on("click", '#change_biography', function(){
					var biography_val = $("textarea#admin_biography").val();
					$.ajax({
						url: 'settings.php',
						type: 'POST',
						data: { change_biography: biography_val },
						dataType: 'json',
						cache: false,
						success: function(biography_change) {
							if (biography_change.biography.modified == "yes") {
								$("#settings_biography").html("<p>Biography changed succesfully.</p>");
							}
						}
					});
					return false;
				});
				$(document).on("click", '#change_title', function(){
					var title_val = $("#photoblog_title").prop('value');
					$.ajax({
						url: 'settings.php',
						type: 'POST',
						data: { change_title: title_val },
						dataType: 'json',
						cache: false,
						success: function(title_change) {
							if (title_change.title.modified == "yes") {
								$("#settings_title").html("<p>Photoblog title changed succesfully.</p>");
							}
						}
					});
				});
				$(document).on("click", '#change_description', function(){
					var description_val = $("#photoblog_description").prop('value');
					$.ajax({
						url: 'settings.php',
						type: 'POST',
						data: { change_description: description_val },
						dataType: 'json',
						cache: false,
						success: function(description_change) {
							if (description_change.description.modified == "yes") {
								$("#settings_description").html("<p>Photoblog description changed succesfully.</p>");
							}
						}
					});
				});
				$(document).on("click", '#change_keywords', function(){
					var keywords_val = $("#photoblog_keywords").prop('value');
					$.ajax({
						url: 'settings.php',
						type: 'POST',
						data: { change_keywords: keywords_val },
						dataType: 'json',
						cache: false,
						success: function(keywords_change) {
							if (keywords_change.keywords.modified == "yes") {
								$("#settings_keywords").html("<p>Photoblog description changed succesfully.</p>");
							}
						}
					});
				});
				$(document).on("click", '#change_timezone', function(){
					var timezone = $("#timezone option:selected").prop('value');
					$.ajax({
						url: 'settings.php',
						type: 'POST',
						data: { change_timezone: timezone },
						dataType: 'json',
						cache: false,
						success: function(timezone_change) {
							if (timezone_change.timezone.modified == "yes") {
								$("#settings_timezone").html("<p>Timezone: "+timezone_change.timezone.value+"</p>");
							}
						}
					});
				});
				$("#photosmodify input:checkbox").on('click', function() {
					var checked = $(this).is(':checked');
					if (checked == false) {
						$(this).prop("checked",false);
					} else if (checked == true) {
						$(this).prop("checked",true);
					}
				});
				$('input,button,textarea,select').focusin(function() {
					$(this).fadeTo(500, 0.90).css('background-color','#0AC');
				});
				$('input,button,textarea,select').focusout(function() {
					$(this).fadeTo(500, 0.40).css('background-color','#0AF');
				});
				$('a[title],input[title],button[title],img[title]').Tooltip();
			});
		</script>
		
</head>
<body onload="start();">
			<div class="header" id="header-margin">
				<p><a href="administrator.php"<?php require_once('titleprint.php'); ?></p>
			</div>
			<br />
			<br />
			<div class="administration" id="administration">
            	<div id="admintab">
					<ul>
						<li><a href="#upload">Upload Photos</a></li>
						<li class="managephotos_tab"><a href="#manage_photos">Manage Photos</a></li>
						<li class="categories_tab"><a href="#manage_categories">Manage Categories</a></li>
						<li class="comments_tab"><a href="#manage_comments">Manage Comments</a></li>
						<li class="settings_tab"><a href="#manage_settings">Settings</a></li>
					</ul>
					<div id="upload">
						<form action="administrator.php" enctype="multipart/form-data" id="images" method="POST">
							<div id="addfields">
								<input type="file" id="filestyle" class="uploadphoto0" title="Upload Photo" name="uploadphoto[]" alt="Upload Photo" />
								<button class="addphoto" type="button" title="Upload Another Photo">+</button>
							</div>
							<br />
							<input type="submit" name="send-photos" alt="Upload Photos" title="Upload Photos" value="Upload Photos" />
						</form><?php
						include('uploadphotos.php');
					?><br /></div>
					<div id="manage_photos">
					</div>
					<div id="manage_categories">
						<form action="categories.php" onsubmit="return false;" id="categories" method="GET">
							<input type="hidden" name="id-edit" id="id-category" />
							<p>Category Name</p><input type="text" name="category-name" alt="Category Name" title="Category Name" id="title-category" />
							<p>Category Description</p><input type="text" name="category-description" alt="Category Description" title="Category Description" id="description-category" />
							<br />
							<br />
							<span><button id="addcategory" type="button" title="Add New Category">Add New Category</button><button id="applycategory" type="button" title="Apply Changes">Apply Changes</button><button id="deletecategory" type="button" title="Delete">Delete</button></span>
							<br />
							<br />
							<button id="listcategory" type="button" title="List Categories">List Categories</button>
							<div id="categorytext"></div>
						</form>
					</div>
					<div id="manage_comments">
						<div class="photodialog" title="Edit Photo"></div>
					</div>
					<div id="manage_settings">
						<form action="settings.php" onsubmit="return false;" id="settings_form" method="GET">
							<p>Change Password</p>
							<br />
							<div id="admin_mail"></div>
							<br />
							<input type="password" name="admin_password" title="Change Password" alt="Change Password" id="admin_password" />
							<br />
							<br />
							<button id="change_password" type="button" title="Change Password">Change Password</button>
							<br />
							<p>Change Biography</p>
							<textarea cols="50" rows="14" name="admin_biography" id="admin_biography"></textarea>
							<div id="settings_biography"></div>
							<br />
							<button id="change_biography" type="button" title="Change Biography">Change Biography</button>
							<br />
							<br />
							<p>Change Photoblog Title</p>
							<input type="text" name="photoblog_title" title="Change Photoblog Title" alt="Change Photoblog Title" id="photoblog_title" />
							<div id="settings_title"></div>
							<br />
							<button id="change_title" type="button" title="Change Photoblog Title">Change Photoblog Title</button>
							<br />
							<br />
							<p>Change Photoblog Description</p>
							<input type="text" name="photoblog_description" title="Change Photoblog Keywords" alt="Change Photoblog Keywords" id="photoblog_description" />
							<div id="settings_description"></div>
							<br />
							<button id="change_description" type="button" title="Change Photoblog Description">Change Photoblog Description</button>
							<br />
							<br />
							<p>Change Photoblog Keywords</p>
							<input type="text" name="photoblog_keywords" title="Change Photoblog Keywords" alt="Change Photoblog Keywords" id="photoblog_keywords" />
							<div id="settings_keywords"></div>
							<br />
							<button id="change_keywords" type="button" title="Change Photoblog Keywords">Change Photoblog Keywords</button>
							<br />
							<br />
							<p>Change Timezone</p><?php
								include('showtimezone.php');
							?><div id="settings_timezone"></div>
							<br />
							<button id="change_timezone" type="button" title="Change Timezone">Change Timezone</button>
						</form>
					</div>
				</div>
            </div>
            <br />
			<p class="logout"><a href="logout.php" title="Logout" alt="Logout">Logout</a></p>
			<br />
			<br />
	</body>
</html>
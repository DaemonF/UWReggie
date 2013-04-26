<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
 		<title><?php echo isset($title) ? "$title - UW Reggie" : "UW Reggie - Get text and email alerts for open courses at the UW!" ?></title>
 		<meta name="viewport" content="width=620">
		<meta name="description" content="Free text and email alerts for open courses at the University of Washington. Sign up now!"/>
		<meta name="keywords" content="uw,university,washington,alert,classes,courses,text,email,uwreggie,uw reggie,university of washington,registration,register,robot,uw robot,uwrobot"/>
	</head>

	<body<?php echo isset($cssBase) ? " class=\"$cssBase\"" : "" ?>>
		<div id=wrapper>
<?php if(!isset($noBranding)): ?>
			<div id="branding">
	<?php if(!isset($cssBase) || $cssBase != "login"): ?>
			<!--<div class="bubble"><b><span class="messageText">UPDATE:</span></b>Please select your phone carrier on the Settings page to begin using the new texting system! Please email me if you have any issues. <a href="mailto:uwreggie@gmail.com">uwreggie@gmail.com</a><div class="alignright"><em>-Reggie</em></div></div>-->
			<div class="bubble">I'm ready for <b>Spring and Summer 2013</b>! Share your story or like me on <a href="https://www.facebook.com/pages/UWReggie/342974295747177" target="_blank">Facebook</a>!<div class="alignright"><em>-Reggie</em></div></div>
	<?php endif; ?>
			<a style="color: black;" href="/"><h1 id="title">reggie</h1></a>
			<img id="logo" height="196" width="160" alt="Reggie Logo" src="images/happyreggie.png" />
			</div>
			<div class="clear"></div>
<?php endif; ?>

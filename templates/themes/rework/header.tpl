<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="description" content="ezRPG Project, the free, open source browser-based game engine!" />
		<meta name="keywords" content="" />
		
		<link rel="stylesheet" href="{$THEMEDIR}assets/css/style.css" type="text/css" />
		
		
		<script src="static/scripts/ext/jquery/jquery.1.8.1.min.js"></script>
		<script src="static/scripts/ext/jquery/plugins/run.js"></script>
		<script src="static/scripts/security.js"></script>
		
		<title>{$TITLE|default:"ezRPG rework"}</title>
	</head>
	<body>

		<div id="wrapper">

			<div id="header">
				<span id="title"><a href="./">ezRPG <span>rework</span></a></span>
				<span id="time">{$smarty.now|date_format:'%A, %H:%M'}
					<br />
					<strong>Players Online</strong>: {$ONLINE}</span>
			</div>

			<div id="nav">
				<ul>
					{if $LOGGED_IN == 'TRUE'}
						<li><a href="index.php">Home</a></li>
						<li><a href="index.php?mod=EventLog">Log</a></li>
						<li><a href="index.php?mod=City">City</a></li>
						<li><a href="index.php?mod=Members">Members</a></li>
						<li><a href="index.php?mod=AccountSettings">Account</a></li>
						<li><a href="index.php?mod=Logout">Log Out</a></li>
					{else}
						<li><a href="index.php">Home</a></li>
						<li><a href="index.php?mod=Register">Register</a></li>
					{/if}
				</ul>
			</div>
			<div id="body_wrap">
				{if $LOGGED_IN == 'TRUE'}
					<div id="sidebar">
						<strong class="alias">{$player->username}</strong>
						<hr />
						
						<strong>Level</strong>: {$player->level}<br />
						<strong>Gold</strong>: {$player->money}<br />
							<hr />
						<div class="bar">
							<div class="inner" style="width: {$player->exp_percentage}%"></div>
							<div class="text">EXP: {$player->exp} / {$player->max_exp}</div>
						</div>
						<div class="bar">
							<div class="inner" style="width: {$player->hp_percentage}%"></div>
							<div class="text">HP: {$player->hp} / {$player->max_hp}</div>
						</div>
						<div class="bar">
							<div class="inner" style="width: {$player->energy_percentage}%"></div>
							<div class="text">Energy: {$player->energy} / {$player->max_energy}</div>
						</div>
						{if $new_logs > 0}
							<a href="index.php?mod=EventLog" class="red"><strong>{$new_logs} New Log Events!</strong></a>
						{/if}
					</div>
				{/if}

				<div id="{if $LOGGED_IN == 'TRUE'}gamebody{else}body{/if}">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta name="Description" content="ezRPG Project, the free, open source browser-based game engine!" />
<meta name="Keywords" content="ezrpg, game, game engine, pbbg, browser game, browser games, rpg, ezrpg project" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="Distribution" content="Global" />
<meta name="Robots" content="index,follow" />
<link rel="stylesheet" href="../static/default/style.css" type="text/css" />	
<title>{$GAMESETTINGS['game_name']['value']} :: {$TITLE|default:""}</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script>
			// Wait until the DOM has loaded before querying the document
			$(document).ready(function(){
				$('ul.tabs').each(function(){
					// For each set of tabs, we want to keep track of
					// which tab is active and it's associated content
					var $active, $content, $links = $(this).find('a');

					// If the location.hash matches one of the links, use that as the active tab.
					// If no match is found, use the first link as the initial active tab.
					$active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
					$active.addClass('active');
					$content = $($active.attr('href'));

					// Hide the remaining content
					$links.not($active).each(function () {
						$($(this).attr('href')).hide();
					});

					// Bind the click event handler
					$(this).on('click', 'a', function(e){
						// Make the old tab inactive.
						$active.removeClass('active');
						$content.hide();

						// Update the variables with the new link and content
						$active = $(this);
						$content = $($(this).attr('href'));

						// Make the tab active.
						$active.addClass('active');
						$content.show();

						// Prevent the anchor's default click action
						e.preventDefault();
					});
				});
			});
		</script>
		<style>
		.tabs li {
				list-style:none;
				display:inline;
			}

			.tabs a {
				padding:5px 10px;
				display:inline-block;
				color:#fff;
				text-decoration:none;
			}

			.tabs a.active input{
				background:#fff;
				color:#000;
				border-bottom:5px solid #FF0000;
			}

		</style>
</head>
<body>

<div id="wrapper">

<div id="header">
	<span id="title">{$GAMESETTINGS['game_name']['value']}</span>
	<span id="time">{$smarty.now|date_format:'%A %T'}
	<br />
	<strong>Players Online</strong>: {$ONLINE}</span>
</div>
<div id="nav">
	<ul>
	{foreach from=$TOP_MENU_AdminMenu item=menu key=menukey} 
	<li><a href={$menu}>{$menukey}</a></li>
	{/foreach}
	</ul>
</div>

<span class="space"></span>

<div id="body">
	{if $GET_MSG != ''}<div class="msg">
	<span class="red"><strong>{$GET_MSG}</strong></span>
	</div>
	<span class="space"></span>{/if}

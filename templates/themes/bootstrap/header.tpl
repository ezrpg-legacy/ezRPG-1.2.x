<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="Description" content="ezRPG Project, the free, open source browser-based game engine!" />
<meta name="Keywords" content="ezrpg, game, game engine, pbbg, browser game, browser games, rpg, ezrpg project" />
<meta name="Distribution" content="Global" />
<meta name="Robots" content="index,follow" />
<link rel="stylesheet" href="static/bootstrap/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="static/bootstrap/css/bootstrap-theme.min.css" type="text/css" />
<link rel="stylesheet" href="static/bootstrap/css/theme.css" rel="stylesheet">	
<title>ezRPG :: {$TITLE|default:""}</title>
</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">{$GAMESETTINGS['game_name']['value']}</a>
        </div>
		<div class="navbar-collapse collapse">
          {if $LOGGED_IN == 'TRUE'}
			<ul class="nav navbar-nav">
			{foreach from=$TOP_MENU_UserMenu item=menu key=menukey} 
			<li><a href={$menu}>{$menukey}</a></li>
			{/foreach}
			</ul>
			{else}
			<ul class="nav navbar-nav">
			{foreach from=$TOP_MENU_LOGGEDOUT item=menu key=menukey} 
			<li><a href={$menu}>{$menukey}</a></li>
			{/foreach}
			</ul>
		  {/if}
        </div><!--/.nav-collapse -->
      </div>
    </div>
	
	<div class="container theme-showcase">
	<div class="row">
{if $LOGGED_IN == 'TRUE'}
<div id="sidebar" class="col-xs-6 col-sm-3">
<div class="well sidebar">
<strong>Level</strong>: {$player->level}<br />
<strong>Gold</strong>: {$player->money}<br />
EXP:
<div class="progress">
	<div class="progress-bar" role="progressbar" aria-valuenow="{$player->exp}" aria-valuemin="0" aria-valuemax="100" style="width: {{$player->exp}/{$player->max_exp}*100}%">
	<span class="sr-only">{{$player->exp}/{$player->max_exp}*100}% EXP</span>
	</div>
</div>
HP:
<div class="progress">
	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{$player->hp}" aria-valuemin="0" aria-valuemax="100" style="width: {{$player->hp}/{$player->max_hp}*100}%">
	<span class="sr-only">{{$player->hp}/{$player->max_hp}*100}% HP</span>
	</div>
</div>
Energy:
<div class="progress">
	<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="{$player->energy}" aria-valuemin="0" aria-valuemax="100" style="width: {{$player->energy}/{$player->max_energy}*100}%">
	<span class="sr-only">{{$player->energy}/{$player->max_energy}*100}% Energy</span>
	</div>
</div>

{if $new_logs > 0}
<a href="index.php?mod=EventLog" class="red"><strong>{$new_logs} New Log Events!</strong></a>
{/if}
</div></div>
{/if}

<div class="col-sm-6"id="{if $LOGGED_IN == 'TRUE'}gamebody{else}body{/if}">
	{if isset($MSG)}
	<div class="msg">
	{foreach $MSG as $newmsg}
		{foreach $newmsg as $level=>$message}
			<span class="msg {$level}"><strong>{$message}</strong></span>
		{/foreach}
	{/foreach}
	</div>
	{/if}

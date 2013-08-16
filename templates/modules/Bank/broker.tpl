{include file="file:[$THEME]header.tpl" TITLE="Broker"}

<h1>Broker</h1>
<div class="right"><div id="nav">
	<ul>
  <li><a href="index.php?mod=Bank">Bank</a></li>
  <li><a href="index.php?mod=Bank&act=transfer">Transfer</a></li>
</ul>
</div></div>
<p>
Your broker will try to multiply your money every day, gain 1% of its money.<br />
Has a 95% chance of success, if no success, the broker loses money 50%!<br />
Broker has {$player->broker} money.
</p>

<div class="left">
  <h2>Deposit</h2>
  <form method="post" action="index.php?mod=Bank&act=broker&move=deposit">
  <input type="text" name="amount" autocomplete="off" value="{$player->money}" />
  <br />
  <input type="submit" value="Deposit" />
  </form>
</div>


<div class="right">
  <h2>Withdraw</h2>
  <form method="post" action="index.php?mod=Bank&act=broker&move=withdraw">
  <input type="text" name="amount" autocomplete="off" value="{$player->broker}" />
  <br />
  <input type="submit" value="Withdraw" />
  </form>
</div>

{include file="file:[$THEME]footer.tpl"}
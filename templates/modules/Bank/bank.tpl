{include file="file:[$THEME]header.tpl" TITLE="Bank"}

<h1>Bank</h1>
<div class="right"><div id="nav">
	<ul>
  <li><a href="index.php?mod=Bank&act=transfer">Transfer</a></li>
  <li><a href="index.php?mod=Bank&act=broker">Broker</a></li>
</ul>
</div></div>
<p>
  Welcome, <strong>{$player->username}</strong>!
  <br />
  You have <strong>{$player->bank}</strong> in bank!
  <br />
  Your broker have <strong>{$player->broker}</strong> money!
</p>

<div class="left">
  <h2>Deposit</h2>
  <form method="post" action="index.php?mod=Bank&act=deposit">
  <input type="text" name="amount" autocomplete="off" value="{$player->money}" />
  <br />
  <input type="submit" value="Deposit" />
  </form>
</div>


<div class="right">
  <h2>Withdraw</h2>
  <form method="post" action="index.php?mod=Bank&act=withdraw">
  <input type="text" name="amount" autocomplete="off" value="{$player->bank}" />
  <br />
  <input type="submit" value="Withdraw" />
  </form>
</div>

{include file="file:[$THEME]footer.tpl"}
{include file="[$THEME]header.tpl" TITLE="Bank"}

<h1>Bank</h1>

{if isset($MSG_FAIL)}
	<span class="msg fail">{$MSG_FAIL}</span>
{elseif isset($MSG_GOOD)}
	<span class="msg good">{$MSG_GOOD}</span>
{/if}

<p>
  Welcome, <strong>{$player->username}</strong>!
  <br />
  You have <strong>{$player->bank}</strong> gold in your bank!
</p>

<div class="left">
  <h2>Deposit</h2>
  <form method="post" action="index.php?mod=Bank&act=deposit">
  <label>Amount to Deposit</label>
  <input type="text" name="amount" value="{$player->money}" />
  <br />
  <input type="submit" value="Deposit" />
  </form>
</div>

<div class="right">
  <h2>Withdraw</h2>
  <form method="post" action="index.php?mod=Bank&act=withdraw">
  <label>Amount to Withdraw</label>
  <input type="text" name="amount" value="{$player->bank}" />
  <br />
  <input type="submit" value="Withdraw" />
  </form>
</div>

{include file="[$THEME]footer.tpl"}
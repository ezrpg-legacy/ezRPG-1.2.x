{include file="file:[$THEME]header.tpl" TITLE="Transfer"}

<h1>Transfer</h1>
<div class="right"><div id="nav">
	<ul>
  <li><a href="index.php?mod=Bank">Bank</a></li>
  <li><a href="index.php?mod=Bank&act=broker">Broker</a></li>
</ul>
</div></div>		
		
		<p>
    Here you can send money to another player.<br />
    The commission is 1% of the amount.
		</p>
		
&nbsp;<form action="index.php?mod=Bank&act=transfer&move=dosend" method="post">
        <div class="left">
         <h2>Money</h2>
         <input type="text" name="money"><br>
         </div>
         <div class="right">
         <h2>Player</h2>
         <input type="text" name="to"><br>
         </div>
         <center><input type="submit" value="Send"></center>
        </form>

{include file="file:[$THEME]footer.tpl"}
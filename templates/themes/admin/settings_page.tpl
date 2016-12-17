{include file="file:[$THEME]header.tpl" TITLE="Plugin/Module Admin"}

{function name=fsettings item=0 key=0 grp=0}
    {foreach from=$item item=sitem key=skey}
        {if isset($sitem.visible) && $sitem.visible == 1}
                    {if isset($sitem.children)}
                        <!--<tr>
							<td valign="top">

								<strong>{$sitem.title}</strong><br/>
                                {$sitem.description}
							</td>
						</tr>-->
						{fsettings item=$sitem.children key={$skey + $subkey}}
                    {else}
						<tr>
						<td valign="top">
						<strong>{$sitem.title}</strong><br>
                        {$sitem.description}<br>
						{if $sitem.datatype == 'text'}
							<input name='{$group}.{$skey}' type='text' value='{$sitem.value}'></input>
						{/if}

						{if $sitem.datatype == 'select'}
							<select name='{$group}.{$skey}'>
							{foreach from=$sitem['options'] item=$subitem}
								<option value='{$subitem.name}' {if $subitem.name == $sitem.value}selected{/if}>
									{$subitem.title} - {$subitem.value}
								</option>
							{/foreach}
						{/if}

						</td>
					</tr>
                    {/if}

        {/if}
    {/foreach}
{/function}


<!-- START OF ADMINCP_PLUGINS_ROW-->
{if $error != 0}
<b>You have an error with a code of {$error}:</b>
<b>{$errormsg}</b>
<br />
{/if}
<div>
		<form method="post" action="index.php?mod=Settings&act=save">
<table width="100%" border="1px">
  <tbody>
		<tr>
			<th width="25%">
				{$group|capitalize}
			</th>
		</tr>
{fsettings item=$settings}
	</tbody>
</table>

</div>
<input type="hidden" name="act" value="save" />
<input type="submit" class="button" value="Save Settings" name='save' />
</form>
<!-- END OF ADMINCP_PLUGINS_ROW-->

{include file="file:[$THEME]footer.tpl"}

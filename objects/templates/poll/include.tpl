{if $poll}
<div class="{$class}">
{if $poll.active}
<form name="poll_{$poll.id}" id="poll_{$poll.id}" action="{$action}" method="post">
<input type="hidden" name="a" value="vote"/>
	<div class="question" style="padding-top:10px;">
		<label>{$poll.question}</label>
	</div>
	<div class="choices">
		<table width="90%" class="box_table">
			{foreach item=choice from=$poll.choices}
			<tr>
				<td><label><input type="radio" name="choice" value="{$choice.id}"/>{$choice.text}</label></td>
			</tr>
			{/foreach}
		</table>
	</div>
	<button type="submit" class="{$button_class}"><span>{tr}Voteaza{/tr}</span></button>
</form>
{else}
	<div class="question">
		{$poll.question}
	</div>
	<div class="choices">
		<img src="{$root}files/polls/poll_{$poll.id}.png" alt="{$poll.question}"/>
	</div>
{/if}
</div>
{else}
- sondajul nu este alocat din administrare -
{/if}

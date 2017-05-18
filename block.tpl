
<div class="pkp_block block_last_issue">
	<div class="content">
		<ul class="JournalLastIssue">
				<li> </li>
            {foreach from=$issues item=issue}
				<li>
					{$issue.journal} -
					<a href="{url|escape journal=$issue.path page="issue" op="current"}" >{$issue.issue}</a>
				</li>
            {/foreach}
		</ul>
	</div>
</div>


<div class="pkp_block block_last_issue">
	<span class="title">
		{translate key="plugins.block.lastIssue.listTitle"}
	</span>
	<span class="content">
		<ul class="LastIssues">
            {foreach from=$issues item=issue}
				<li>
					{$issue.journal} -
					<a href="{url|escape journal=$issue.path page="issue" op="current"}" >{$issue.issue}</a>
				</li>
            {/foreach}
		</ul>
	</span>
</div>

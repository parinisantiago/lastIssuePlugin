
<div class="pkp_block block_last_issue">
	<span class="title">
		{translate key="plugins.block.lastIssue.listTitle"}
	</span>
	<span class="content">
		<ul class="last_issues_list">
            {foreach from=$issues item=issue}
				<li class="last_issues_list_item">
					<span class="last_issues_journal_title"> {$issue.journal} </span>
					<span class="last_issues_link">
						<a href="{url|escape journal=$issue.path page="issue" op="current"}" >{$issue.issue}</a>
					</span>
				</li>
            {/foreach}
		</ul>
	</span>
</div>

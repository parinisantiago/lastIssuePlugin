<?php

import('lib.pkp.classes.plugins.BlockPlugin');

class LastIssueBlockPlugin extends BlockPlugin {
	
	function getInstallSitePluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

	function getContextSpecificPluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

    function getDisplayName() {
		return __('plugins.block.lastIssue.displayName');
	}

	function getDescription() {
		return __('plugins.block.lastIssue.description');
	}

    function getContents($templateMgr, $request = null) {
        $issueDao = DAORegistry::getDAO('IssueDAO');
        $journalDao = DAORegistry::getDAO('JournalDAO');
		$lastIssues = array();
        $journals = $journalDao->getAll(true, null)->toArray();
        foreach ($journals as $journal){
        	$lastIssue = array();
        	$issue = $issueDao->getCurrent($journal->getId());
        	if($issue)
        	{
                $lastIssue['journal'] = $journal->getLocalizedName();
                $lastIssue['path'] = $journal->getPath();
                $lastIssue['issue'] = $issue->getLocalizedTitle();
                $lastIssue['published'] = $issue->getDatePublished();
                array_push($lastIssues, $lastIssue);
            }
		}
        $templateMgr->assign(array(
			'issues' => $lastIssues
        ));

        return parent::getContents($templateMgr, $request);
    }
}

?>

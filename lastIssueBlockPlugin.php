<?php

import('lib.pkp.classes.plugins.BlockPlugin');
define('__LIMIT__', 90);

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

    function build_sorter($key){
		return function ($a, $b) use ($key){
			return strnatcmp($b[$key], $a[$key]);
		};
    }

    function getContents($templateMgr, $request = null) {
		$issueDao = DAORegistry::getDAO('IssueDAO');
        $journalDao = DAORegistry::getDAO('JournalDAO');
		$lastIssues = array();
        $journals = $journalDao->getAll(true, null)->toArray();

        foreach ($journals as $journal){
            $issue = $issueDao->getCurrent($journal->getId());
            $lastIssue = array();
			$lastIssue['published'] = $issue->getDatePublished();
			$lastIssue['journal'] = $journal->getLocalizedName();
			$lastIssue['path'] = $journal->getPath();
			$lastIssue['issue'] = __('plugins.block.lastIssue.volume').$issue->getVolume().__('plugins.block.lastIssue.number').$issue->getNumber().' ('.$issue->getYear().')';
			array_push($lastIssues, $lastIssue);
		}

		usort($lastIssues, $this->build_sorter('published'));
		$lastIssues = array_slice($lastIssues,0,__LIMIT__);

        $templateMgr->assign(array(
			'issues' => $lastIssues
        ));

        return parent::getContents($templateMgr, $request);
    }
    
}

?>

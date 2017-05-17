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
}

?>

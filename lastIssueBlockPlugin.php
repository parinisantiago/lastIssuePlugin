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

    function manage($args, $request) {
        switch ($request->getUserVar('verb')) {
            case 'settings':
                $context = $request->getContext();

                AppLocale::requireComponents(LOCALE_COMPONENT_APP_COMMON,  LOCALE_COMPONENT_PKP_MANAGER);
                $templateMgr = TemplateManager::getManager($request);
                $templateMgr->register_function('plugin_url', array($this, 'smartyPluginUrl'));

                $this->import('GoogleAnalyticsSettingsForm');
                $form = new LastIssuesSettingsForm($this, $context->getId());

                if ($request->getUserVar('save')) {
                    $form->readInputData();
                    if ($form->validate()) {
                        $form->execute();
                        return new JSONMessage(true);
                    }
                } else {
                    $form->initData();
                }
                return new JSONMessage(true, $form->fetch($request));
        }
        return parent::manage($args, $request);
    }

    function getActions($request, $verb) {
        $router = $request->getRouter();
        import('lib.pkp.classes.linkAction.request.AjaxModal');
        return array_merge(
            $this->getEnabled()?array(
                new LinkAction(
                    'settings',
                    new AjaxModal(
                        $router->url($request, null, null, 'manage', null, array('verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'block')),
                        $this->getDisplayName()
                    ),
                    __('manager.plugins.settings'),
                    null
                ),
            ):array(),
            parent::getActions($request, $verb)
        );
    }
}

?>

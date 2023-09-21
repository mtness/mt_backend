<?php

namespace MarkusTimtner\MtBackend\Hooks;

use TYPO3\CMS\Backend\Controller\PageLayoutController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class PageHook
{
	/**
	 * @var StandaloneView
	 */
	protected $view;

	public function render(array $params, PageLayoutController $parentObject)
	{
		//load partial paths info from typoscript
		$this->view = GeneralUtility::makeInstance(StandaloneView::class);
		$this->view->setFormat('html');
		$resourcesPath = 'EXT:mt_backend/Resources/';
		$this->view->setTemplatePathAndFilename($resourcesPath . 'Private/Templates/PageHook.html');

		$pageinfo = $parentObject->pageinfo;
		if($pageinfo['media']) {
			$fileRepository = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\FileRepository::class);
			$fileObjects = $fileRepository->findByRelation('pages', 'media', $pageinfo['uid']);
			$this->view->assign('files', $fileObjects);
		}

		if ($pageinfo['categories']) {
			$queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable('sys_category');
			$query = $queryBuilder->select('sys_category.uid', 'sys_category.title')->from('sys_category');
			$query->join(
				'sys_category',
				'sys_category_record_mm',
				'mm',
				$queryBuilder->expr()->andX(
					$queryBuilder->expr()->eq('mm.uid_local', $queryBuilder->quoteIdentifier('sys_category.uid')),
					$queryBuilder->expr()->in('mm.uid_foreign', $pageinfo['uid']),
					$queryBuilder->expr()->eq('mm.tablenames', $queryBuilder->quote('pages')),
					$queryBuilder->expr()->eq('mm.fieldname', $queryBuilder->quote('categories'))
				)
			);
			$categoryObjects = $query->execute()->fetchAll();
			$this->view->assign('categories', $categoryObjects);
		}

		$this->view->assign('page', $parentObject->pageinfo);
		return $this->view->render();
	}
}

<?php

namespace MarkusTimtner\MtBackend\Backend;

use TYPO3\CMS\Backend\Controller\Event\ModifyPageLayoutContentEvent;

use TYPO3\CMS\Backend\Controller\PageLayoutController;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Type\Bitmask\Permission;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;


class ModifyPageLayoutContent {

	public function __invoke( ModifyPageLayoutContentEvent $event ): void {

		$event->addHeaderContent( $this->renderStuff( (int) ( $event->getRequest()
		                                                            ->getQueryParams()['id'] ?? 0 ) ) );
	}
	protected function renderStuff(int $id)
	{

		$pageinfo = BackendUtility::readPageAccess($id, $GLOBALS['BE_USER']->getPagePermsClause(Permission::PAGE_SHOW));

		//load partial paths info from typoscript
		$view = GeneralUtility::makeInstance(StandaloneView::class);
		$view->setFormat('html');
		$resourcesPath = 'EXT:mt_backend/Resources/';
		$view->setTemplatePathAndFilename($resourcesPath . 'Private/Templates/PageHook.html');

		if ($pageinfo['media']) {
			$fileRepository = GeneralUtility::makeInstance(FileRepository::class);
			$fileObjects = $fileRepository->findByRelation('pages', 'media', $pageinfo['uid']);
			$view->assign('files', $fileObjects);
		}

		if ($pageinfo['categories']) {
			$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_category');
			$query = $queryBuilder->select('sys_category.uid', 'sys_category.title')->from('sys_category');
			$query->join(
				'sys_category',
				'sys_category_record_mm',
				'mm',
				(string)$queryBuilder->expr()->and(
					$queryBuilder->expr()->eq('mm.uid_local', $queryBuilder->quoteIdentifier('sys_category.uid')),
					$queryBuilder->expr()->in('mm.uid_foreign', $pageinfo['uid']),
					$queryBuilder->expr()->eq('mm.tablenames', $queryBuilder->quote('pages')),
					$queryBuilder->expr()->eq('mm.fieldname', $queryBuilder->quote('categories'))
				)
			);
			$categoryObjects = $query->executeQuery()->fetchAllAssociative();
			$view->assign('categories', $categoryObjects);
		}

		$view->assign('page', $pageinfo);
		return $view->render();
	}

}
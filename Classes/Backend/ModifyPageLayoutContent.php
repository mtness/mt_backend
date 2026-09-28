<?php

namespace MarkusTimtner\MtBackend\Backend;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Controller\Event\ModifyPageLayoutContentEvent;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Type\Bitmask\Permission;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;


class ModifyPageLayoutContent {

	public function __invoke( ModifyPageLayoutContentEvent $event ): void {

		$event->addHeaderContent( $this->renderStuff(
			(int) ( $event->getRequest()->getQueryParams()['id'] ?? 0 ),
			$event->getRequest()
		) );
	}
	protected function renderStuff(int $id, ?ServerRequestInterface $request = null): string
	{

		$pageinfo = BackendUtility::readPageAccess($id, $GLOBALS['BE_USER']->getPagePermsClause(Permission::PAGE_SHOW));

		$view = $this->createView($request);

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

	/**
	 * TYPO3 v14 removed \TYPO3\CMS\Fluid\View\StandaloneView, so from v14 on the
	 * generic ViewFactoryInterface must be used instead. Earlier versions keep
	 * using StandaloneView, as the view factory only exists since TYPO3 v13.3.
	 */
	protected function createView(?ServerRequestInterface $request): object
	{
		if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() >= 14) {
			$viewFactory = GeneralUtility::makeInstance(ViewFactoryInterface::class);
			return $viewFactory->create(new ViewFactoryData(
				templatePathAndFilename: 'EXT:mt_backend/Resources/Private/Templates/PageHook.html',
				request: $request,
				format: 'html'
			));
		}

		$view = GeneralUtility::makeInstance(StandaloneView::class);
		$view->setFormat('html');
		$view->setTemplatePathAndFilename('EXT:mt_backend/Resources/Private/Templates/PageHook.html');
		return $view;
	}

}

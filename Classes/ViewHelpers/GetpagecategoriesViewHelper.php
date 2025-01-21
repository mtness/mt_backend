<?php
namespace MarkusTimtner\MtBackend\ViewHelpers;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * 2024-07-18 by Markus Timtner
 *
 * Get categories of page
 * only applicable in backend context
 *
 * Example usage
 * <m:getpagecategories uid="{page.uid}" />
 *
 */

class GetpagecategoriesViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper {

	public function initializeArguments()
	{
		parent::initializeArguments();
		$this->registerArgument('uid', 'int', 'the page uid');
	}

	public static function renderStatic(
		array $arguments,
		\Closure $renderChildrenClosure,
		RenderingContextInterface $renderingContext
	): string {

		$pageUid = (int)$arguments['uid'];

			$queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable('sys_category');
			$query = $queryBuilder->select('sys_category.uid', 'sys_category.title')->from('sys_category');
			$query->join(
				'sys_category',
				'sys_category_record_mm',
				'mm',
				$queryBuilder->expr()->and(
					$queryBuilder->expr()->eq('mm.uid_local', $queryBuilder->quoteIdentifier('sys_category.uid')),
					$queryBuilder->expr()->in('mm.uid_foreign', $pageUid ),
					$queryBuilder->expr()->eq('mm.tablenames', $queryBuilder->quote('pages')),
					$queryBuilder->expr()->eq('mm.fieldname', $queryBuilder->quote('categories'))
				)
			);

			$pageCats = $query->executeQuery()->fetchAllAssociative();

			$result = '';

			foreach ($pageCats as $value) {
				$result .= "<li>" .$value['title']. "</li>";
			}

			return $result;
	}
}
<?php
namespace MarkusTimtner\MtBackend\ViewHelpers;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 *
 * @version $Id$
 * @copyright Dimitri Lavrenuek <lavrenuek.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class GetpagecategoriesViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper {

	use CompileWithRenderStatic;

	/**
	 * Initialize arguments
	 */
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
				$queryBuilder->expr()->andX(
					$queryBuilder->expr()->eq('mm.uid_local', $queryBuilder->quoteIdentifier('sys_category.uid')),
					$queryBuilder->expr()->in('mm.uid_foreign', $pageUid ),
					$queryBuilder->expr()->eq('mm.tablenames', $queryBuilder->quote('pages')),
					$queryBuilder->expr()->eq('mm.fieldname', $queryBuilder->quote('categories'))
				)
			);

			$pageCats = $query->execute()->fetchAll();

			$result = '';

			foreach ($pageCats as $value) {
				$result .= "<li>" .$value['title']. "</li>";
			}

			return $result;
	}
}
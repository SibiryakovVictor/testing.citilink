<?php
declare(strict_types=1);

namespace Step\Acceptance;

use AcceptanceTester;
use Page\Acceptance\GoodSubcategoryPage;

class SubcategorySteps extends AcceptanceTester
{
    public function openSubcategoryNotebooks(): GoodSubcategoryPage
    {
        $this->amOnPage('/');

        $this->openCatalog();

        $this->chooseCategory('Ноутбуки и компьютеры');

        return $this->openSubcategory('Ноутбуки');
    }

    private function openCatalog()
    {
        $catalogButton = ['css' => '.PopupCatalogMenu__button-open'];

        $this->scrollTo($catalogButton, null, -100);
        $this->waitForElementClickable($catalogButton);
        $this->clickWithLeftButton($catalogButton);
        $this->wait(5);
    }

    private function chooseCategory(string $dataTitle)
    {
        $categoryButton = ['css' => sprintf(
            '.CatalogMenu__category-list .CatalogMenu__category-items .CatalogMenu__category-item[data-title="%s"]',
            $dataTitle
        )];

        $this->scrollTo($categoryButton);
        $this->moveMouseOver($categoryButton);
        $this->wait(3);
    }

    private function openSubcategory(string $dataTitle): GoodSubcategoryPage
    {
        $subcategoryLink = ['css' => sprintf(
            '.CatalogMenu__right .CatalogMenu__subcategory-list .CatalogMenu__subcategory-label[data-title="%s"]',
            $dataTitle
        )];

        $this->scrollTo($subcategoryLink);
        $this->moveMouseOver($subcategoryLink);
        $this->click($subcategoryLink);

        $subcategoryUrl = $this->grabFromCurrentUrl();

        return new GoodSubcategoryPage($this, $subcategoryUrl);
    }
}

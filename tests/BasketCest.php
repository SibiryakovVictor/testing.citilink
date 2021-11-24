<?php
declare(strict_types=1);

use Page\Acceptance\BasketPage;
use Page\Acceptance\GoodCatalogPage;

class BasketCest
{
    /**
     * @before ensureBasketIsEmpty
     */
    public function testAddGoodFromGoodPage(AcceptanceTester $I)
    {
        $catalog = GoodCatalogPage::createNotebooksCatalog($I);

        $goodTitle = $catalog->grabGoodTitle(7);
        $goodPrice = $catalog->grabGoodPrice(7);

        $goodPage = $catalog->toGoodPage(7);

        $goodPage->addToBasket();

        $goodPage->closePopupIfAppeared();

        $basketPage = $goodPage->goToBasketThroughAddButton();

        $basketPage->ensureHasGood($goodTitle);
        $basketPage->ensureTotalPriceEquals($goodPrice);
    }

    /**
     * @before ensureBasketIsEmpty
     */
    public function testAddGoodFromGoodCatalog(AcceptanceTester $I)
    {
        $catalog = GoodCatalogPage::createNotebooksCatalog($I);

        $goodTitle = $catalog->grabGoodTitle(7);
        $goodPrice = $catalog->grabGoodPrice(7);

        $catalog->addGoodToBasket(7);

        $catalog->closePopupIfAppeared();

        $basketPage = $catalog->goToBasketThroughAddButton(7);

        $basketPage->ensureHasGood($goodTitle);
        $basketPage->ensureTotalPriceEquals($goodPrice);
    }

    private function ensureBasketIsEmpty(BasketPage $basketPage)
    {
        $basketPage->ensureIsEmpty();
    }
}

<?php
declare(strict_types=1);

use Codeception\Example;
use Page\Acceptance\BasketPage;
use Page\Acceptance\CheckoutPage;
use Page\Acceptance\FavoritesPage;
use Step\Acceptance\SubcategorySteps;

class BasketCest
{
    /**
     * @before ensureBasketIsEmpty
     * @dataProvider provideGoodPosition
     */
    public function testAddGoodFromGoodPage(SubcategorySteps $subcategorySteps, Example $goodPositionExample)
    {
        $subcategory = $subcategorySteps->openSubcategoryNotebooks();

        $goodPosition = $goodPositionExample[0];
        $goodTitle = $subcategory->grabGoodTitle($goodPosition);
        $goodPrice = $subcategory->grabGoodPrice($goodPosition);

        $goodPage = $subcategory->toGoodPage($goodPosition);

        $goodPage->addToBasket();

        $goodPage->closePopupIfAppeared();

        $basketPage = $goodPage->goToBasketThroughAddButton();

        $basketPage->assertHasGood($goodTitle);
        $basketPage->assertTotalPriceEquals($goodPrice);
    }

    /**
     * @before ensureBasketIsEmpty
     * @dataProvider provideGoodPosition
     */
    public function testAddGoodFromSubcategoryPage(SubcategorySteps $subcategorySteps, Example $goodPositionExample)
    {
        $subcategory = $subcategorySteps->openSubcategoryNotebooks();

        $goodPosition = $goodPositionExample[0];
        $goodTitle = $subcategory->grabGoodTitle($goodPosition);
        $goodPrice = $subcategory->grabGoodPrice($goodPosition);

        $subcategory->addGoodToBasket($goodPosition);

        $subcategory->closePopupIfAppeared();

        $basketPage = $subcategory->goToBasketThroughAddButton($goodPosition);

        $basketPage->assertHasGood($goodTitle);
        $basketPage->assertTotalPriceEquals($goodPrice);
    }

    /**
     * @before ensureBasketIsEmpty
     * @dataProvider provideGoodPosition
     */
    public function testAddGoodThenServices(SubcategorySteps $subcategorySteps, Example $goodPositionExample)
    {
        $subcategory = $subcategorySteps->openSubcategoryNotebooks();

        $goodPosition = $goodPositionExample[0];
        $goodPage = $subcategory->toGoodPage($goodPosition);

        $goodPage->addToBasket();
        $goodPage->closePopupIfAppeared();

        $goodPage->activateServicesTab();

        $goodPage->togglePurchaseProtectionService(2);

        $goodPrice = $goodPage->grabGoodPrice();
        $servicePrice = $goodPage->grabPricePurchaseProtectionService(2);

        $basketPage = $goodPage->goToBasketThroughAddButton();

        $basketPage->assertTotalPriceEquals($goodPrice + $servicePrice);
    }

    /**
     * @before ensureBasketIsEmpty
     * @dataProvider provideGoodPosition
     */
    public function testAddServicesBeforeAddGood(SubcategorySteps $subcategorySteps, Example $goodPositionExample)
    {
        $subcategory = $subcategorySteps->openSubcategoryNotebooks();

        $goodPosition = $goodPositionExample[0];
        $goodPage = $subcategory->toGoodPage($goodPosition);

        $goodPage->activateServicesTab();

        $goodPage->togglePurchaseProtectionService(2);

        $servicePrice = $goodPage->grabPricePurchaseProtectionService(2);
        $goodPrice = $goodPage->grabGoodPrice();

        $goodPage->addToBasket();
        $goodPage->closePopupIfAppeared();
        $basketPage = $goodPage->goToBasketThroughAddButton();

        $basketPage->assertTotalPriceEquals($goodPrice + $servicePrice);
    }

    /**
     * @before ensureBasketIsEmpty
     * @dataProvider provideGoodPosition
     */
    public function testIncreaseGoodCountFromBasket(SubcategorySteps $subcategorySteps, Example $goodPositionExample)
    {
        $subcategory = $subcategorySteps->openSubcategoryNotebooks();

        $goodPosition = $goodPositionExample[0];
        $subcategory->addGoodToBasket($goodPosition);

        $subcategory->closePopupIfAppeared();

        $goodTitle = $subcategory->grabGoodTitle($goodPosition);
        $goodPrice = $subcategory->grabGoodPrice($goodPosition);

        $basketPage = $subcategory->goToBasketThroughAddButton($goodPosition);

        $basketPage->incrementCountFirstGood();

        $basketPage->assertHasGood($goodTitle);
        $basketPage->assertChangedTotalPriceEquals($goodPrice * 2);
        $basketPage->assertFirstGoodPriceEquals($goodPrice * 2);
    }

    /**
     * @before ensureBasketIsEmpty
     * @dataProvider provideGoodPosition
     */
    public function testDecreaseGoodCountFromBasket(SubcategorySteps $subcategorySteps, Example $goodPositionExample)
    {
        $subcategory = $subcategorySteps->openSubcategoryNotebooks();

        $goodPosition = $goodPositionExample[0];
        $subcategory->addGoodToBasket($goodPosition);

        $subcategory->closePopupIfAppeared();

        $goodTitle = $subcategory->grabGoodTitle($goodPosition);
        $goodPrice = $subcategory->grabGoodPrice($goodPosition);

        $basketPage = $subcategory->goToBasketThroughAddButton($goodPosition);

        $basketPage->incrementCountFirstGood();
        $basketPage->decrementCountFirstGood();

        $basketPage->assertHasGood($goodTitle);
        $basketPage->assertChangedTotalPriceEquals($goodPrice);
        $basketPage->assertFirstGoodPriceEquals($goodPrice);
    }

    /**
     * @before ensureBasketIsEmpty
     * @dataProvider provideGoodPosition
     */
    public function testIncreaseGoodCountFromSubcategory(SubcategorySteps $subcategorySteps, Example $goodPositionExample)
    {
        $subcategory = $subcategorySteps->openSubcategoryNotebooks();

        $goodPosition = $goodPositionExample[0];

        $subcategory->addGoodToBasket($goodPosition);

        $subcategory->closePopupIfAppeared();

        $subcategory->incrementGoodCount($goodPosition);

        $goodTitle = $subcategory->grabGoodTitle($goodPosition);
        $goodPrice = $subcategory->grabGoodPrice($goodPosition);

        $basketPage = $subcategory->goToBasketThroughAddButton($goodPosition);

        $basketPage->assertHasGood($goodTitle);
        $basketPage->assertChangedTotalPriceEquals($goodPrice * 2);
        $basketPage->assertFirstGoodPriceEquals($goodPrice * 2);
    }

    /**
     * @before ensureBasketIsEmpty
     * @dataProvider provideGoodPosition
     */
    public function testDecreaseGoodCountFromSubcategory(SubcategorySteps $subcategorySteps, Example $goodPositionExample)
    {
        $subcategory = $subcategorySteps->openSubcategoryNotebooks();

        $goodPosition = $goodPositionExample[0];

        $subcategory->addGoodToBasket($goodPosition);

        $subcategory->closePopupIfAppeared();

        $subcategory->incrementGoodCount($goodPosition);
        $subcategory->decrementGoodCount($goodPosition);

        $goodTitle = $subcategory->grabGoodTitle($goodPosition);
        $goodPrice = $subcategory->grabGoodPrice($goodPosition);

        $basketPage = $subcategory->goToBasketThroughAddButton($goodPosition);

        $basketPage->assertHasGood($goodTitle);
        $basketPage->assertChangedTotalPriceEquals($goodPrice);
        $basketPage->assertFirstGoodPriceEquals($goodPrice);
    }

    /**
     * @before ensureBasketIsEmpty
     * @dataProvider provideGoodPosition
     */
    public function testTryAddToBasketMultipleTimes(SubcategorySteps $subcategorySteps, Example $goodPositionExample)
    {
        $subcategory = $subcategorySteps->openSubcategoryNotebooks();

        $goodPosition = $goodPositionExample[0];
        $subcategory->addGoodToBasket($goodPosition);
        $subcategory->closePopupIfAppeared();

        $goodTitle = $subcategory->grabGoodTitle($goodPosition);
        $goodPrice = $subcategory->grabGoodPrice($goodPosition);

        $goodPage = $subcategory->toGoodPage($goodPosition);
        $goodPage->assertAlreadyInBasket();

        $basketPage = $goodPage->goToBasketThroughAddButton();

        $basketPage->assertFirstGoodTitleEquals($goodTitle);
        $basketPage->assertFirstGoodCountEquals(1);
        $basketPage->assertFirstGoodPriceEquals($goodPrice);
    }

    /**
     * @before ensureBasketIsEmpty
     * @dataProvider provideGoodPosition
     */
    public function testRemoveGoodFromBasket(SubcategorySteps $subcategorySteps, Example $goodPositionExample)
    {
        $subcategory = $subcategorySteps->openSubcategoryNotebooks();

        $goodPosition = $goodPositionExample[0];
        $subcategory->addGoodToBasket($goodPosition);
        $subcategory->closePopupIfAppeared();

        $goodTitle = $subcategory->grabGoodTitle($goodPosition);
        $goodPrice = $subcategory->grabGoodPrice($goodPosition);

        $basketPage = $subcategory->goToBasketThroughAddButton($goodPosition);

        $basketPage->assertFirstGoodTitleEquals($goodTitle);
        $basketPage->assertFirstGoodCountEquals(1);
        $basketPage->assertFirstGoodPriceEquals($goodPrice);
        $basketPage->assertIsNotEmpty();

        $basketPage->removeFirstGoodFromBasket();

        $basketPage->assertIsEmpty();
    }

    /**
     * @before ensureBasketIsEmpty
     * @dataProvider provideGoodPositions
     */
    public function testClearBasket(SubcategorySteps $subcategorySteps, Example $goodPositionExample)
    {
        $subcategory = $subcategorySteps->openSubcategoryNotebooks();
        $goodPositions = $goodPositionExample[0];

        $goodTitles = [];
        foreach ($goodPositions as $goodPosition) {
            $subcategory->addGoodToBasket($goodPosition);
            $subcategory->closePopupIfAppeared();
            $goodTitles[] = $subcategory->grabGoodTitle($goodPosition);
        }

        $basketPage = $subcategory->goToBasketThroughAddButton(current($goodPositions));

        foreach ($goodTitles as $goodTitle) {
            $basketPage->assertContainsGood($goodTitle);
        }
        $basketPage->assertIsNotEmpty();

        $basketPage->clear();

        $basketPage->assertIsEmpty();
    }

    /**
     * @before loadCookies
     * @before ensureBasketIsEmpty
     * @before ensureFavoritesIsEmpty
     * @dataProvider provideGoodPosition
     */
    public function testAddOneGoodToFavorites(SubcategorySteps $subcategorySteps, Example $goodPositionExample)
    {
        $subcategory = $subcategorySteps->openSubcategoryNotebooks();

        $goodPosition = $goodPositionExample[0];
        $subcategory->addGoodToBasket($goodPosition);
        $subcategory->closePopupIfAppeared();

        $basketPage = $subcategory->goToBasketThroughAddButton($goodPosition);

        $basketPage->addFirstGoodToFavorites();

        $shortTitle = $basketPage->grabFirstGoodShortTitle();

        $favoritesPage = new FavoritesPage($subcategorySteps);

        $favoritesPage->assertFirstGoodShortTitleEquals($shortTitle);
    }

    /**
     * @before loadCookies
     * @before ensureBasketIsEmpty
     * @before ensureFavoritesIsEmpty
     * @dataProvider provideGoodPositions
     */
    public function testAllGoodsToFavorites(SubcategorySteps $subcategorySteps, Example $goodPositionExample)
    {
        $subcategory = $subcategorySteps->openSubcategoryNotebooks();

        $goodPositions = $goodPositionExample[0];
        foreach ($goodPositions as $goodPosition) {
            $subcategory->addGoodToBasket($goodPosition);
            $subcategory->closePopupIfAppeared();
        }

        $basketPage = $subcategory->goToBasketThroughAddButton(current($goodPositions));
        $basketPage->addAllGoodsToFavorites();

        $shortTitles = [];
        foreach (range(1, count($goodPositions)) as $goodPosition) {
            $shortTitles[] = $basketPage->grabGoodShortTitle($goodPosition);
        }

        $favoritesPage = new FavoritesPage($subcategorySteps);
        foreach ($shortTitles as $shortTitle) {
            $favoritesPage->assertContainsGood($shortTitle);
        }
    }

    /**
     * @before loadCookies
     * @before ensureBasketIsEmpty
     * @before ensureFavoritesIsEmpty
     * @dataProvider provideGoodPositions
     */
    public function testAddOneAndAllGoodsToFavorites(SubcategorySteps $subcategorySteps, Example $goodPositionExample)
    {
        $subcategory = $subcategorySteps->openSubcategoryNotebooks();

        $goodPositions = $goodPositionExample[0];
        foreach ($goodPositions as $goodPosition) {
            $subcategory->addGoodToBasket($goodPosition);
            $subcategory->closePopupIfAppeared();
        }

        $basketPage = $subcategory->goToBasketThroughAddButton(current($goodPositions));

        $shortTitles = [];
        foreach (range(1, count($goodPositions)) as $goodPosition) {
            $shortTitles[] = $basketPage->grabGoodShortTitle($goodPosition);
        }

        $basketPage->addFirstGoodToFavorites();

        $basketPage->addAllGoodsToFavorites();

        $favoritesPage = new FavoritesPage($subcategorySteps);
        foreach ($shortTitles as $shortTitle) {
            $favoritesPage->assertContainsGood($shortTitle);
        }
    }

    /**
     * @before loadCookies
     * @before ensureBasketIsEmpty
     * @before ensureFavoritesIsEmpty
     * @dataProvider provideGoodPositions
     */
    public function testAllGoodsAlreadyInFavorites(SubcategorySteps $subcategorySteps, Example $goodPositionExample)
    {
        $subcategory = $subcategorySteps->openSubcategoryNotebooks();

        $goodPositions = $goodPositionExample[0];
        foreach ($goodPositions as $goodPosition) {
            $subcategory->addGoodToBasket($goodPosition);
            $subcategory->closePopupIfAppeared();
        }

        $basketPage = $subcategory->goToBasketThroughAddButton(current($goodPositions));

        foreach (range(1, count($goodPositions)) as $goodPosition) {
            $basketPage->addGoodToFavorites($goodPosition);
        }

        $basketPage->assertAllGoodsAlreadyInFavorites();
    }

    /**
     * @before ensureBasketIsEmpty
     * @dataProvider provideGoodPositions
     */
    public function testToCheckoutIfBasketNotEmpty(SubcategorySteps $subcategorySteps, Example $goodPositionExample)
    {
        $subcategory = $subcategorySteps->openSubcategoryNotebooks();

        $goodPositions = $goodPositionExample[0];
        foreach ($goodPositions as $goodPosition) {
            $subcategory->addGoodToBasket($goodPosition);
            $subcategory->closePopupIfAppeared();
        }

        $basketPage = $subcategory->goToBasketThroughAddButton(current($goodPositions));

        $checkoutPage = $basketPage->toCheckout();

        $checkoutPage->assertOnPage();
    }

    /**
     * @before ensureBasketIsEmpty
     */
    public function testToCheckoutIfBasketEmpty(AcceptanceTester $I)
    {
        $checkoutPage = new CheckoutPage($I);

        $checkoutPage->ensureOnPage();

        $checkoutPage->assertOnPage();

        $checkoutPage->assertEmpty();
    }

    protected function provideGoodPositions(): array
    {
        $goodPositions = [];
        $maxPositions = rand(2, 4);
        for ($i = 0; $i < $maxPositions; $i++) {
            $goodPositions[] = $this->provideGoodPosition()[0][0];
        }

        return [[$goodPositions]];
    }

    protected function provideGoodPosition(): array
    {
        do {
            $goodPosition = rand(1,15);
        } while ($goodPosition === 4);

        return [[$goodPosition]];
    }

    private function ensureBasketIsEmpty(BasketPage $basketPage)
    {
        $basketPage->ensureIsEmpty();
    }

    private function ensureFavoritesIsEmpty(FavoritesPage $favoritesPage)
    {
        $favoritesPage->ensureIsEmpty();
    }

    private function loadCookies(AcceptanceTester $I)
    {
        $I->amOnPage('/');

        $cookies = file_get_contents(dirname(__FILE__) . '/cookies.txt');
        $cookies = explode('; ', $cookies);
        foreach ($cookies as $cookie) {
            list($key, $value) = explode('=', $cookie);
            $I->setCookie($key, $value, ['path' => '/', 'domain' => 'citilink.ru'], false);
        }
    }
}

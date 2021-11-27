<?php
namespace Page\Acceptance;

use AcceptanceTester;
use PHPUnit\Framework\Assert;

class BasketPage
{
    private const URL = '/order/';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     *
     * public static function route($param)
     * {
     *     return static::$URL.$param;
     * }
     */

    /**
     * @var AcceptanceTester;
     */
    protected AcceptanceTester $acceptanceTester;

    public function __construct(AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }

    public function assertHasGood(string $goodTitle)
    {
        $this->ensureOnUrl();

        $this->acceptanceTester->canSee($goodTitle);
    }

    public function assertFirstGoodTitleEquals(string $goodTitle)
    {
        $this->ensureOnUrl();

        $this->acceptanceTester->see($goodTitle, [
            'css' => '.ProductListForBasket__item:first-child .ProductCardForBasket__name'
        ]);
    }

    public function assertGoodTitleEquals(int $goodPosition, string $goodTitle)
    {
        $this->ensureOnUrl();

        $this->acceptanceTester->see($goodTitle, [
            'css' => sprintf(
                '.ProductListForBasket__item:nth-child(%s) .ProductCardForBasket__name',
                $goodPosition
            )
        ]);
    }

    public function assertTotalPriceEquals(int $totalPrice)
    {
        $this->ensureOnUrl();

        $priceOnPage = $this->acceptanceTester->grabTextFrom(['css' => '.OrderFinalPrice__price-current_current-price']);
        $priceOnPage = preg_replace('/\D/', '', $priceOnPage);

        Assert::assertEquals($priceOnPage, $totalPrice);
    }

    public function assertContainsGood(string $goodTitle)
    {
        $this->ensureOnUrl();

        $this->acceptanceTester->see($goodTitle, ['css' => '.Basket__list']);
    }

    public function assertChangedTotalPriceEquals(int $totalPrice)
    {
        $this->ensureOnUrl();

        $priceOnPage = $this->acceptanceTester->grabTextFrom(['css' => '.OrderFinalPrice__price-current__price']);
        $priceOnPage = preg_replace('/\D/', '', $priceOnPage);

        Assert::assertEquals($priceOnPage, $totalPrice);
    }

    public function assertFirstGoodPriceEquals(int $price)
    {
        $this->ensureOnUrl();

        $priceOnGoodCard = $this->acceptanceTester->grabTextFrom([
            'css' => '.ProductListForBasket__item:first-child .ProductCardForBasket__price-current_current-price'
        ]);
        $priceOnGoodCard = preg_replace('/\D/', '', $priceOnGoodCard);

        Assert::assertEquals($priceOnGoodCard, $price);
    }

    public function assertGoodPriceEquals(int $goodPosition, int $price)
    {
        $this->ensureOnUrl();

        $priceOnGoodCard = $this->acceptanceTester->grabTextFrom([
            'css' => sprintf(
                '.ProductListForBasket__item:nth-child(%s) .ProductCardForBasket__price-current_current-price',
                $goodPosition
            )
        ]);
        $priceOnGoodCard = preg_replace('/\D/', '', $priceOnGoodCard);

        Assert::assertEquals($priceOnGoodCard, $price);
    }

    public function assertFirstGoodCountEquals($count)
    {
        $countSelector = ['css' => '.ProductListForBasket__item:first-child .CountSelector__input'];

        $countOnGoodCard = $this->acceptanceTester->grabAttributeFrom($countSelector, 'value');
        $countOnGoodCard = (int)preg_replace('/\D/', '', $countOnGoodCard);

        Assert::assertEquals($countOnGoodCard, $count);
    }

    public function assertGoodCountEquals(int $goodPosition, int $count)
    {
        $countSelector = ['css' => sprintf(
            '.ProductListForBasket__item:nth-child(%s) .CountSelector__input',
            $goodPosition
        )];

        $countOnGoodCard = $this->acceptanceTester->grabAttributeFrom($countSelector, 'value');
        $countOnGoodCard = (int)preg_replace('/\D/', '', $countOnGoodCard);

        Assert::assertEquals($countOnGoodCard, $count);
    }

    public function assertIsEmpty()
    {
        $this->acceptanceTester->seeElement('.Basket__basket-empty');
    }

    public function assertIsNotEmpty()
    {
        $this->acceptanceTester->dontSeeElement('.Basket__basket-empty');
    }

    public function incrementCountFirstGood()
    {
        $incrementButton = ['css' => '.ProductListForBasket__item:first-child .CountSelector__control_increase'];

        $this->acceptanceTester->waitForElementClickable($incrementButton);
        $this->acceptanceTester->clickWithLeftButton($incrementButton);
        $this->acceptanceTester->wait(5);
    }

    public function decrementCountFirstGood()
    {
        $decrementButton = ['css' => '.ProductListForBasket__item:first-child .CountSelector__control_reduction'];

        $this->acceptanceTester->waitForElementClickable($decrementButton);
        $this->acceptanceTester->clickWithLeftButton($decrementButton);
        $this->acceptanceTester->wait(5);
    }

    public function removeFirstGoodFromBasket()
    {
        $removeButton = ['css' => '.ProductListForBasket__item:first-child .ProductCardForBasket__button-icon_remove'];

        $this->acceptanceTester->waitForElementClickable($removeButton);
        $this->acceptanceTester->clickWithLeftButton($removeButton);
        $this->acceptanceTester->wait(5);
    }

    public function addFirstGoodToFavorites()
    {
        $toFavoritesButton = ['css' => '.ProductListForBasket__item:first-child .wish-list-button'];

        $this->acceptanceTester->waitForElementClickable($toFavoritesButton);
        $this->acceptanceTester->clickWithLeftButton($toFavoritesButton);
        $this->acceptanceTester->wait(5);
    }

    public function addGoodToFavorites(int $goodPosition)
    {
        $toFavoritesButton = ['css' => sprintf(
            '.ProductListForBasket__item:nth-child(%s) .wish-list-button',
            $goodPosition
        )];

        $this->acceptanceTester->scrollTo($toFavoritesButton, null, -100);
        $this->acceptanceTester->waitForElementClickable($toFavoritesButton);
        $this->acceptanceTester->clickWithLeftButton($toFavoritesButton);
        $this->acceptanceTester->wait(5);
    }

    public function addAllGoodsToFavorites()
    {
        $this->ensureOnUrl();

        $allToFavoritesButton = ['css' => '.OrderFinalPrice__move-all-to-wishlist-button'];

        $this->acceptanceTester->scrollTo($allToFavoritesButton, null, -100);
        $this->acceptanceTester->waitForElementClickable($allToFavoritesButton);
        $this->acceptanceTester->clickWithLeftButton($allToFavoritesButton);
        $this->acceptanceTester->wait(5);
    }

    public function assertAllGoodsAlreadyInFavorites()
    {
        $this->ensureOnUrl();

        $allToFavoritesButton = ['css' => '.OrderFinalPrice__move-all-to-wishlist-button'];

        Assert::assertSame('', $this->acceptanceTester->grabAttributeFrom($allToFavoritesButton, 'data-disabled'));
    }

    public function grabFirstGoodShortTitle()
    {
        $this->ensureOnUrl();

        return $this->acceptanceTester->grabTextFrom([
            'css' => '.ProductListForBasket__item:first-child .ProductCardForBasket__name-mobile'
        ]);
    }

    public function grabGoodShortTitle(int $goodPosition)
    {
        $this->ensureOnUrl();

        return $this->acceptanceTester->grabTextFrom([
            'css' => sprintf(
                '.ProductListForBasket__item:nth-child(%s) .ProductCardForBasket__name-mobile',
                $goodPosition
            )
        ]);
    }

    public function clear()
    {
        $this->ensureOnUrl();

        $removeButton = ['css' => '.OrderFinalPrice__empty-cart'];

        $this->acceptanceTester->scrollTo($removeButton, null, -100);
        $this->acceptanceTester->waitForElementClickable($removeButton);
        $this->acceptanceTester->clickWithLeftButton($removeButton);
        $this->acceptanceTester->wait(5);
    }

    public function toCheckout(): CheckoutPage
    {
        $this->ensureOnUrl();

        $toCheckoutButton = ['css' => '.OrderFinalPrice__order-button'];

        $this->acceptanceTester->waitForElementClickable($toCheckoutButton);
        $this->acceptanceTester->scrollTo($toCheckoutButton, null, -100);
        $this->acceptanceTester->click($toCheckoutButton);

        return new CheckoutPage($this->acceptanceTester);
    }

    public function ensureIsEmpty()
    {
        $this->ensureOnUrl();

        $this->acceptanceTester->tryToClick(['css' => 'button.OrderFinalPrice__empty-cart']);
    }

    private function ensureOnUrl()
    {
        if (!$this->acceptanceTester->tryToSeeInCurrentUrl(self::URL)) {
            $this->acceptanceTester->amOnPage(self::URL);
        }
    }
}

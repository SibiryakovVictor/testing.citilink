<?php
declare(strict_types=1);

namespace Page\Acceptance;

use AcceptanceTester;
use Exception;

class GoodSubcategoryPage
{
    private const CATALOG_NOTEBOOKS = '/catalog/noutbuki--ultrabuki/';

    private string $url;

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

    public function __construct(AcceptanceTester $I, string $catalogUrl)
    {
        $this->acceptanceTester = $I;
        $this->url = $catalogUrl;
        $this->ensureOnUrl();
    }

    /**
     * @param AcceptanceTester $I
     * @return GoodSubcategoryPage
     */
    public static function createNotebooksSubcategory(AcceptanceTester $I): self
    {
        return new self($I, self::CATALOG_NOTEBOOKS);
    }

    /**
     * @param int $goodPosition
     * @return string
     */
    public function grabGoodTitle(int $goodPosition): string
    {
        $this->ensureOnUrl();

        $titleSelector = $this->getGoodTitleSelector($goodPosition);

        return $this->acceptanceTester->grabAttributeFrom($titleSelector, 'title');
    }

    /**
     * @param int $goodPosition
     * @return string
     */
    public function grabGoodPrice(int $goodPosition): int
    {
        $this->ensureOnUrl();

        $priceSelector = $this->getGoodPriceSelector($goodPosition);

        $price = $this->acceptanceTester->grabTextFrom($priceSelector);

        return (int)preg_replace('/\D/', '', $price);
    }

    public function incrementGoodCount(int $goodPosition)
    {
        $this->ensureOnUrl();

        $increaseBtnSelector = $this->getIncreaseCountBtnSelector($goodPosition);

        $this->acceptanceTester->waitForElementClickable($increaseBtnSelector);
        $this->acceptanceTester->clickWithLeftButton($increaseBtnSelector);
        $this->acceptanceTester->wait(5);
    }

    public function decrementGoodCount(int $goodPosition)
    {
        $this->ensureOnUrl();

        $increaseBtnSelector = $this->getDecreaseCountBtnSelector($goodPosition);

        $this->acceptanceTester->waitForElementClickable($increaseBtnSelector);
        $this->acceptanceTester->clickWithLeftButton($increaseBtnSelector);
        $this->acceptanceTester->wait(5);
    }

    public function addGoodToBasket(int $goodPosition)
    {
        $this->ensureOnUrl();

        if ($this->isAlreadyInBasket($goodPosition)) {
            return false;
        }

        $addToBasketBtnSelector = $this->getAddToBasketBtnSelector($goodPosition);

        $this->acceptanceTester->waitForElementClickable($addToBasketBtnSelector, 30);
        $this->acceptanceTester->scrollTo($addToBasketBtnSelector, null, -100);
        $this->acceptanceTester->click($addToBasketBtnSelector);

        return true;
    }

    public function goToBasketThroughAddButton(int $goodPosition): BasketPage
    {
        $this->ensureOnUrl();

        $goToBasketBtnSelector = $this->getGoToBasketBtnSelector($goodPosition);

        $this->acceptanceTester->waitForElement($goToBasketBtnSelector, 30);

        $this->acceptanceTester->waitForElementClickable($goToBasketBtnSelector, 30);
        $this->acceptanceTester->scrollTo($goToBasketBtnSelector, null, -100);
        $this->acceptanceTester->click($goToBasketBtnSelector);

        return new BasketPage($this->acceptanceTester);
    }

    public function closePopupIfAppeared()
    {
        try {
            $this->acceptanceTester->waitForElementClickable(['css' => '.UpsaleBasket__buttons button.UpsaleBasket__order'], 10);
            $this->acceptanceTester->scrollTo(['css' => '.UpsaleBasket__main-popup__close'], null, -100);
            $this->acceptanceTester->clickWithLeftButton(['css' => '.UpsaleBasket__main-popup__close']);
        } catch (Exception $exception) {}
    }

    public function toGoodPage(int $goodPosition): GoodPage
    {
        $this->ensureOnUrl();

        $titleSelector = $this->getGoodTitleSelector($goodPosition);
        $this->acceptanceTester->scrollTo($titleSelector, null, -100);
        $this->acceptanceTester->click($titleSelector);

        $goodUrl = $this->acceptanceTester->grabFromCurrentUrl();

        return new GoodPage($this->acceptanceTester, $goodUrl);
    }

    public function addRandomGoods(int $goodCount): array
    {
        $goodPositions = [];
        while (count($goodPositions) !== $goodCount) {
            do {
                $goodPosition = rand(1, 15);
            } while ($goodPosition === 4);

            if (
                !$this->acceptanceTester->tryToSeeElementInDOM(['css' => sprintf(
                '.ProductCardHorizontal:nth-child(%s)',
                    $goodPosition
                )])
            ) {
                continue;
            }

            if ($this->addGoodToBasket($goodPosition)) {
                $goodPositions[] = $goodPosition;
                $this->closePopupIfAppeared();
            }
        }

        return $goodPositions;
    }

    private function getGoodTitleSelector(int $goodPosition): array
    {
        return ['css' => sprintf(
            '.ProductCardHorizontal:nth-child(%s) .ProductCardHorizontal__title',
            $goodPosition
        )];
    }

    private function getGoodPriceSelector(int $goodPosition): array
    {
        return ['css' => sprintf(
            '.ProductCardHorizontal:nth-child(%s) .ProductCardHorizontal__price_current-price',
            $goodPosition
        )];
    }

    private function getAddToBasketBtnSelector(int $goodPosition): array
    {
        return ['css' => sprintf(
            '.ProductCardHorizontal:nth-child(%s) .ProductCardHorizontal__buy-block .ProductCardHorizontal__button_visible .ProductCardHorizontal__button_cart',
            $goodPosition
        )];
    }

    private function getGoToBasketBtnSelector(int $goodPosition): array
    {
        return ['css' => sprintf(
            '.ProductCardHorizontal:nth-child(%s) .ProductCardHorizontal__buy-block .ProductCardHorizontal__button_visible .ProductCardHorizontal__button_order',
            $goodPosition
        )];
    }

    private function getIncreaseCountBtnSelector(int $goodPosition): array
    {
        return ['css' => sprintf(
            '.ProductCardHorizontal:nth-child(%s) .CountSelector__control_increase',
            $goodPosition
        )];
    }

    private function getDecreaseCountBtnSelector(int $goodPosition): array
    {
        return ['css' => sprintf(
            '.ProductCardHorizontal:nth-child(%s) .CountSelector__control_reduction',
            $goodPosition
        )];
    }

    private function ensureOnUrl()
    {
        if (!$this->acceptanceTester->tryToSeeInCurrentUrl($this->url)) {
            $this->acceptanceTester->amOnPage($this->url);
        }
    }

    private function isAlreadyInBasket(int $goodPosition): bool
    {
        return false !== $this->acceptanceTester->tryToSeeElementInDOM($this->getGoToBasketBtnSelector($goodPosition));
    }
}

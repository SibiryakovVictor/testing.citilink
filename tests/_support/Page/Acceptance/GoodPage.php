<?php
declare(strict_types=1);

namespace Page\Acceptance;

use AcceptanceTester;
use Exception;
use PHPUnit\Framework\Assert;

class GoodPage
{
    // include url of current page
    public static $URL = '';

    private string $url;

    private array $btnAddToBasket = ['css' => '.ProductHeader__buy-block .js--AddToCart'];

    private array $btnGoToBasket = ['css' => '.ProductHeader__buy-block .js--ProductHeader__order'];

    private array $goodPrice = ['css' => '.ProductHeader__price-default_current-price'];

    private array $tabServices = ['css' => '.ProductCardLayout__tabs .TabItem[data-tabname="services"]'];

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }

    /**
     * @var AcceptanceTester;
     */
    protected $acceptanceTester;

    public function __construct(AcceptanceTester $I, string $goodUrl)
    {
        $this->acceptanceTester = $I;
        $this->url = $goodUrl;
        $this->ensureOnUrl();
    }

    public function addToBasket()
    {
        $this->ensureOnUrl();

        if ($this->isAlreadyInBasket()) {
            return false;
        }

        $this->acceptanceTester->waitForElementClickable($this->btnAddToBasket, 30);
        $this->acceptanceTester->scrollTo($this->btnAddToBasket, null, -100);
        $this->acceptanceTester->click($this->btnAddToBasket);

        return true;
    }

    public function goToBasketThroughAddButton()
    {
        $this->ensureOnUrl();

        if (!$this->isAlreadyInBasket()) {
            throw new Exception('good is not yet in basket');
        }

        $this->acceptanceTester->waitForElementClickable($this->btnGoToBasket, 30);
        $this->acceptanceTester->scrollTo($this->btnGoToBasket, null, -100);
        $this->acceptanceTester->click($this->btnGoToBasket);

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

    public function activateServicesTab()
    {
        $this->acceptanceTester->waitForElementClickable($this->tabServices, 30);
        $this->acceptanceTester->scrollTo($this->tabServices, null, -100);
        $this->acceptanceTester->click($this->tabServices);
    }

    public function togglePurchaseProtectionService(int $position)
    {
        $serviceSelector = $this->getPurchaseProtectionCheckboxSelector($position);

        $this->acceptanceTester->waitForElementClickable($serviceSelector, 30);
        $this->acceptanceTester->scrollTo($serviceSelector, null, -100);
        $this->acceptanceTester->checkOption($serviceSelector);
    }

    public function grabPricePurchaseProtectionService(int $position): int
    {
        $servicePriceSelector = $this->getPurchaseProtectionPriceSelector($position);

        $price = $this->acceptanceTester->grabTextFrom($servicePriceSelector);

        return (int)preg_replace('/\D/', '', $price);
    }

    public function grabGoodPrice(): int
    {
        $price = $this->acceptanceTester->grabTextFrom($this->goodPrice);

        return (int)preg_replace('/\D/', '', $price);
    }

    public function assertAlreadyInBasket()
    {
        Assert::assertTrue($this->isAlreadyInBasket());
    }

    public function ensureOnUrl()
    {
        if (!$this->acceptanceTester->tryToSeeInCurrentUrl($this->url)) {
            $this->acceptanceTester->amOnPage($this->url);
        }
    }

    private function isAlreadyInBasket(): bool
    {
        return false !== strpos(
            $this->acceptanceTester->grabAttributeFrom($this->btnAddToBasket, 'class'),
            'hidden'
        );
    }

    private function getPurchaseProtectionCheckboxSelector(int $servicePosition): array
    {
        return ['css' => sprintf(
            '.ServicesListForProduct__protection-purchase-service .AdditionalServices__item:nth-child(%s) .checkbox-input',
            $servicePosition
        )];
    }

    private function getPurchaseProtectionPriceSelector(int $servicePosition): array
    {
        return ['css' => sprintf(
            '.ServicesListForProduct__protection-purchase-service .AdditionalServices__item:nth-child(%s) .AdditionalServices__price-block_current-price',
            $servicePosition
        )];
    }
}

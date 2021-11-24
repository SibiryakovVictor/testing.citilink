<?php
namespace Page\Acceptance;

use AcceptanceTester;

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

    public function ensureHasGood(string $goodTitle)
    {
        $this->ensureOnUrl();

        $this->acceptanceTester->canSee($goodTitle);
    }

    public function ensureTotalPriceEquals(string $totalPrice)
    {
        $this->ensureOnUrl();

        $this->acceptanceTester->canSee($totalPrice, ['css' => '.OrderFinalPrice__price-current_current-price']);
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

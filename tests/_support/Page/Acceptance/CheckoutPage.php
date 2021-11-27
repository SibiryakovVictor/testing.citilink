<?php
namespace Page\Acceptance;

use AcceptanceTester;

class CheckoutPage
{
    // include url of current page
    private const URL = '/order/checkout';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * @var AcceptanceTester;
     */
    protected $acceptanceTester;

    public function __construct(AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
        $this->ensureOnPage();
    }

    public function assertOnPage()
    {
        $this->acceptanceTester->seeInCurrentUrl(self::URL);

        $this->acceptanceTester->see('Оформление заказа');
    }

    public function assertEmpty()
    {
        $this->acceptanceTester->see('В корзине нет товаров');
    }

    public function ensureOnPage()
    {
        if (!$this->acceptanceTester->tryToSeeInCurrentUrl(self::URL)) {
            $this->acceptanceTester->amOnPage(self::URL);
        }
    }
}

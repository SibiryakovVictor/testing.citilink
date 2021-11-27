<?php
declare(strict_types=1);

namespace Page\Acceptance;

use AcceptanceTester;

class FavoritesPage
{
    private const URL = '/profile/wishlist';

    private array $clearButton = ['css' => '.js--FavouritesLeftSidebar__button-remove-all'];

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * @var AcceptanceTester;
     */
    protected AcceptanceTester $acceptanceTester;

    public function __construct(AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }

    public function assertFirstGoodShortTitleEquals(string $goodShortTitle)
    {
        $this->ensureOnUrl();

        $this->acceptanceTester->see($goodShortTitle, [
            'css' => '.ProductCardVertical:first-child .ProductCardVertical__name'
        ]);
    }

    public function assertContainsGood(string $goodShortTitle)
    {
        $this->ensureOnUrl();

        $this->acceptanceTester->see($goodShortTitle, [
            'css' => '.ProductListFavourites'
        ]);
    }

    public function clear()
    {
        $this->ensureOnUrl();

        $this->acceptanceTester->scrollTo($this->clearButton, null, -100);
        $this->acceptanceTester->waitForElementClickable($this->clearButton);
        $this->acceptanceTester->clickWithLeftButton($this->clearButton);
        $this->acceptanceTester->wait(5);
    }

    public function ensureIsEmpty()
    {
        $this->ensureOnUrl();

        if ($this->acceptanceTester->tryToScrollTo($this->clearButton, null, -100)) {
            $this->clear();
        }
    }

    private function ensureOnUrl()
    {
        if (!$this->acceptanceTester->tryToSeeInCurrentUrl(self::URL)) {
            $this->acceptanceTester->amOnPage(self::URL);
            $this->acceptanceTester->seeInCurrentUrl(self::URL);
        }
    }
}

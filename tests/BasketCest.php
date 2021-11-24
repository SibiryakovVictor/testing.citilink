<?php
declare(strict_types=1);

class BasketCest
{
    /**
     * @before ensureBasketIsEmpty
     */
    public function testAddGoodFromGoodPage(AcceptanceTester $I)
    {
        $I->amOnPage('/catalog/noutbuki--ultrabuki/');

        $goodTitle = $I->grabAttributeFrom(['css' => '.ProductCardHorizontal:first-child .ProductCardHorizontal__title'], 'title');
        $goodPrice = trim($I->grabTextFrom(
            ['css' => '.ProductCardHorizontal:first-child .ProductCardHorizontal__price_current-price']
        ));
        $I->click(['css' => '.ProductCardHorizontal:first-child .ProductCardHorizontal__title']);

        $I->waitForElementClickable(['css' => '.ProductHeader__buy-button'], 15);
        $I->scrollTo(['css' => '.ProductHeader__buy-button'], null, -200);
        $I->click(['css' => '.ProductHeader__buy-button']);

        try {
            $I->waitForElementClickable(['css' => '.UpsaleBasket__buttons button.UpsaleBasket__order'], 15);
            $I->scrollTo(['css' => '.UpsaleBasket__main-popup__close'], null, -50);
            $I->clickWithLeftButton(['css' => '.UpsaleBasket__main-popup__close']);
        } catch (Exception $exception) {}

        $I->scrollTo(['css' => '.ProductHeader__buy-button'], null, -200);
        $I->click(['css' => '.ProductHeader__buy-button']);

        $I->amOnPage('/order/');
        $I->canSee($goodTitle);
        $I->canSee($goodPrice, ['css' => '.OrderFinalPrice__price-current_current-price']);
    }

    /**
     * @before ensureBasketIsEmpty
     */
    public function testAddGoodFromGoodCatalog(AcceptanceTester $I)
    {
        $this->ensureBasketIsEmpty($I);

        $I->amOnPage('/catalog/noutbuki--ultrabuki/');

        $goodTitle = $I->grabAttributeFrom(['css' => '.ProductCardHorizontal:first-child .ProductCardHorizontal__title'], 'title');
        $goodPrice = trim($I->grabTextFrom(
            ['css' => '.ProductCardHorizontal:first-child .ProductCardHorizontal__price_current-price']
        ));


    }

    private function ensureBasketIsEmpty(AcceptanceTester $I)
    {
        if (!$I->tryToSeeInCurrentUrl('/order/')) {
            $I->amOnPage('/order/');
        }
        $I->tryToClick(['css' => 'button.OrderFinalPrice__empty-cart']);
    }
}

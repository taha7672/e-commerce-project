<?php

namespace App\Services;

use Iyzipay\Options;
use Iyzipay\Model\PaymentCard;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Address;
use Iyzipay\Model\Payment;
use Iyzipay\Request\CreatePaymentRequest;
use App\Models\Product;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\BasketItemType;


class IyzipayService
{
    protected $options;

    public function __construct($credentials)
    {
        $this->options = new Options();
        // $this->options->setApiKey(config('iyzipay.api_key'));
        // $this->options->setSecretKey(config('iyzipay.secret_key'));
        // $this->options->setBaseUrl(config('iyzipay.base_url'));
        $this->options->setApiKey($credentials['api_key']);
        $this->options->setSecretKey($credentials['api_secret']);
        $this->options->setBaseUrl($credentials['base_url']);
    }

    public function createPayment($data)
    {
        $request = new CreatePaymentRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId(uniqid());
        $request->setPrice(round($data['total_amount'], 2));
        $request->setPaidPrice(round($data['paid_amount'], 2));
        $request->setCurrency(\Iyzipay\Model\Currency::TL);
        $request->setInstallment(1); // Adjust according to your needs
        $request->setBasketId("BASKET_" . $data['basket_id']);
        $request->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);

        // Payment Card
        $paymentCard = new PaymentCard();
        $paymentCard->setCardHolderName($data['card_holder_name']);
        $paymentCard->setCardNumber($data['card_number']);
        $paymentCard->setExpireMonth($data['expire_month']);
        $paymentCard->setExpireYear($data['expire_year']);
        $paymentCard->setCvc($data['cvc']);
        // $paymentCard->setRegisterCard(0);
        $request->setPaymentCard($paymentCard);

        // Buyer
        $buyer = new Buyer();
        $buyer->setId($data['user_id']);
        $buyer->setName($data['billing_address']->first_name);
        $buyer->setSurname($data['billing_address']->last_name);
        $buyer->setGsmNumber($data['billing_address']->phone);
        $buyer->setEmail($data['billing_address']->email);
        $buyer->setIdentityNumber('CUST_'.$data['user']->id);
        $buyer->setLastLoginDate(date('Y-m-d h:i:s'));
        $buyer->setRegistrationDate(\Carbon\Carbon::parse($data['user']->created_at)->format("Y-m-d h:i:s"));
        $buyer->setRegistrationAddress($data['billing_address']->address_line1);
        $buyer->setIp($data['ip']);
        $buyer->setCity($data['billing_address']->city);
        $buyer->setCountry($data['billing_address']->country);
        $buyer->setZipCode($data['billing_address']->postal_code);
        $request->setBuyer($buyer);

        // Address
        $shippingAddress = new Address();
        $shippingAddress->setContactName($data['shipping_address']->first_name . ' ' . $data['shipping_address']->last_name);
        $shippingAddress->setCity($data['shipping_address']->city);
        $shippingAddress->setCountry($data['shipping_address']->country);
        $shippingAddress->setAddress($data['shipping_address']->address_line1);
        $shippingAddress->setZipCode($data['shipping_address']->postal_code);
        $request->setShippingAddress($shippingAddress);

        $billingAddress = new Address();
        $billingAddress->setContactName($data['billing_address']->first_name . ' ' . $data['billing_address']->last_name);
        $billingAddress->setCity($data['billing_address']->city);
        $billingAddress->setCountry($data['billing_address']->country);
        $billingAddress->setAddress($data['billing_address']->address_line1);
        $billingAddress->setZipCode($data['billing_address']->postal_code);
        $request->setBillingAddress($billingAddress);
		
		// Add Basket Items
        $basketItems = [];
		foreach($data['order_items'] as $order_item){
			$product = Product::find($order_item['product_id']);
			$categories = $product->productCategories(); 
			
			$basketItem = new BasketItem();
			$basketItem->setId( $order_item['product_id'] );
			$basketItem->setName($product->name); 
			$basketItem->setCategory1($categories->first()->category()->first()->name);
			$basketItem->setCategory2($categories->first()->subCategory()->first()->name);
			$basketItem->setItemType(BasketItemType::PHYSICAL);
			$basketItem->setPrice($order_item['price_at_order']);
			$basketItems[] = $basketItem;
		}
		
        $request->setBasketItems($basketItems);
		
        return Payment::create($request, $this->options);
    }
}

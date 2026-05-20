import LoginController from './LoginController'
import RegisterController from './RegisterController'
import ProductHomeController from './ProductHomeController'
import ShopController from './ShopController'
import LogoutController from './LogoutController'
import CartController from './CartController'
import CheckoutController from './CheckoutController'
import CustomerProfileController from './CustomerProfileController'
import OrderController from './OrderController'
import WishlistController from './WishlistController'
import StripeWebhookController from './StripeWebhookController'

const Api = {
    LoginController: Object.assign(LoginController, LoginController),
    RegisterController: Object.assign(RegisterController, RegisterController),
    ProductHomeController: Object.assign(ProductHomeController, ProductHomeController),
    ShopController: Object.assign(ShopController, ShopController),
    LogoutController: Object.assign(LogoutController, LogoutController),
    CartController: Object.assign(CartController, CartController),
    CheckoutController: Object.assign(CheckoutController, CheckoutController),
    CustomerProfileController: Object.assign(CustomerProfileController, CustomerProfileController),
    OrderController: Object.assign(OrderController, OrderController),
    WishlistController: Object.assign(WishlistController, WishlistController),
    StripeWebhookController: Object.assign(StripeWebhookController, StripeWebhookController),
}

export default Api
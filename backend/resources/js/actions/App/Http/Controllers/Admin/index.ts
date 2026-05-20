import DashboardController from './DashboardController'
import ProductController from './ProductController'
import BrandController from './BrandController'
import UserController from './UserController'
import CustomerOrderController from './CustomerOrderController'

const Admin = {
    DashboardController: Object.assign(DashboardController, DashboardController),
    ProductController: Object.assign(ProductController, ProductController),
    BrandController: Object.assign(BrandController, BrandController),
    UserController: Object.assign(UserController, UserController),
    CustomerOrderController: Object.assign(CustomerOrderController, CustomerOrderController),
}

export default Admin
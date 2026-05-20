import Cashier from './Cashier'
import Fortify from './Fortify'
import Sanctum from './Sanctum'
import Telescope from './Telescope'

const Laravel = {
    Cashier: Object.assign(Cashier, Cashier),
    Fortify: Object.assign(Fortify, Fortify),
    Sanctum: Object.assign(Sanctum, Sanctum),
    Telescope: Object.assign(Telescope, Telescope),
}

export default Laravel
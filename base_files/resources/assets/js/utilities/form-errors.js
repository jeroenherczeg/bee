import Lodash from 'lodash'

export default class FormErrors {

    constructor (errors) {
        this.errors = errors
    }

    get (field) {
        if (this.has(field)) {
            return this.errors[field][0]
        }
    }

    set (errors) {
        this.errors = errors
    }

    has (field) {
        return Lodash.indexOf(Lodash.keys(this.errors), field) > -1
    }

    forget () {
        this.errors = {}
    }
}

import FormErrors from './form-errors'

export default class Form {

    constructor () {
        this.data = new FormData()
        this.errors = new FormErrors()
        this.busy = false
        this.successful = false
    }

    setData (e) {
        this.data = new FormData(e.target)
    }

    startProcessing () {
        this.errors.forget()
        this.busy = true
        this.successful = false
    }

    finishProcessing () {
        this.data = new FormData()
        this.busy = false
        this.successful = true
    }

    resetStatus () {
        this.data = new FormData()
        this.errors.forget()
        this.busy = false
        this.successful = false
    }

    setErrors (errors) {
        this.busy = false
        this.errors.set(errors)
    }

    setAttribute (name, value) {
        this.data.append(name, value)
    }
}

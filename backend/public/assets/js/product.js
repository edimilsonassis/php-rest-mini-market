
class Product {

    el = {
        form: document.getElementById('form-product'),
        btnSave: document.getElementById('btnSave'),
    }

    async getProduct() {
        const response = await fetchGet('product')

        console.log(response.data);

        this.product = response.data
    }

    async init() {

        // on click save button
        this.el.btnSave.addEventListener('click', (e) => {
            const submitEvent = new Event('submit', { bubbles: true, cancelable: true });
            this.el.form.dispatchEvent(submitEvent);
        })

        // on submit form
        this.el.form.addEventListener('submit', async (e) => {
            e.preventDefault()

            const formData = new FormData(this.el.form)

            const body = Object.fromEntries(formData.entries())

            if (body.id)
                await fetchPut(`products/${body.id}`, body)
            else
                await fetchPut(`products`, body)

            location.href = '/produtos'
        })

    }

    constructor() {
        this.init()
    }
}

window.addEventListener('load', async () => {
    app.product = new Product()
})

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
            Swal.fire('Desenvolvendo ...')
            // if (!e.currentTarget.value)
            //     return

            // const submitEvent = new Event('submit', { bubbles: true, cancelable: true });
            // this.el.form.dispatchEvent(submitEvent);
        })
    }

    constructor() {
        this.init()
    }
}

window.addEventListener('load', async () => {
    app.product = new Product()
})

class ProductsTypes {

    el = {
        table: {
            products: document.getElementById('table-products-types'),
            body: document.querySelector('#table-products-types tbody'),
            row: document.querySelector('#table-products-types tbody tr'),
        }
    }

    async getProducts() {
        const response = await fetchGet('types')

        console.log(response.data);

        this.products = response.data
    }

    async init() {
        await this.getProducts();

        this.el.table.body.innerHTML = ''

        this.products.forEach(data => {
            const row = this.el.table.row.cloneNode(true)

            row.querySelectorAll(`[data-key]`).forEach(el => el.fillDisplay(data))

            this.el.table.body.append(row)
        });
    }

    constructor() {
        this.init()
    }
}

window.addEventListener('load', async () => {
    app.products = new ProductsTypes()
})
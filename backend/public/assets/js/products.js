
class Products {

    el = {
        table: {
            products: document.getElementById('table-products'),
            body: document.querySelector('#table-products tbody'),
            row: document.querySelector('#table-products tbody tr'),
        }
    }

    async getProducts() {
        const response = await fetchGet('products')

        console.log(response.data);

        this.products = response.data
    }

    async init() {
        await this.getProducts();

        this.el.table.body.innerHTML = ''

        this.products.forEach(data => {
            const row = this.el.table.row.cloneNode(true)

            row.querySelectorAll(`[data-key]`).forEach(el => {
                el.setAttribute('href', `/produtos/${data.prd_id}`)
                el.fillDisplay(data)
            })

            this.el.table.body.append(row)
        });
    }

    constructor() {
        this.init()
    }
}

window.addEventListener('load', async () => {
    app.products = new Products()
})
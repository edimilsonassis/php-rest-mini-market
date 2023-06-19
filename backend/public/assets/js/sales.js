
class Sales {

    el = {
        table: {
            sales: document.getElementById('table-sales'),
            body: document.querySelector('#table-sales tbody'),
            row: document.querySelector('#table-sales tbody tr'),
        }
    }

    async getSales() {
        const response = await fetchGet('sales')

        console.log(response.data);

        this.sales = response.data
    }

    async init() {
        await this.getSales();

        this.el.table.body.innerHTML = ''

        this.sales.forEach(data => {
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
    app.sales = new Sales()
})

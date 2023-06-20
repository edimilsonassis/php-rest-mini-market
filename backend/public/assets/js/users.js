
class Users {

    el = {
        table: {
            sales: document.getElementById('table-users'),
            body: document.querySelector('#table-users tbody'),
            row: document.querySelector('#table-users tbody tr'),
        }
    }

    async getUsers() {
        const response = await fetchGet('users')

        console.log(response.data);

        this.sales = response.data
    }

    async init() {
        await this.getUsers();

        this.el.table.body.innerHTML = ''

        this.sales.forEach(data => {
            const row = this.el.table.row.cloneNode(true)

            row.querySelectorAll(`[data-key]`).forEach(el => {
                el.setAttribute('href', `/usuarios/${data.usr_id}`)
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
    app.sales = new Users()
})

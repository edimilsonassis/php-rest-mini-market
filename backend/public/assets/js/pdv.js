class PDV {

    data = {
        sale: {},
    }

    el = {
        form: document.getElementById('form-pdv'),
        btnNewSale: document.getElementById('newSale'),
        btnCloseSale: document.getElementById('closeSale'),
        checkout: document.getElementById('checkout'),
        resume: document.getElementById('resume'),
        table: {
            pdv: document.getElementById('table-items'),
            body: document.querySelector('#table-items tbody'),
            row: document.querySelector('#table-items tbody tr'),
        }
    }

    resetSale() {
        this.data.sale = {
            sls_id: null,
            sls_client: null,
            sls_id_user: null,
            sls_total_price: null,
            sls_total_price_taxes: null,
            sls_date: null,
            items: [
            ]
        }
    }

    toggleBtnNewSale() {
        if (this.data.sale.sls_id) {
            this.el.btnNewSale.classList.add('d-none')
            this.el.btnCloseSale.classList.remove('d-none')
        } else {
            this.el.btnCloseSale.classList.add('d-none')
            this.el.btnNewSale.classList.remove('d-none')
        }
    }

    async getSale() {
        if (!this.data.sale.sls_id)
            return

        const response = await fetchGet(`sales/${this.data.sale.sls_id}`)

        console.log('getSale', response.data);

        this.data.sale = response.data
    }

    async getSaleItems() {
        if (!this.data.sale.sls_id)
            return

        const response = await fetchGet(`sales/${this.data.sale.sls_id}/items`)

        console.log('getSaleItems', response.data);

        this.data.sale.items = response.data
    }

    async refreshItems() {
        await this.getSaleItems();

        this.el.table.body.innerHTML = ''

        this.data.sale.items.forEach(data => {
            const row = this.el.table.row.cloneNode(true)

            row.querySelectorAll(`[data-key]`).forEach(el => el.fillDisplay(data))

            this.el.table.body.append(row)
        });
    }

    async freshSale() {
        await this.getSale()
        await this.refreshItems()

        this.toggleBtnNewSale()

        this.el.form.id_product.value = ''
        this.el.form.id_product.focus()

        this.el.resume.querySelectorAll(`[data-key]`).forEach(el => {
            el.fillDisplay(this.data)
        })

        this.el.checkout.querySelectorAll(`[data-key]`).forEach(el => {
            el.fillDisplay(this.data.sale.items[0])
        })
    }

    async init() {
        this.resetSale()
        this.refreshItems()

        // listen for changes in product field
        this.el.form.id_product.addEventListener('change', (e) => {
            // if (!e.currentTarget.value)
            //     return

            // const submitEvent = new Event('submit', { bubbles: true, cancelable: true });
            // this.el.form.dispatchEvent(submitEvent);
        })

        // insert new product
        this.el.form.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!this.data.sale.sls_id)
                return Swal.fire('Por favor, inicie a venda')

            const data = {
                id_product: this.el.form.id_product.value,
                qtde: this.el.form.qtde.value
            }

            const response = await fetchPost(`sales/${this.data.sale.sls_id}/items`, data)

            if (response.status != 200)
                Swal.fire({
                    title: 'Erro',
                    text: (response.data && response.data.message) ?? 'Erro desconhecido',
                    icon: 'warning'
                })

            await this.freshSale()
        })

        // new sale
        this.el.btnNewSale.addEventListener('click', async () => {
            if (this.data.sale.sls_id)
                return Swal.fire({
                    title: 'Venda em Andamento',
                    icon: 'info'
                })

            const prompt = await Swal.fire({
                title: 'Deseja informar o CPF?',
                input: 'text',
                inputPlaceholder: 'CPF',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: 'Iniciar',
                cancelButtonText: 'Cancelar',
                denyButtonText: 'Não Informar',
                reverseButtons: true,
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage('CPF inválido')
                    }

                    return value
                }
            })

            if (prompt.isDismissed)
                return

            const data = {
                client: prompt.value || 'Cliente sem CPF'
            }

            const response = await fetchPost('sales', data)

            this.data.sale.sls_client = data.client
            this.data.sale.sls_id = response.data

            await this.freshSale()
        })

        //  sale
        this.el.btnCloseSale.addEventListener('click', async () => {
            if (!this.data.sale.sls_id)
                return Swal.fire({
                    title: 'Nenhuma Venda em Andamento',
                    icon: 'info'
                })

            const prompt = await Swal.fire({
                title: 'Deseja encerrar a venda?',
                showCancelButton: true,
                confirmButtonText: 'Encerrar',
                cancelButtonText: 'Voltar',
                reverseButtons: true,
            })

            if (prompt.isDismissed)
                return

            this.resetSale()

            await this.freshSale()
        })

    }

    constructor() {
        this.init()
    }
}

window.addEventListener('load', async () => {
    app.pdv = new PDV()
})

class User {

    el = {
        form: document.getElementById('form-user'),
        btnSave: document.getElementById('btnSave'),
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
                await fetchPut(`users/${body.id}`, body)
            else
                await fetchPost(`users`, body)

            location.href = '/usuarios'
        })

    }

    constructor() {
        this.init()
    }
}

window.addEventListener('load', async () => {
    app.user = new User()
})
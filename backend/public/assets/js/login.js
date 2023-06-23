window.onload = async () => {
    const form = document.getElementById('login');
    const btnSubmit = document.querySelector('[type="submit"]');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const body = {
            username: form.username.value,
            password: form.password.value
        }

        document.querySelectorAll('.is-invalid').forEach(element => {
            element.classList.remove('is-invalid')
        });

        btnSubmit.loading(true)

        try {
            const url = `${baseUrl}/auth/login`;

            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(body),
            });

            res.data = await res.json();

            console.log(res);

            if (res.status != 200) {
                throw res
            }

            app.auth.login(res.data.user, res.data.jwt)

            window.location.href = '/';

        } catch (err) {
            console.log(err);

            switch (err.status) {
                case 400:
                    form.displayFieldError(err.data.errors)
                    break;

                default:
                    Swal.fire({
                        text: (err.data && err.data.message) ?? err.message,
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    })
                    break;
            }

        }

        btnSubmit.loading(false)

    })
}
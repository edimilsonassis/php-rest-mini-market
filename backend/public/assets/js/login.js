window.onload = async () => {
    const form = document.getElementById('login');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const body = {
            username: form.username.value,
            password: form.password.value
        }

        document.querySelectorAll('.is-invalid').forEach(element => {
            element.classList.remove('is-invalid')
        });

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
                    err.data && err.data.errors && err.data.errors.forEach(element => {
                        document.querySelector('[name="' + element.field + '"]').classList.add('is-invalid')
                    });
                    break;

                case 401:
                    if (err.data && err.data.message == "Invalid password")
                        document.querySelector('[name="password"]').classList.add('is-invalid')
                    else
                        document.querySelector('[name="username"]').classList.add('is-invalid')
                    break;

                default:
                    Swal.fire({
                        title: 'Ocorreu um erro',
                        text: (err.data && err.data.message) ?? err.message,
                        icon: 'warning',
                        confirmButtonText: 'Ok'
                    })
                    break;
            }

        }
    })
}
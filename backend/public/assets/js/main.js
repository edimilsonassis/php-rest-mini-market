const baseUrl = `${location.origin}/api`

function toQueryString(query) {
    return Object.keys(query)
        .map(key => `${key}=${query[key]}`)
        .join('&');
}

function accessNestedProperty(obj, propertyString) {
    if (!propertyString)
        return

    const properties = propertyString.split('.');
    let value = obj;

    for (let property of properties) {
        if (value && typeof value === 'object' && property in value) {
            value = value[property];
        } else {
            return undefined;
        }
    }

    return value;
}

HTMLButtonElement.prototype.loading = function (isLoading) {
    const status = this.querySelector('[role="status"]')
    const statusHidden = this.querySelector('[role="status-hidden"]')

    if (isLoading === true) {
        this.disabled = true
        status && status.classList.remove('d-none')
        statusHidden && statusHidden.classList.add('d-none')
    }

    if (isLoading === false) {
        this.disabled = false
        status && status.classList.add('d-none')
        statusHidden && statusHidden.classList.remove('d-none')
    }

    return this.disabled

}

HTMLFormElement.prototype.displayFieldError = function (errors) {
    errors.forEach(element => {
        const el = document.querySelector('[name="' + element.field + '"]')

        if (!el)
            return

        const invalidFeedback = el.parentNode.querySelector('.invalid-feedback')

        if (invalidFeedback) {
            invalidFeedback.textContent = element.errors.toLocaleString()
        }

        el.classList.add('is-invalid')
    });
}

HTMLElement.prototype.fillDisplay = function (data) {
    const placeholder = this.getAttribute('data-placeholder')
    const format = this.getAttribute('data-format')
    const key = this.getAttribute('data-key')

    let value = accessNestedProperty(data, key) ?? placeholder

    switch (format) {
        case 'money':
            this.textContent = value != null && parseFloat(value).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
            break;

        case 'datatime':
            this.textContent = value != null && new Date(value).toLocaleString(
                'pt-BR',
                {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false,
                    timeZone: 'America/Sao_Paulo',
                }
            )

            break;

        default:
            this.textContent = value
    }
}

async function fetchJson(method, url, query, body) {
    url = `${baseUrl}/${url}`;

    if (query) {
        url += url.indexOf('?') > -1 ? `&${toQueryString(query)}` : `?${toQueryString(query)}`
    }

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${await app.auth.getToken()}`,
            },
            body: JSON.stringify(body),
        });

        response.data = await response.json();

        if (response.status === 401)
            throw window.location.href = '/login';

        return response;
    } catch (error) {
        console.error('Error:', error);

        await error.text();

        throw error;
    }
}

async function fetchGet(url, query) {
    return await fetchJson('GET', url, query)
}

async function fetchPost(url, body) {
    return await fetchJson('POST', url, null, body)
}

async function fetchPut(url, body) {
    return await fetchJson('PUT', url, null, body)
}

async function fetchDelete(url) {
    return await fetchJson('DELETE', url, null, body)
}

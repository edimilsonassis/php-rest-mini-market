const baseUrl = 'http://localhost:8080/api';

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

Node.prototype.fillDisplay = function (data) {
    const placeholder = this.getAttribute('data-placeholder')
    const format = this.getAttribute('data-format')
    const key = this.getAttribute('data-key')

    let value = accessNestedProperty(data, key) ?? placeholder

    switch (format) {
        case 'money':
            this.textContent = value && parseFloat(value).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
            break;

        case 'datatime':
            this.textContent = value
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

        if (response.status === 401)
            throw window.location.href = '/login';

        response.data = await response.json();

        return response;
    } catch (error) {
        console.error('Error:', error);
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

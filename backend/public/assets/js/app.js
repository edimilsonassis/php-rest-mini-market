
class Auth {
    async login(user, token) {
        localStorage.setItem('user', JSON.stringify(user));
        localStorage.setItem('token', token);
    }

    async logout() {
        localStorage.removeItem('user');
        localStorage.removeItem('token');

        this.checkAuth()
    }

    async getMe() {
        const user = localStorage.getItem('user');
        return JSON.parse(user);
    }

    async getToken() {
        return localStorage.getItem('token');
    }

    async checkAuth() {
        const user = await this.getMe()

        if (!user) {
            if (location.pathname != '/login') {
                return window.location.href = '/login';
            }
            return false
        }

        if (location.pathname == '/login') {
            return window.location.href = '/';
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('[data-key="urs_name"]').textContent = user.urs_name;
        })

        return true
    }
}

class App {
    constructor() {
        this.auth = new Auth();

        this.auth.checkAuth();
    }
}

const app = new App();
